var request       = require('request');
var fs          = require('fs');
var express       = require('express');
var util            = require('util');
var app         = express();

var SteamUser       = require('steam-user');
var TradeOfferManager   = require('steam-tradeoffer-manager');
var SteamCommunity = require('steamcommunity');
var SteamID         = SteamCommunity.SteamID;

require('dotenv').config();

/*******************
 *    CONSTANTS    *
 *******************/

const HTTP_PORT = 8888;
const LOGS_PATH = __dirname + '/logs/logs' + Date.now() + '.log';
const STDOUT_PATH = __dirname + '/logs/stdout' + Date.now() + '.log';
const STDERR_PATH = __dirname + '/logs/errout' + Date.now() + '.log';

/*******************
 *    VARIABLES    *
 *******************/

var client = new SteamUser();
var manager = new TradeOfferManager({
  'steam': client,
  'domain': 'localhost',
  'language': 'en'
});
var community = new SteamCommunity();

if (fs.existsSync('polldata.json')) {
    manager.pollData = JSON.parse(fs.readFileSync('polldata.json'));
}

client.on('loggedOn', function (det){
    console.log("Working");
});

client.on('error', function (err) {
    console.log(err);
})

client.on('webSession', function(sessionID, cookies) {
    console.log("Got web session");
    manager.setCookies(cookies, function(err) {
        if (err) {
            console.log(err);
            process.exit(1); // Fatal error since we couldn't get our API key
            return;
        }

        console.log("Got API key: " + manager.apiKey);
    });
   
    // Do something with these cookies if you wish
});

manager.on('pollData', function(pollData) {
    fs.writeFile('polldata.json', JSON.stringify(pollData), function() {});
});


/*********************
 *    STATIC CODE    *
 *********************/
/*
var log_file = fs.createWriteStream(LOGS_PATH, {flags : 'w'});
var log_stdout = process.stdout;

var out_file = fs.createWriteStream(STDOUT_PATH);
var err_file = fs.createWriteStream(STDERR_PATH);

process.stdout.write = out_file.write.bind(out_file);
process.stderr.write = err_file.write.bind(err_file);

console.log = function(d) { //
  log_file.write(util.format(d) + '\n');
  log_stdout.write(util.format(d) + '\n');
};

process.on('uncaughtException', function(err) {
  console.error((err && err.stack) ? err.stack : err);
});
*/

/***************
 *    PAGES    *
 ***************/

app.get('/login', (req, res) => {
    var code = req.query.code;
    
    var logOnOptions = {
        accountName: process.env.ACCOUNT_NAME,
        password: process.env.ACCOUNT_PASS,
        twoFactorCode: code
    };

    client.logOn(logOnOptions);
    console.log('Trying to log in to Steam');

    res.send('Trying to login');
});

app.get('/inventory', (req, res) => {
    manager.getUserInventoryContents(new SteamID(req.query.steamid), 730, 2, true, function(err, inventory) {
       
        res.send(inventory);
    });
});

app.get('/status', (req, res) => {
    res.send(JSON.stringify({
        online: true
    }));
});

app.get('/sendTradeOffer', (req, res) => {

    var encoded_data = req.query.data;

    var data = JSON.parse(encoded_data);

    if(data.tradelink == undefined) {
        res.send('Missing trade link');
        console.log('Missing trade link');
        return;
    }

    if(data.encoded_items == undefined) {
        res.send('Missing item list');
        console.log('Missing item list');
        return;
    }

    var itemsParsed = JSON.parse(data.encoded_items);

    var offer = manager.createOffer(decodeURI(decodeURI(data.tradelink)));

    console.log('TradeLink: ' + decodeURI(data.tradelink));

    for(var i = 0; i < itemsParsed.length; i++) {
        var addedItem = offer.addTheirItem({
            assetid: itemsParsed[i].assetid,
            appid: itemsParsed[i].appid,
            contextid: itemsParsed[i].contextid,
            amount: 1
        });

        if(addedItem === true) {
            console.log('Added item sucessfully [' + (i + 1) + '/' + itemsParsed.length + ']: ' + itemsParsed[i].assetid);
        } else {
            console.log('Failed to add item: ' + itemsParsed[i].assetid);
            res.send('Error adding item');
            return;
        }
    }

    console.log('Added all items sucessfully!');

    offer.send(function (err, status) {
        if(!err) {
            console.log('Sent Trade Offer!');
            res.send(status);
        } else {
            console.log('Error sending Trade Offer');
            res.send(err);
        }
    });
});

app.get('/logs', (req, res) => {
  res.type('text');
  res.send(fs.readFileSync(LOGS_PATH));
});

app.get('/stdout', (req, res) => {
  res.type('text');
  res.send(fs.readFileSync(STDOUT_PATH));
});

app.get('/stderr', (req, res) => {
  res.type('text');
  res.send(fs.readFileSync(STDERR_PATH));
});

app.listen(HTTP_PORT, () => {
  console.log('Logging on ' + LOGS_PATH);
  console.log('STDOUT on: ' + STDOUT_PATH);
  console.log('STDERR on: ' + STDERR_PATH);

  console.log('Listening on ' + HTTP_PORT);
});

