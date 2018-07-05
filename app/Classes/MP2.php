<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 7/5/2018
 * Time: 2:20 PM
 */

namespace App\Classes;


use Livepixel\MercadoPago\Facades\MP;

class MP2
{
	public static $saving = true;
	public static $mocking = false;
	public static $responses = [];


	public static function fileMock($request, $fileName)
	{
		$path = app_path('Mock/mp/') . $fileName;
		$file = fopen($path, 'r');

		$content = fread($file, filesize($path));

		fclose($file);

		static::mock($request, json_decode($content));
	}

	public static function saveResponse($name, $response)
	{
		if (static::$saving === true) {
			$path = app_path('Mock/' . str_replace('/', '-', $name));

			$file = fopen($path, 'w');
			fwrite($file, json_encode($response));
			fclose($file);
		}

		return $response;
	}

	public static function create_preference($preference)
	{
		if (static::$mocking === true) {
			return static::mock('create_preference');
		}

		return static::saveResponse('create_preference', \MP::create_preference($preference));
	}

	public static function get_payment($paymentId)
	{
		if (static::$mocking === true) {
			return static::mock('get_payment');
		}

		return static::saveResponse('get_payment', \MP::get_payment($paymentId));
	}

	public static function get($type, $url)
	{
		if (static::$mocking === true) {
			return static::mock('get' . $type);
		}

		return static::saveResponse('get' . '/' . $type . '/' . $url, \MP::get('/' . $type . '/' . $url));
	}

	public static function mock($name)
	{
		if (static::$mocking === true) {
			return static::$responses[ $name ];
		}
	}
}