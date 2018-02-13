<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
	static $password;

	return [
		'username'       => $faker->name,
		'steamid'        => '76561198033222983',
		'avatar'         => 'https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/45/45be9bd313395f74762c1a5118aee58eb99b4688_full.jpg',
		'name'           => $faker->name,
		'accepted'       => true,
		'tradelink'      => 'https://steamcommunity.com/tradeoffer/new/?partner=22856154&token=kbAW-Ebq',
		'email'          => $faker->unique()->safeEmail,
		'remember_token' => str_random(10),
	];
});

$factory->define(App\Order::class, function (Faker\Generator $faker, $attributes) {
	if ($attributes['orderable_type'] == 'App\SteamOrder') {
		$type = 0;
	} elseif ($attributes['orderable_type'] == 'App\TokenOrder') {
		$type = 1;
	} else {
		$type = $faker->numberBetween(0, 1);
	}

	return [
		'public_id'       => substr(md5(microtime()), 0, 15),
		'server_uploaded' => 0,
		'duration'        => $faker->randomNumber(2),
		'orderable_id'    => function () use ($type) {
			if ($type === 0) {
				return factory(App\SteamOrder::class)->create()->id;
			} else {
				return factory(App\TokenOrder::class)->create()->id;
			}
		},
		'orderable_type'  => function () use ($type) {
			if ($type === 0) {
				return 'App\SteamOrder';
			} else {
				return 'App\TokenOrder';
			}
		},
	];
});

