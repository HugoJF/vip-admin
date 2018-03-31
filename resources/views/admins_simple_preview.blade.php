@extends('layouts.app')

@section('content')
@if($html)
<h1>admins_simple.ini Preview</h1>
<pre>
@endif
//
// READ THIS CAREFULLY! SEE BOTTOM FOR EXAMPLES
//
// For each admin, you need three settings:
//  "identity"		"permissions"		"password"
//
// For the Identity, you can use a SteamID or Name.  To use an IP address, prepend a ! character.
// For the Permissions, you can use a flag string and an optional password.
//
// PERMISSIONS:
//  Flag definitions are in "admin_levels.cfg"
//  You can combine flags into a string like this:
//  "abcdefgh"
//
//  If you want to specify a group instead of a flag, use an @ symbol.  Example:
//  "@Full Admins"
//
//	You can also specify immunity values.  Two examples:
//	"83:abcdefgh"			//Immunity is 83, flags are abcdefgh
//	"6:@Full Admins"		//Immunity is 6, group is "Full Admins"
//
//	Immunity values can be any number.  An admin cannot target an admin with
//	a higher access value (see sm_immunity_mode to tweak the rules).  Default
//  immunity value is 0 (no immunity).
//
// PASSWORDS:
//  Passwords are generally not needed unless you have name-based authentication.
//  In this case, admins must type this in their console:
//
//   setinfo "KEY" "PASSWORD"
//
//  Where KEY is the "PassInfoVar" setting in your core.cfg file, and "PASSWORD"
//  is their password.  With name based authentication, this must be done before
//  changing names or connecting.  Otherwise, SourceMod will automatically detect
//  the password being set.
//
////////////////////////////////
// Examples: (do not put // in front of real lines, as // means 'comment')
//
//   "STEAM_0:1:16"		"bce"				//generic, kick, unban for this steam ID, no immunity
//   "!127.0.0.1"		"99:z"				//all permissions for this ip, immunity value is 99
//   "BAILOPAN"			"abc"	"Gab3n"		//name BAILOPAN, password "Gab3n": gets reservation, generic, kick
//
////////////////////////////////

"STEAM_1:1:107619130"		"99:z" // Teaguenho			| Forevis
"STEAM_0:0:33074301"    "99:z" // Teaguenho     | Forevis
"STEAM_1:1:36509127"		"99:z" // Eu				| Forevis
"STEAM_0:0:53359806"		"50:a" // Lari				| Forevis

"STEAM_1:1:64297652"		"50:a" // Vitorlk74			| Sub
"STEAM_0:0:42974714"		"50:a" // Ximus				| Forevis
"STEAM_0:1:197194388"		"50:a" // Caio				| Sub

// PROS
"STEAM_1:0:17418242"		"50:a" // tatazin
"STEAM_1:0:24572716"		"50:a" // delboNi
"STEAM_1:0:89523495"		"50:a" // land1n

// VIPS MERCADO PAGO
"STEAM_0:0:66095696"		"50:a" // Analise de Variancia | Chroma Case 3 Key + 2 chaves
"STEAM_0:1:68445875"    "50:a" // zvook

// VIP ADMIN GENERATED
@foreach($list as $item)
"{{ $item['id'] }}"		"50:a" // #{{ $item['confirmation']->public_id }} - {{ $item['confirmation']->baseOrder->user->username }}
@endforeach

@if($html)
</pre>
@endif

@endsection
