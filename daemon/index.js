var request = require('request');
var fs = require('fs');
var express = require('express');
var util = require('util');
var app = express();
var rcon = require('rcon');
var bodyParser = require("body-parser");
var dotenv = require('dotenv').config({path: __dirname + '/.env'});
var csgoCDN = require('csgo-cdn');

var SteamUser = require('steam-user');
var TradeOfferManager = require('steam-tradeoffer-manager');
var SteamCommunity = require('steamcommunity');
var SteamID = SteamCommunity.SteamID;

app.use(bodyParser.urlencoded({extended: false}));
app.use(bodyParser.json());

/*******************
 *    CONSTANTS    *
 *******************/

const HTTP_PORT = 8888;
const DATE_NOW = Date.now();
const LOGS_PATH = __dirname + '/logs/logs' + DATE_NOW + '.log';
const STDOUT_PATH = __dirname + '/logs/stdout' + DATE_NOW + '.log';
const STDERR_PATH = __dirname + '/logs/errout' + DATE_NOW + '.log';

/*********************
 *    WEB LOGGING    *
 *********************/

var log_file = fs.createWriteStream(LOGS_PATH, {flags: 'w'});
var log_stdout = process.stdout;

var out_file = fs.createWriteStream(STDOUT_PATH);
var err_file = fs.createWriteStream(STDERR_PATH);

process.stdout.write = out_file.write.bind(out_file);
process.stderr.write = err_file.write.bind(err_file);

console.log = function (d) { //
    log_file.write(util.format(d) + '\n');
    log_stdout.write(util.format(d) + '\n');
};

process.on('uncaughtException', function (err) {
    console.error((err && err.stack) ? err.stack : err);
});

/*******************
 *    VARIABLES    *
 *******************/

var lastLoginAttempt = 0;
var logged = false;

var client = new SteamUser();
var community = new SteamCommunity();
var manager = new TradeOfferManager({
    'steam': client,
    'community': community,
    'domain': 'localhost',
    'language': 'en'
});

var cdn = new csgoCDN(client, {logLevel: 'debug'});

if (fs.existsSync(__dirname + '/polldata.json')) {
    manager.pollData = JSON.parse(fs.readFileSync(__dirname + '/polldata.json'));
}

client.on('loggedOn', function (det) {
    console.log("Logged on");

    setInterval(function () {
        log('Automatic session refresher called');

        client.webLogOn();
    }, 1000 * 60 * 30);
});

client.on('error', function (err) {
    console.error('############################# STARTED LOGGGING ERROR NOW #############################');

    console.error('console.error(err): ');
    console.error(err);

    console.error('console.error(err.cause): ');
    console.error(err.cause);

    console.error('console.error(err.eresult): ');
    console.error(err.eresult);

    console.error('console.error(err.strError): ');
    console.error(err.strError);

    console.error('############################# ENDED LOGGGING ERROR NOW #############################');
});

client.on('disconnected', function (eresult, msg) {
    console.log('Disconnect from Steam: ' + msg + ' -- EResult[' + eresult + ']');
    logged = false;
});

client.on('webSession', function (sessionID, cookies) {
    console.log("Got web session");
    community.setCookies(cookies);
    manager.setCookies(cookies, function (err) {
        if (err) {
            console.log(err);
            process.exit(1); // Fatal error since we couldn't get our API key
            return;
        }

        logged = true;
        console.log("Got API key: " + manager.apiKey);
        console.log('Got cookies: ' + cookies);
    });
});


community.on("sessionExpired", function (err) {
    log('Community triggered sessionExpired, trying to reloing');

    community.loggedIn(function (err, loggedIn, familyView) {
        log('community.loggedIn: ' + loggedIn);
    });
    if (Date.now() - lastLoginAttempt > 30000) {
        lastLoginAttempt = Date.now();
        console.log(" > Session Expired, relogging.");
        client.webLogOn();
    } else {
        console.log(" Session Expired, waiting a while before attempting to relogin.");
    }
});

