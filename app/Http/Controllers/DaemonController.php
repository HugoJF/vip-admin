<?php

namespace App\Http\Controllers;

use App\OPSkinsCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Ixudra\Curl\Facades\Curl;

class DaemonController extends Controller
{

	public function loginPost(Request $request)
	{
		$code = $request->input('code');

		static::curl('login', [
			'code' => $code,
		]);

		return redirect()->route('home');
	}

	public function login()
	{
		return view('daemon_login');
	}

	public function logs()
	{
		$logs = static::curl('logs');

		if ($logs === false) {
			return redirect()->back();
		} else {
			return view('logs', [
				'content' => $logs,
			]);
		}
	}

	public function stderr()
	{
		$logs = static::curl('stderr');

		if ($logs === false) {
			return redirect()->back();
		} else {
			return view('logs', [
				'content' => $logs,
			]);
		}
	}

	public function stdout()
	{
		$logs = static::curl('stdout');

		if ($logs === false) {
			return redirect()->back();
		} else {
			return view('logs', [
				'content' => $logs,
			]);
		}
	}

	public function kill()
	{
		$response = static::curl('kill');

		if ($response === false) {
			return redirect()->back();
		} else {
			return view('logs', [
				'content' => $response,
			]);
		}
	}

	public static function curl($path, $data = null, $post = false)
	{
		$result = Curl::to(config('app.daemon_address') . '/' . $path);

		if ($data) {
			$result = $result->withData($data);
		}

		$result = $result->asJson();

		$response = null;

		if ($post) {
			$response = $result->post();
		} else {
			$response = $result->get();
		}

		if (!isset($response->error) || !isset($response->response) || $response->error == true || !isset($response->response)) {
			if (isset($response->message)) {
				flash()->error('Could not contact Steam servers: ' . $response->message);
			} else {
				flash()->error('Could not contact Steam servers: Unknown error message');
			}

			return false;
		}

		return $response->response;
	}

	public static function status()
	{
		$result = static::curl('status');

		return $result;
	}

	public static function isOnline()
	{
		$status = self::status();

		if (isset($status->online)) {
			return $status->online === true;
		} else {
			return false;
		}
	}

	public static function consoleLog($message)
	{
		$result = static::curl('consoleLog', [
			'message' => $message,
		]);

		return $result;
	}

	public static function isLoggedIn()
	{
		$status = self::status();

		if (isset($status->logged)) {
			return $status->logged === true;
		} else {
			return false;
		}
	}

	public static function updateSourceMod()
	{
		$result = static::curl('csgoServerUpdate');

		return $result;
	}

	public static function getInventory($steamid)
	{
		$inventory = static::curl('inventory', [
			'steamid' => $steamid,
		]);

		return $inventory;
	}

	public static function getInventoryFromAuthedUser()
	{
		$user = Auth::user();
		if ($user === false) {
			return false;
		}

		if (isset($user->tradelink)) {
			$inventory = self::getInventory($user->tradeid());
		} else {
			$inventory = self::getInventory($user->steamid);
		}

		return $inventory;
	}

	public static function cancelTradeOffer($tradeid)
	{
		$result = static::curl('cancelTradeOffer', [
			'tradeid' => $tradeid,
		]);

		return $result;
	}

	public static function sendTradeOffer($tradelink, $message, $encoded_items)
	{
		$data = [
			'tradelink'     => $tradelink,
			'encoded_items' => $encoded_items,
			'message'       => $message,
		];

		$result = static::curl('sendTradeOffer', [
			'items' => json_encode($data),
		], true);

		return $result;
	}

	public static function getTradeOffer($tradeofferid)
	{
		$result = static::curl('getTradeOffer', [
			'offerid' => $tradeofferid,
		]);

		return $result;
	}

	public static function checkDaemon()
	{
		$status = static::curl('status');
		if (isset($status['online'])) {
			return $status['online'];
		} else {
			return false;
		}
	}

	public static function calculateTotalPrice($item_list)
	{
		$totalPrice = 0;

		foreach ($item_list as $item) {
			$cache = OPSkinsCache::where('name', $item->market_name)->get()->first();

			if (!$cache) {
				continue;
			}

			$totalPrice += $cache->price;
		}

		return $totalPrice;
	}

	public static function calculateOfferDuration($price)
	{
		return floor($price / \Setting::get('cost-per-day'));
	}

	public static function getSteam2ID($steamid)
	{
		$result = static::curl('steam2', [
			'steamid' => $steamid,
		]);

		return $result;
	}

	public static function fillItemArray($item_list, $inventory = null)
	{
		if ($inventory === null) {
			$inventory = self::getInventoryFromAuthedUser();
		}

		if ($inventory === false) {
			return false;
		}

		$full_item_list = [];

		foreach ($item_list as $item) {
			foreach ($inventory as $inv) {
				if ($inv->assetid == $item->assetid) {
					$full_item_list[] = $inv;
				}
			}
		}

		return $full_item_list;
	}
}