$factory->define(App\SteamOrder::class, function (Faker\Generator $faker) {
	return [
		'encoded_items'    => '[{"appid":730,"contextid":"2","assetid":"13051109688","classid":"1989279141","instanceid":"302028390","amount":1,"pos":2,"id":"13051109688","background_color":"","icon_url":"IzMF03bi9WpSBq-S-ekoE33L-iLqGFHVaU25ZzQNQcXdB2ozio1RrlIWFK3UfvMYB8UsvjiMXojflsZalyxSh31CIyHz2GZ-KuFpPsrTzBG0suOBCG25Pm-Te3WBHg84T7ZdPT6N-WChtOqVE2vAEuglSwECf_cM9mIdbprYPgx9itAdqGq0mFZwCxo8e9VKaVK4m3dCMuyaadCusA","icon_url_large":"IzMF03bi9WpSBq-S-ekoE33L-iLqGFHVaU25ZzQNQcXdB2ozio1RrlIWFK3UfvMYB8UsvjiMXojflsZalyxSh31CIyHz2GZ-KuFpPsrTzBG0suOBCG3IZDbWKCSXSlsxHLENZDvaqjSi4-TCEDyaQeF6QlhSeaMA-mcaOMzcORJr09YKqSuomUM7HRkkfddLZQOvw2QfKOAmmSJDJpoMGFMTmg","descriptions":[{"type":"html","value":"This is a sealed container of a graffiti pattern. Once this graffiti pattern is unsealed, it will provide you with enough charges to apply the graffiti pattern <b>50<\/b> times to the in-game world."},{"type":"html","value":" "},{"type":"html","value":"","color":"00a000"}],"tradable":true,"actions":[{"link":"steam:\/\/rungame\/730\/76561202255233023\/+csgo_econ_action_preview%20S%owner_steamid%A%assetid%D6945853568754688420","name":"Inspect in Game..."}],"name":"Sealed Graffiti | NaCl (Shark White)","name_color":"D2D2D2","type":"Base Grade Graffiti","market_name":"Sealed Graffiti | NaCl (Shark White)","market_hash_name":"Sealed Graffiti | NaCl (Shark White)","market_actions":[{"link":"steam:\/\/rungame\/730\/76561202255233023\/+csgo_econ_action_preview%20M%listingid%A%assetid%D6945853568754688420","name":"Inspect in Game..."}],"commodity":true,"market_tradable_restriction":7,"marketable":true,"tags":[{"internal_name":"CSGO_Type_Spray","name":"Graffiti","category":"Type","color":"","category_name":"Type"},{"internal_name":"normal","name":"Normal","category":"Quality","color":"","category_name":"Category"},{"internal_name":"Rarity_Common","name":"Base Grade","category":"Rarity","color":"b0c3d9","category_name":"Quality"},{"internal_name":"Tint19","name":"Shark White","category":"SprayColorCategory","color":"","category_name":"Graffiti Color"}],"is_currency":false,"market_marketable_restriction":0,"fraudwarnings":[]},{"appid":730,"contextid":"2","assetid":"13051109672","classid":"1989279141","instanceid":"302028390","amount":1,"pos":3,"id":"13051109672","background_color":"","icon_url":"IzMF03bi9WpSBq-S-ekoE33L-iLqGFHVaU25ZzQNQcXdB2ozio1RrlIWFK3UfvMYB8UsvjiMXojflsZalyxSh31CIyHz2GZ-KuFpPsrTzBG0suOBCG25Pm-Te3WBHg84T7ZdPT6N-WChtOqVE2vAEuglSwECf_cM9mIdbprYPgx9itAdqGq0mFZwCxo8e9VKaVK4m3dCMuyaadCusA","icon_url_large":"IzMF03bi9WpSBq-S-ekoE33L-iLqGFHVaU25ZzQNQcXdB2ozio1RrlIWFK3UfvMYB8UsvjiMXojflsZalyxSh31CIyHz2GZ-KuFpPsrTzBG0suOBCG3IZDbWKCSXSlsxHLENZDvaqjSi4-TCEDyaQeF6QlhSeaMA-mcaOMzcORJr09YKqSuomUM7HRkkfddLZQOvw2QfKOAmmSJDJpoMGFMTmg","descriptions":[{"type":"html","value":"This is a sealed container of a graffiti pattern. Once this graffiti pattern is unsealed, it will provide you with enough charges to apply the graffiti pattern <b>50<\/b> times to the in-game world."},{"type":"html","value":" "},{"type":"html","value":"","color":"00a000"}],"tradable":true,"actions":[{"link":"steam:\/\/rungame\/730\/76561202255233023\/+csgo_econ_action_preview%20S%owner_steamid%A%assetid%D6945853568754688420","name":"Inspect in Game..."}],"name":"Sealed Graffiti | NaCl (Shark White)","name_color":"D2D2D2","type":"Base Grade Graffiti","market_name":"Sealed Graffiti | NaCl (Shark White)","market_hash_name":"Sealed Graffiti | NaCl (Shark White)","market_actions":[{"link":"steam:\/\/rungame\/730\/76561202255233023\/+csgo_econ_action_preview%20M%listingid%A%assetid%D6945853568754688420","name":"Inspect in Game..."}],"commodity":true,"market_tradable_restriction":7,"marketable":true,"tags":[{"internal_name":"CSGO_Type_Spray","name":"Graffiti","category":"Type","color":"","category_name":"Type"},{"internal_name":"normal","name":"Normal","category":"Quality","color":"","category_name":"Category"},{"internal_name":"Rarity_Common","name":"Base Grade","category":"Rarity","color":"b0c3d9","category_name":"Quality"},{"internal_name":"Tint19","name":"Shark White","category":"SprayColorCategory","color":"","category_name":"Graffiti Color"}],"is_currency":false,"market_marketable_restriction":0,"fraudwarnings":[]},{"appid":730,"contextid":"2","assetid":"13051109656","classid":"1989279141","instanceid":"302028390","amount":1,"pos":4,"id":"13051109656","background_color":"","icon_url":"IzMF03bi9WpSBq-S-ekoE33L-iLqGFHVaU25ZzQNQcXdB2ozio1RrlIWFK3UfvMYB8UsvjiMXojflsZalyxSh31CIyHz2GZ-KuFpPsrTzBG0suOBCG25Pm-Te3WBHg84T7ZdPT6N-WChtOqVE2vAEuglSwECf_cM9mIdbprYPgx9itAdqGq0mFZwCxo8e9VKaVK4m3dCMuyaadCusA","icon_url_large":"IzMF03bi9WpSBq-S-ekoE33L-iLqGFHVaU25ZzQNQcXdB2ozio1RrlIWFK3UfvMYB8UsvjiMXojflsZalyxSh31CIyHz2GZ-KuFpPsrTzBG0suOBCG3IZDbWKCSXSlsxHLENZDvaqjSi4-TCEDyaQeF6QlhSeaMA-mcaOMzcORJr09YKqSuomUM7HRkkfddLZQOvw2QfKOAmmSJDJpoMGFMTmg","descriptions":[{"type":"html","value":"This is a sealed container of a graffiti pattern. Once this graffiti pattern is unsealed, it will provide you with enough charges to apply the graffiti pattern <b>50<\/b> times to the in-game world."},{"type":"html","value":" "},{"type":"html","value":"","color":"00a000"}],"tradable":true,"actions":[{"link":"steam:\/\/rungame\/730\/76561202255233023\/+csgo_econ_action_preview%20S%owner_steamid%A%assetid%D6945853568754688420","name":"Inspect in Game..."}],"name":"Sealed Graffiti | NaCl (Shark White)","name_color":"D2D2D2","type":"Base Grade Graffiti","market_name":"Sealed Graffiti | NaCl (Shark White)","market_hash_name":"Sealed Graffiti | NaCl (Shark White)","market_actions":[{"link":"steam:\/\/rungame\/730\/76561202255233023\/+csgo_econ_action_preview%20M%listingid%A%assetid%D6945853568754688420","name":"Inspect in Game..."}],"commodity":true,"market_tradable_restriction":7,"marketable":true,"tags":[{"internal_name":"CSGO_Type_Spray","name":"Graffiti","category":"Type","color":"","category_name":"Type"},{"internal_name":"normal","name":"Normal","category":"Quality","color":"","category_name":"Category"},{"internal_name":"Rarity_Common","name":"Base Grade","category":"Rarity","color":"b0c3d9","category_name":"Quality"},{"internal_name":"Tint19","name":"Shark White","category":"SprayColorCategory","color":"","category_name":"Graffiti Color"}],"is_currency":false,"market_marketable_restriction":0,"fraudwarnings":[]},{"appid":730,"contextid":"2","assetid":"13051109638","classid":"1989279141","instanceid":"302028390","amount":1,"pos":5,"id":"13051109638","background_color":"","icon_url":"IzMF03bi9WpSBq-S-ekoE33L-iLqGFHVaU25ZzQNQcXdB2ozio1RrlIWFK3UfvMYB8UsvjiMXojflsZalyxSh31CIyHz2GZ-KuFpPsrTzBG0suOBCG25Pm-Te3WBHg84T7ZdPT6N-WChtOqVE2vAEuglSwECf_cM9mIdbprYPgx9itAdqGq0mFZwCxo8e9VKaVK4m3dCMuyaadCusA","icon_url_large":"IzMF03bi9WpSBq-S-ekoE33L-iLqGFHVaU25ZzQNQcXdB2ozio1RrlIWFK3UfvMYB8UsvjiMXojflsZalyxSh31CIyHz2GZ-KuFpPsrTzBG0suOBCG3IZDbWKCSXSlsxHLENZDvaqjSi4-TCEDyaQeF6QlhSeaMA-mcaOMzcORJr09YKqSuomUM7HRkkfddLZQOvw2QfKOAmmSJDJpoMGFMTmg","descriptions":[{"type":"html","value":"This is a sealed container of a graffiti pattern. Once this graffiti pattern is unsealed, it will provide you with enough charges to apply the graffiti pattern <b>50<\/b> times to the in-game world."},{"type":"html","value":" "},{"type":"html","value":"","color":"00a000"}],"tradable":true,"actions":[{"link":"steam:\/\/rungame\/730\/76561202255233023\/+csgo_econ_action_preview%20S%owner_steamid%A%assetid%D6945853568754688420","name":"Inspect in Game..."}],"name":"Sealed Graffiti | NaCl (Shark White)","name_color":"D2D2D2","type":"Base Grade Graffiti","market_name":"Sealed Graffiti | NaCl (Shark White)","market_hash_name":"Sealed Graffiti | NaCl (Shark White)","market_actions":[{"link":"steam:\/\/rungame\/730\/76561202255233023\/+csgo_econ_action_preview%20M%listingid%A%assetid%D6945853568754688420","name":"Inspect in Game..."}],"commodity":true,"market_tradable_restriction":7,"marketable":true,"tags":[{"internal_name":"CSGO_Type_Spray","name":"Graffiti","category":"Type","color":"","category_name":"Type"},{"internal_name":"normal","name":"Normal","category":"Quality","color":"","category_name":"Category"},{"internal_name":"Rarity_Common","name":"Base Grade","category":"Rarity","color":"b0c3d9","category_name":"Quality"},{"internal_name":"Tint19","name":"Shark White","category":"SprayColorCategory","color":"","category_name":"Graffiti Color"}],"is_currency":false,"market_marketable_restriction":0,"fraudwarnings":[]},{"appid":730,"contextid":"2","assetid":"13051109626","classid":"1989279141","instanceid":"302028390","amount":1,"pos":6,"id":"13051109626","background_color":"","icon_url":"IzMF03bi9WpSBq-S-ekoE33L-iLqGFHVaU25ZzQNQcXdB2ozio1RrlIWFK3UfvMYB8UsvjiMXojflsZalyxSh31CIyHz2GZ-KuFpPsrTzBG0suOBCG25Pm-Te3WBHg84T7ZdPT6N-WChtOqVE2vAEuglSwECf_cM9mIdbprYPgx9itAdqGq0mFZwCxo8e9VKaVK4m3dCMuyaadCusA","icon_url_large":"IzMF03bi9WpSBq-S-ekoE33L-iLqGFHVaU25ZzQNQcXdB2ozio1RrlIWFK3UfvMYB8UsvjiMXojflsZalyxSh31CIyHz2GZ-KuFpPsrTzBG0suOBCG3IZDbWKCSXSlsxHLENZDvaqjSi4-TCEDyaQeF6QlhSeaMA-mcaOMzcORJr09YKqSuomUM7HRkkfddLZQOvw2QfKOAmmSJDJpoMGFMTmg","descriptions":[{"type":"html","value":"This is a sealed container of a graffiti pattern. Once this graffiti pattern is unsealed, it will provide you with enough charges to apply the graffiti pattern <b>50<\/b> times to the in-game world."},{"type":"html","value":" "},{"type":"html","value":"","color":"00a000"}],"tradable":true,"actions":[{"link":"steam:\/\/rungame\/730\/76561202255233023\/+csgo_econ_action_preview%20S%owner_steamid%A%assetid%D6945853568754688420","name":"Inspect in Game..."}],"name":"Sealed Graffiti | NaCl (Shark White)","name_color":"D2D2D2","type":"Base Grade Graffiti","market_name":"Sealed Graffiti | NaCl (Shark White)","market_hash_name":"Sealed Graffiti | NaCl (Shark White)","market_actions":[{"link":"steam:\/\/rungame\/730\/76561202255233023\/+csgo_econ_action_preview%20M%listingid%A%assetid%D6945853568754688420","name":"Inspect in Game..."}],"commodity":true,"market_tradable_restriction":7,"marketable":true,"tags":[{"internal_name":"CSGO_Type_Spray","name":"Graffiti","category":"Type","color":"","category_name":"Type"},{"internal_name":"normal","name":"Normal","category":"Quality","color":"","category_name":"Category"},{"internal_name":"Rarity_Common","name":"Base Grade","category":"Rarity","color":"b0c3d9","category_name":"Quality"},{"internal_name":"Tint19","name":"Shark White","category":"SprayColorCategory","color":"","category_name":"Graffiti Color"}],"is_currency":false,"market_marketable_restriction":0,"fraudwarnings":[]},{"appid":730,"contextid":"2","assetid":"13051109615","classid":"1989279141","instanceid":"302028390","amount":1,"pos":7,"id":"13051109615","background_color":"","icon_url":"IzMF03bi9WpSBq-S-ekoE33L-iLqGFHVaU25ZzQNQcXdB2ozio1RrlIWFK3UfvMYB8UsvjiMXojflsZalyxSh31CIyHz2GZ-KuFpPsrTzBG0suOBCG25Pm-Te3WBHg84T7ZdPT6N-WChtOqVE2vAEuglSwECf_cM9mIdbprYPgx9itAdqGq0mFZwCxo8e9VKaVK4m3dCMuyaadCusA","icon_url_large":"IzMF03bi9WpSBq-S-ekoE33L-iLqGFHVaU25ZzQNQcXdB2ozio1RrlIWFK3UfvMYB8UsvjiMXojflsZalyxSh31CIyHz2GZ-KuFpPsrTzBG0suOBCG3IZDbWKCSXSlsxHLENZDvaqjSi4-TCEDyaQeF6QlhSeaMA-mcaOMzcORJr09YKqSuomUM7HRkkfddLZQOvw2QfKOAmmSJDJpoMGFMTmg","descriptions":[{"type":"html","value":"This is a sealed container of a graffiti pattern. Once this graffiti pattern is unsealed, it will provide you with enough charges to apply the graffiti pattern <b>50<\/b> times to the in-game world."},{"type":"html","value":" "},{"type":"html","value":"","color":"00a000"}],"tradable":true,"actions":[{"link":"steam:\/\/rungame\/730\/76561202255233023\/+csgo_econ_action_preview%20S%owner_steamid%A%assetid%D6945853568754688420","name":"Inspect in Game..."}],"name":"Sealed Graffiti | NaCl (Shark White)","name_color":"D2D2D2","type":"Base Grade Graffiti","market_name":"Sealed Graffiti | NaCl (Shark White)","market_hash_name":"Sealed Graffiti | NaCl (Shark White)","market_actions":[{"link":"steam:\/\/rungame\/730\/76561202255233023\/+csgo_econ_action_preview%20M%listingid%A%assetid%D6945853568754688420","name":"Inspect in Game..."}],"commodity":true,"market_tradable_restriction":7,"marketable":true,"tags":[{"internal_name":"CSGO_Type_Spray","name":"Graffiti","category":"Type","color":"","category_name":"Type"},{"internal_name":"normal","name":"Normal","category":"Quality","color":"","category_name":"Category"},{"internal_name":"Rarity_Common","name":"Base Grade","category":"Rarity","color":"b0c3d9","category_name":"Quality"},{"internal_name":"Tint19","name":"Shark White","category":"SprayColorCategory","color":"","category_name":"Graffiti Color"}],"is_currency":false,"market_marketable_restriction":0,"fraudwarnings":[]},{"appid":730,"contextid":"2","assetid":"13051109605","classid":"1989279141","instanceid":"302028390","amount":1,"pos":8,"id":"13051109605","background_color":"","icon_url":"IzMF03bi9WpSBq-S-ekoE33L-iLqGFHVaU25ZzQNQcXdB2ozio1RrlIWFK3UfvMYB8UsvjiMXojflsZalyxSh31CIyHz2GZ-KuFpPsrTzBG0suOBCG25Pm-Te3WBHg84T7ZdPT6N-WChtOqVE2vAEuglSwECf_cM9mIdbprYPgx9itAdqGq0mFZwCxo8e9VKaVK4m3dCMuyaadCusA","icon_url_large":"IzMF03bi9WpSBq-S-ekoE33L-iLqGFHVaU25ZzQNQcXdB2ozio1RrlIWFK3UfvMYB8UsvjiMXojflsZalyxSh31CIyHz2GZ-KuFpPsrTzBG0suOBCG3IZDbWKCSXSlsxHLENZDvaqjSi4-TCEDyaQeF6QlhSeaMA-mcaOMzcORJr09YKqSuomUM7HRkkfddLZQOvw2QfKOAmmSJDJpoMGFMTmg","descriptions":[{"type":"html","value":"This is a sealed container of a graffiti pattern. Once this graffiti pattern is unsealed, it will provide you with enough charges to apply the graffiti pattern <b>50<\/b> times to the in-game world."},{"type":"html","value":" "},{"type":"html","value":"","color":"00a000"}],"tradable":true,"actions":[{"link":"steam:\/\/rungame\/730\/76561202255233023\/+csgo_econ_action_preview%20S%owner_steamid%A%assetid%D6945853568754688420","name":"Inspect in Game..."}],"name":"Sealed Graffiti | NaCl (Shark White)","name_color":"D2D2D2","type":"Base Grade Graffiti","market_name":"Sealed Graffiti | NaCl (Shark White)","market_hash_name":"Sealed Graffiti | NaCl (Shark White)","market_actions":[{"link":"steam:\/\/rungame\/730\/76561202255233023\/+csgo_econ_action_preview%20M%listingid%A%assetid%D6945853568754688420","name":"Inspect in Game..."}],"commodity":true,"market_tradable_restriction":7,"marketable":true,"tags":[{"internal_name":"CSGO_Type_Spray","name":"Graffiti","category":"Type","color":"","category_name":"Type"},{"internal_name":"normal","name":"Normal","category":"Quality","color":"","category_name":"Category"},{"internal_name":"Rarity_Common","name":"Base Grade","category":"Rarity","color":"b0c3d9","category_name":"Quality"},{"internal_name":"Tint19","name":"Shark White","category":"SprayColorCategory","color":"","category_name":"Graffiti Color"}],"is_currency":false,"market_marketable_restriction":0,"fraudwarnings":[]},{"appid":730,"contextid":"2","assetid":"13051109590","classid":"1989279141","instanceid":"302028390","amount":1,"pos":9,"id":"13051109590","background_color":"","icon_url":"IzMF03bi9WpSBq-S-ekoE33L-iLqGFHVaU25ZzQNQcXdB2ozio1RrlIWFK3UfvMYB8UsvjiMXojflsZalyxSh31CIyHz2GZ-KuFpPsrTzBG0suOBCG25Pm-Te3WBHg84T7ZdPT6N-WChtOqVE2vAEuglSwECf_cM9mIdbprYPgx9itAdqGq0mFZwCxo8e9VKaVK4m3dCMuyaadCusA","icon_url_large":"IzMF03bi9WpSBq-S-ekoE33L-iLqGFHVaU25ZzQNQcXdB2ozio1RrlIWFK3UfvMYB8UsvjiMXojflsZalyxSh31CIyHz2GZ-KuFpPsrTzBG0suOBCG3IZDbWKCSXSlsxHLENZDvaqjSi4-TCEDyaQeF6QlhSeaMA-mcaOMzcORJr09YKqSuomUM7HRkkfddLZQOvw2QfKOAmmSJDJpoMGFMTmg","descriptions":[{"type":"html","value":"This is a sealed container of a graffiti pattern. Once this graffiti pattern is unsealed, it will provide you with enough charges to apply the graffiti pattern <b>50<\/b> times to the in-game world."},{"type":"html","value":" "},{"type":"html","value":"","color":"00a000"}],"tradable":true,"actions":[{"link":"steam:\/\/rungame\/730\/76561202255233023\/+csgo_econ_action_preview%20S%owner_steamid%A%assetid%D6945853568754688420","name":"Inspect in Game..."}],"name":"Sealed Graffiti | NaCl (Shark White)","name_color":"D2D2D2","type":"Base Grade Graffiti","market_name":"Sealed Graffiti | NaCl (Shark White)","market_hash_name":"Sealed Graffiti | NaCl (Shark White)","market_actions":[{"link":"steam:\/\/rungame\/730\/76561202255233023\/+csgo_econ_action_preview%20M%listingid%A%assetid%D6945853568754688420","name":"Inspect in Game..."}],"commodity":true,"market_tradable_restriction":7,"marketable":true,"tags":[{"internal_name":"CSGO_Type_Spray","name":"Graffiti","category":"Type","color":"","category_name":"Type"},{"internal_name":"normal","name":"Normal","category":"Quality","color":"","category_name":"Category"},{"internal_name":"Rarity_Common","name":"Base Grade","category":"Rarity","color":"b0c3d9","category_name":"Quality"},{"internal_name":"Tint19","name":"Shark White","category":"SprayColorCategory","color":"","category_name":"Graffiti Color"}],"is_currency":false,"market_marketable_restriction":0,"fraudwarnings":[]},{"appid":730,"contextid":"2","assetid":"13051109583","classid":"1989279141","instanceid":"302028390","amount":1,"pos":10,"id":"13051109583","background_color":"","icon_url":"IzMF03bi9WpSBq-S-ekoE33L-iLqGFHVaU25ZzQNQcXdB2ozio1RrlIWFK3UfvMYB8UsvjiMXojflsZalyxSh31CIyHz2GZ-KuFpPsrTzBG0suOBCG25Pm-Te3WBHg84T7ZdPT6N-WChtOqVE2vAEuglSwECf_cM9mIdbprYPgx9itAdqGq0mFZwCxo8e9VKaVK4m3dCMuyaadCusA","icon_url_large":"IzMF03bi9WpSBq-S-ekoE33L-iLqGFHVaU25ZzQNQcXdB2ozio1RrlIWFK3UfvMYB8UsvjiMXojflsZalyxSh31CIyHz2GZ-KuFpPsrTzBG0suOBCG3IZDbWKCSXSlsxHLENZDvaqjSi4-TCEDyaQeF6QlhSeaMA-mcaOMzcORJr09YKqSuomUM7HRkkfddLZQOvw2QfKOAmmSJDJpoMGFMTmg","descriptions":[{"type":"html","value":"This is a sealed container of a graffiti pattern. Once this graffiti pattern is unsealed, it will provide you with enough charges to apply the graffiti pattern <b>50<\/b> times to the in-game world."},{"type":"html","value":" "},{"type":"html","value":"","color":"00a000"}],"tradable":true,"actions":[{"link":"steam:\/\/rungame\/730\/76561202255233023\/+csgo_econ_action_preview%20S%owner_steamid%A%assetid%D6945853568754688420","name":"Inspect in Game..."}],"name":"Sealed Graffiti | NaCl (Shark White)","name_color":"D2D2D2","type":"Base Grade Graffiti","market_name":"Sealed Graffiti | NaCl (Shark White)","market_hash_name":"Sealed Graffiti | NaCl (Shark White)","market_actions":[{"link":"steam:\/\/rungame\/730\/76561202255233023\/+csgo_econ_action_preview%20M%listingid%A%assetid%D6945853568754688420","name":"Inspect in Game..."}],"commodity":true,"market_tradable_restriction":7,"marketable":true,"tags":[{"internal_name":"CSGO_Type_Spray","name":"Graffiti","category":"Type","color":"","category_name":"Type"},{"internal_name":"normal","name":"Normal","category":"Quality","color":"","category_name":"Category"},{"internal_name":"Rarity_Common","name":"Base Grade","category":"Rarity","color":"b0c3d9","category_name":"Quality"},{"internal_name":"Tint19","name":"Shark White","category":"SprayColorCategory","color":"","category_name":"Graffiti Color"}],"is_currency":false,"market_marketable_restriction":0,"fraudwarnings":[]},{"appid":730,"contextid":"2","assetid":"13051109572","classid":"360475617","instanceid":"188530139","amount":1,"pos":11,"id":"13051109572","background_color":"","icon_url":"-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXH5ApeO4YmlhxYQknCRvCo04DEVlxkKgposLuoKhRf0v33dzxP7c-Jh4efqPrxN7LEmyUJ6ZRyi-yV8N6g0VXn_EBqZWj6JoHBegE8NwzX_VK8xLjn0Z6_uZ6a1zI97U38jV3f","icon_url_large":"-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXH5ApeO4YmlhxYQknCRvCo04DEVlxkKgposLuoKhRf0v33dzxP7c-Jh4efqPrxN7LEm1Rd6dd2j6fArd-iiQGwr0I6NTrxddKcdgBsZAvT_1K5leftgMTqu57IyiE27ycn-z-DyCRsIegQ","descriptions":[{"type":"html","value":"Exterior: Well-Worn"},{"type":"html","value":" "},{"type":"html","value":"A cheap option for cash-strapped players, the FAMAS effectively fills the niche between more expensive rifles and the less-effective SMGs. It has been given a patina of varying depth using masking fluid to create a military motif, sealed with a satin finish.\n\n<i>Lead by example<\/i>"},{"type":"html","value":" "},{"type":"html","value":"The Phoenix Collection","color":"9da1a9"},{"type":"html","value":" "}],"tradable":true,"actions":[{"link":"steam:\/\/rungame\/730\/76561202255233023\/+csgo_econ_action_preview%20S%owner_steamid%A%assetid%D5245222454906983604","name":"Inspect in Game..."}],"name":"FAMAS | Sergeant","name_color":"D2D2D2","type":"Restricted Rifle","market_name":"FAMAS | Sergeant (Well-Worn)","market_hash_name":"FAMAS | Sergeant (Well-Worn)","market_actions":[{"link":"steam:\/\/rungame\/730\/76561202255233023\/+csgo_econ_action_preview%20M%listingid%A%assetid%D5245222454906983604","name":"Inspect in Game..."}],"commodity":false,"market_tradable_restriction":7,"marketable":true,"tags":[{"internal_name":"CSGO_Type_Rifle","name":"Rifle","category":"Type","color":"","category_name":"Type"},{"internal_name":"weapon_famas","name":"FAMAS","category":"Weapon","color":"","category_name":"Weapon"},{"internal_name":"set_community_2","name":"The Phoenix Collection","category":"ItemSet","color":"","category_name":"Collection"},{"internal_name":"normal","name":"Normal","category":"Quality","color":"","category_name":"Category"},{"internal_name":"Rarity_Mythical_Weapon","name":"Restricted","category":"Rarity","color":"8847ff","category_name":"Quality"},{"internal_name":"WearCategory3","name":"Well-Worn","category":"Exterior","color":"","category_name":"Exterior"}],"is_currency":false,"market_marketable_restriction":0,"fraudwarnings":[]},{"appid":730,"contextid":"2","assetid":"13051109565","classid":"310776590","instanceid":"302028390","amount":1,"pos":12,"id":"13051109565","background_color":"","icon_url":"-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXH5ApeO4YmlhxYQknCRvCo04DEVlxkKgpot6-iFBRv7OPFcgJP6di_gY3FwaX2ZuuEzjxQscMgiOvDrI-tjFfkqUI6ZmnwI46dc1NoNQ7X-ATowvCv28F3OnPKLg","icon_url_large":"-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXH5ApeO4YmlhxYQknCRvCo04DEVlxkKgpot6-iFBRv7OPFcgJP6di_gY20m_bmNL6fwDlTvZUk0r7ArIqkila28hBkNzrzdofDdwY9ZlDY-wTolbjoh8Pqv4OJlyX3iSNzEQ","descriptions":[{"type":"html","value":"Exterior: Field-Tested"},{"type":"html","value":" "},{"type":"html","value":"Powerful and accurate, the AUG scoped assault rifle compensates for its long reload times with low spread and a high rate of fire. It has individual parts spray-painted tan, navy and dark green.\n\n<i>Rona Sabri still hasn\'t forgiven Sebastien for not selecting her to go after Turner<\/i>"},{"type":"html","value":" "},{"type":"html","value":"The Italy Collection","color":"9da1a9"},{"type":"html","value":" "}],"tradable":true,"actions":[{"link":"steam:\/\/rungame\/730\/76561202255233023\/+csgo_econ_action_preview%20S%owner_steamid%A%assetid%D4620909684717656608","name":"Inspect in Game..."}],"name":"AUG | Contractor","name_color":"D2D2D2","type":"Consumer Grade Rifle","market_name":"AUG | Contractor (Field-Tested)","market_hash_name":"AUG | Contractor (Field-Tested)","market_actions":[{"link":"steam:\/\/rungame\/730\/76561202255233023\/+csgo_econ_action_preview%20M%listingid%A%assetid%D4620909684717656608","name":"Inspect in Game..."}],"commodity":false,"market_tradable_restriction":7,"marketable":true,"tags":[{"internal_name":"CSGO_Type_Rifle","name":"Rifle","category":"Type","color":"","category_name":"Type"},{"internal_name":"weapon_aug","name":"AUG","category":"Weapon","color":"","category_name":"Weapon"},{"internal_name":"set_italy","name":"The Italy Collection","category":"ItemSet","color":"","category_name":"Collection"},{"internal_name":"normal","name":"Normal","category":"Quality","color":"","category_name":"Category"},{"internal_name":"Rarity_Common_Weapon","name":"Consumer Grade","category":"Rarity","color":"b0c3d9","category_name":"Quality"},{"internal_name":"WearCategory2","name":"Field-Tested","category":"Exterior","color":"","category_name":"Exterior"}],"is_currency":false,"market_marketable_restriction":0,"fraudwarnings":[]},{"appid":730,"contextid":"2","assetid":"13051109560","classid":"310776632","instanceid":"302028390","amount":1,"pos":13,"id":"13051109560","background_color":"","icon_url":"-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXH5ApeO4YmlhxYQknCRvCo04DEVlxkKgpopbmkOVUw7ODHTi1P7-O6nYeDg7n3YL6Bw2lQ7cZy27yTp9X00Qztrxc4Y2DwLYCRJw9tZQ3ZrAPrx-a-m9bi67t5CePh","icon_url_large":"-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXH5ApeO4YmlhxYQknCRvCo04DEVlxkKgpopbmkOVUw7ODHTi1P7-O6nYeDg8j4MqnWkyVSu8Ah3-vA8I_22QHm-Uo9amH6cNLBcg89aF7Ur1jtxbvm08TpupWY1zI97SZLY2Jj","descriptions":[{"type":"html","value":"Exterior: Minimal Wear"},{"type":"html","value":" "},{"type":"html","value":"The SCAR-20 is a semi-automatic sniper rifle that trades a high rate of fire and powerful long-distance damage for sluggish movement speed and big price tag. It has individual parts spray-painted tan, navy and dark green.\n\n<i>Rona Sabri still hasn\'t forgiven Sebastien for not selecting her to go after Turner<\/i>"},{"type":"html","value":" "},{"type":"html","value":"The Safehouse Collection","color":"9da1a9"},{"type":"html","value":" "}],"tradable":true,"actions":[{"link":"steam:\/\/rungame\/730\/76561202255233023\/+csgo_econ_action_preview%20S%owner_steamid%A%assetid%D7972744262948358002","name":"Inspect in Game..."}],"name":"SCAR-20 | Contractor","name_color":"D2D2D2","type":"Consumer Grade Sniper Rifle","market_name":"SCAR-20 | Contractor (Minimal Wear)","market_hash_name":"SCAR-20 | Contractor (Minimal Wear)","market_actions":[{"link":"steam:\/\/rungame\/730\/76561202255233023\/+csgo_econ_action_preview%20M%listingid%A%assetid%D7972744262948358002","name":"Inspect in Game..."}],"commodity":false,"market_tradable_restriction":7,"marketable":true,"tags":[{"internal_name":"CSGO_Type_SniperRifle","name":"Sniper Rifle","category":"Type","color":"","category_name":"Type"},{"internal_name":"weapon_scar20","name":"SCAR-20","category":"Weapon","color":"","category_name":"Weapon"},{"internal_name":"set_safehouse","name":"The Safehouse Collection","category":"ItemSet","color":"","category_name":"Collection"},{"internal_name":"normal","name":"Normal","category":"Quality","color":"","category_name":"Category"},{"internal_name":"Rarity_Common_Weapon","name":"Consumer Grade","category":"Rarity","color":"b0c3d9","category_name":"Quality"},{"internal_name":"WearCategory1","name":"Minimal Wear","category":"Exterior","color":"","category_name":"Exterior"}],"is_currency":false,"market_marketable_restriction":0,"fraudwarnings":[]}]',
		'tradeoffer_sent'  => Carbon\Carbon::now()->addMinutes($faker->randomNumber(3)),
		'tradeoffer_id'    => '123123123',
		'tradeoffer_state' => 3,
	];
});