manager.on('pollData', function (pollData) {
    fs.writeFile('polldata.json', JSON.stringify(pollData), function () {
    });
});


/*******************
 *    FUNCTIONS    *
 *******************/

function getFullURL(req) {
    var fullUrl = req.protocol + '://' + req.get('host') + req.originalUrl;

    return fullUrl;
}

function createConnection(ip, port, rcon_password) {
    console.log('Creating connection');
    let connection = new rcon(ip, port, rcon_password);



    (function (ip, port, rcon_password) {
        connection.on('auth', function () {
            console.log("RCON connected!");
            console.log('Sending update messages:');
            updateServer(connection);

        }).on('response', function (str) {
            console.log('Receiving response from RCON: ' + str);

        }).on('end', function (err) {
            console.log("RCON socket closed! = " + err);

        }).on('error', function (err) {
            console.log("ERROR: " + err + 'IP: ' + ip + ', PORT=' + port + ', PASS=' + rcon_password);
            console.log('Trying to reopen RCON connection to server');
        });
    })(ip, port, rcon_password);

    connection.connect();

    return connection;
}

function updateServer(connection) {

    setTimeout(() => {
        connection.send('say Server update 3 seconds');
    }, 1000);
    setTimeout(() => {
        connection.send('say Server update 2 seconds');
    }, 2000);
    setTimeout(() => {
        connection.send('say Server update 1 seconds');
    }, 3000);
    setTimeout(() => {
        console.log('Sending reload Admins');
        connection.send('sm_reloadadmins');
    }, 4000);
    setTimeout(() => {
        console.log('Sending reload TogsClanTags')
        connection.send('sm plugins reload togsclantags');
    }, 5000);
    setTimeout(() => {
        console.log('Sending reload CCC');
        connection.send('sm_reloadccc');
    }, 6000);
    setTimeout(() => {
        connection.send('say Server update ended.');
    }, 7000);
    setTimeout(() => {
        console.log('Ended updating');
        connection.disconnect();
        console.log('Killing connection')
    }, 8000);
}


function errorResponse(err) {
    var message;

    if (err.cause) {
        message = err.cause
    } else if (err.message) {
        message = err.message;
    } else {
        message = 'No error message.';
    }

    return JSON.stringify({
        error: true,
        message: message
    });

}

function response(res, message) {
    return JSON.stringify({
        error: false,
        message: message,
        response: res
    });
}

function log(message) {
    console.log(message);
}

/***************
 *    PAGES    *
 ***************/

app.get('/login', (req, res) => {
    log('/login routed');
    var code = req.query.code;

    client.logOn({
        accountName: process.env.ACCOUNT_NAME,
        password: process.env.ACCOUNT_PASS,
        twoFactorCode: code
    });

    log('Trying to log in to Steam with two factor code');
    res.send(response('Trying to login...'));
});

app.get('/consoleLog', (req, res) => {
    log('/consoleLog routed');
    log(req.query.message);
    res.send(response('Logged'));
});

app.get('/inventory', (req, res) => {
    log('/inventory routed');
    var steamid = req.query.steamid;
    manager.getUserInventoryContents(new SteamID(steamid), 730, 2, true, function (err, inventory) {
        if (err) {
            console.log('Error getting inventory from SteamID: ' + steamid);
            res.send(errorResponse(err));
        } else {
            console.log('Sucessfully returned inventory from SteamID: ' + steamid);
            res.send(response(inventory));
        }
    });
});

app.post('/csgoServerUpdate', (req, res) => {
    log('/csgoServerUpdate routed');

    let ip = req.body.ip;
    let port = req.body.port;
    let password = req.body.password;

    createConnection(ip, port, password);

    res.send(response('Server update queued for: ' + ip + ', port: ' + port + ', pass: ' + password));
});

app.get('/status', (req, res) => {
    res.send(response({
        online: true,
        logged: logged
    }));
});

app.get('/steam2', (req, res) => {
    log('/steam2 routed');
    var steamid = req.query.steamid;
    var steamObject = new SteamID(steamid);

    res.send(response(steamObject.getSteam2RenderedID()));
});

