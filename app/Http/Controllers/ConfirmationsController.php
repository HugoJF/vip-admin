<?php

namespace App\Http\Controllers;

use App\Confirmation;
use App\Events\ConfirmationGenerated;
use App\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ConfirmationsController extends Controller
{
	public function generate(Order $order)
	{
		// Check if Order with given public ID exists
		if (!$order) {
			flash()->error('Could not find order');

			return redirect()->route('home');
		}

		// Retrieve confirmation count for given order
		$confirmationCount = $order->confirmation()->count();

		// Check if confirmation exists first
		if ($confirmationCount != 0) {
			flash()->error('We already have a confirmation for this order is our database, please contact support!');

			return redirect()->route('home');
		}

		/*
		// Check if user already has a valid confirmation
		if (Auth::user()->confirmations()->valid()->get()->first()) {
			flash()->error('You already have a valid confirmation, please wait for it to expire before generating another one!');

			return redirect()->back();
		}*/

		if ($order->isSteamOffer()) {
			// Check if steam order is accepted
			$steamOrder = $order->orderable()->first();
			if (!$steamOrder || !$steamOrder->accepted()) {
				flash()->error('You must accept the trade offer before creating a confirmation!');

				return redirect()->route('home');
			}
		} else {
			$tokenOrder = $order->orderable()->first();

			if (!$tokenOrder || !$tokenOrder->token()->exists()) {
				flash()->error('Your order must have a valid token associated with to generate a confirmation!');

				return redirect()->route('home');
			}
		}

		// Get last confirmation generated for the User
		$latestConfirmation = Auth::user()->confirmations()->notExpired()->orderBy('end_period', 'asc')->first();

		// The base period for the Confirmation should be now or the last valid confirmation
		if ($latestConfirmation) {
			$basePeriod = $latestConfirmation->end_period;
		} else {
			$basePeriod = Carbon::now();
		}

		// Start creating Confirmation entry
		$confirmation = Confirmation::make();

		$confirmation->public_id = substr(md5(microtime()), 0, \Setting::get('public-id-size'));
		$confirmation->baseOrder()->associate($order);
		$confirmation->user()->associate(Auth::user());
		$confirmation->start_period = $basePeriod;
		$confirmation->end_period = $basePeriod->addDays($order->duration);

		$confirmed = $confirmation->save();

		// Check if we confirmation was set to database and trigger event
		if ($confirmed) {
			event(new ConfirmationGenerated($confirmation));
		} else {
			flash()->error('Error saving confirmation to database!');
		}

		// Redirect to updated order
		if ($order->isSteamOffer()) {
			return redirect()->route('steam-order.show', $order->public_id);
		} else {
			return redirect()->route('token-order.show', $order->public_id);
		}
	}

	public function view()
	{
		$user = Auth::user();

		if ($user->isAdmin()) {
			$confirmations = Confirmation::with('user', 'baseOrder')->get();
		} else {
			$confirmations = Auth::user()->confirmations()->with('user', 'baseOrder')->get();
		}

		return view('confirmations.index', [
			'confirmations' => $confirmations,
			'isAdmin'       => $user->isAdmin(),
		]);
	}

	public function syncServer()
	{
		$result = Confirmation::syncServer();

		if ($result === true) {
			flash()->success('Server synced!');

			return redirect()->route('home');
		} else {
			return $result;
		}
	}

	public function generateAdminsSimple()
	{
		// Caches Carbon::now();
		$now = Carbon::now();

		// Get valid confirmations
		$confirmations = Confirmation::valid()->with('baseOrder.user')->get();

		// Array of SteamID2 to Confirmation
		$steamid = [];

		// Parses each valid confirmation and adds to array
		foreach ($confirmations as $confirmation) {
			$steam2 = DaemonController::getSteam2ID($confirmation->baseOrder->user->steamid);

			// If Steam2 could not be generated
			if ($steam2 === false) {
				return redirect()->route('home');
			}

			$steamid[] = [
				'id'           => $steam2,
				'confirmation' => $confirmation,
			];
		}

		// Render admin_simple.ini
		return view('admins_simple', [
			'list' => $steamid,
			'html' => true,
		]);
	}

	public function viewAdminsSimple()
	{
		// Caches Carbon::now();
		$now = Carbon::now();

		// Get valid confirmations
		$confirmations = Confirmation::valid()->with('baseOrder.user')->get();

		// Array of SteamID2 to Confirmation
		$steamid = [];

		// Parses each valid confirmation and adds to array
		foreach ($confirmations as $confirmation) {
			$steam2 = DaemonController::getSteam2ID($confirmation->baseOrder->user->steamid);

			// If Steam2 could not be generated
			if ($steam2 === false) {
				return redirect()->route('home');
			}

			$steamid[] = [
				'id'           => $steam2,
				'confirmation' => $confirmation,
			];
		}

		// Render admin_simple.ini
		return view('admins_simple_preview', [
			'list' => $steamid,
			'html' => true,
		]);
	}
}