$factory->define(App\TokenOrder::class, function (Faker\Generator $faker) {
	return [
		'state' => null,
	];
});

$factory->define(App\Confirmation::class, function (Faker\Generator $faker) {
	return [
		'public_id'    => substr(md5(microtime()), 0, 15),
		'start_period' => Carbon\Carbon::now(),
		'end_period'   => Carbon\Carbon::now()->addDays($faker->numberBetween(7, 60)),
		'order_id'     => function () {
			return factory(App\Order::class)->create()->id;
		},
		'user_id'      => \App\User::all()->random()->id,
	];
});

$factory->define(App\Token::class, function (Faker\Generator $faker) {
	return [
		'token'          => substr(md5(microtime()), 0, 30),
		'duration'       => $faker->randomNumber(2),
		'expiration'     => $faker->randomNumber(2),
		'note'           => $faker->text(200),
		'token_order_id' => function () {
			return factory(App\TokenOrder::class)->create()->id;
		},
		'user_id'        => \App\User::all()->random(),
	];
});

$factory->define(App\Server::class, function (Faker\Generator $faker) {
	return [
		'name'     => $faker->name(),
		'ip'       => $faker->ipv4,
		'port'     => $faker->numberBetween(0, 27300),
		'password' => $faker->password(5, 10),
	];
});