app.get('/getItemNameURL', (req, res) => {
    log('/getItemNameURL routed');
    var item = req.query.item;
    var phase = req.query.phase;

    if(phase) {
        res.send(response(cdn.getItemNameURL(item, phase)));
    } else {
        res.send(response(cdn.getItemNameURL(item)));
    }
});

app.get('/getTradeOffer', (req, res) => {
    log('/getTradeOffer routed');
    var offerid = req.query.offerid;

    manager.getOffer(offerid, (err, offer) => {
        if (err) {
            console.log('Error getting offer #' + offerid);
            res.send(errorResponse(err));
        } else {
            console.log('Sucessfully returned offer #' + offerid);
            res.send(response(offer));
        }
    });
});

app.get('/cancelTradeOffer', (req, res) => {
    log('/cancelTradeOffer routed');
    var id = req.query.tradeid;

    manager.getOffer(id, (err, offer) => {
        if (!err) {
            offer.cancel((err) => {
                if (!err) {
                    res.send(response('Trade offer canceled!'));
                } else {
                    console.error(err);
                    res.send(errorResponse(err));
                }
            })
        } else {
            console.error(err);
            res.send(errorResponse(err));
        }
    });
});

app.post('/sendTradeOffer', (req, res) => {
    log('/sendTradeOffer routed');

    // var encoded_data = req.query.data;
    var encoded_data = req.body.items;

    // log('EncodedData: ' + encoded_data);

    var data = JSON.parse(encoded_data);

    // log('EncodedItems: ' + data.encoded_items);
    var itemsParsed = data.encoded_items;
    var offer = manager.createOffer(data.tradelink);

    offer.setMessage(data.message);

    for (var i = 0; i < itemsParsed.length; i++) {
        var addedItem = offer.addTheirItem({
            assetid: itemsParsed[i].assetid,
            appid: itemsParsed[i].appid,
            contextid: itemsParsed[i].contextid,
            amount: 1
        });

        if (addedItem === true) {
            console.log('Added item sucessfully [' + (i + 1) + '/' + itemsParsed.length + ']: ' + itemsParsed[i].assetid);
        } else {
            console.log('Failed to add item: ' + itemsParsed[i].assetid);
            res.send(errorResponse(new Error('Failed to add item to trade offer.')));
        }
    }

    console.log('Added all items sucessfully!');

    offer.send(function (err, status) {
        if (!err) {
            console.log('Sent Trade Offer!');
            res.send(response(offer));
        } else {
            console.log('Error trying to send trade offer, refreshing session and retring again...')
            client.webLogOn();
            offer.send(function (err2, status2) {
                if (!err2) {
                    console.log('Trade Offer sent!');
                    res.send(response(offer2));
                } else {
                    console.log('Failed to send trade offer even refreshing session!')
                    console.error(err2);
                    res.send(errorResponse(err2));
                }
            });
        }
    });
});

app.get('/logs', (req, res) => {
    log('/logs routed');
    res.type('text');
    res.send(response(fs.readFileSync(LOGS_PATH, {encoding: 'utf8'})));
});

app.get('/stdout', (req, res) => {
    log('/stdout routed');
    res.type('text');
    res.send(response(fs.readFileSync(STDOUT_PATH, {encoding: 'utf8'})));
});

app.get('/stderr', (req, res) => {
    log('/stderr routed');
    res.type('text');
    res.send(response(fs.readFileSync(STDERR_PATH, {encoding: 'utf8'})));
});

app.get('/kill', (req, res) => {
    log('/kill routed');
    res.type('text');
    process.exit(1); // Fatal error since we couldn't get our API key   
    res.send('Killing this instance');
});


app.listen(HTTP_PORT, () => {
    console.log('Logging on ' + LOGS_PATH);
    console.log('STDOUT on: ' + STDOUT_PATH);
    console.log('STDERR on: ' + STDERR_PATH);

    console.log('Listening on ' + HTTP_PORT);
});