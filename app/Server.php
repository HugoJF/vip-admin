<?php

namespace App;

use App\Classes\Daemon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class Server extends Model
{
	protected $dates = ['updated_at', 'created_at', 'synced_at'];

	protected $fillable = [
		'name',
		'ip',
		'port',
		'password',
		'ftp_host',
		'ftp_user',
		'ftp_password',
		'ftp_root',
	];

	public function sync()
	{
		\Log::info('Syncing server');

		try {
			Daemon::consoleLog('Generating_new_admins_simple');

			$confirmations = Confirmation::valid()->with('baseOrder.user', 'baseOrder')->get();

			$steamid = [];

			foreach ($confirmations as $confirmation) {
				$steam2 = Daemon::getSteam2ID($confirmation->baseOrder->user->steamid);
				$steamid[] = [
					'id'           => $steam2,
					'confirmation' => $confirmation,
				];

				$confirmation->baseOrder->server_uploaded = true;
				$saved = $confirmation->baseOrder->save();
				if (!$saved) {
					flash()->error('Error saving confirmation details.');

					return redirect()->route('home');
				}
			}

			Daemon::consoleLog('Rendering view');
			$view = View::make('admins_simple', [
				'list' => $steamid,
				'html' => false,
			]);

			if (config('app.update_server') == 'true') {
				Daemon::consoleLog('FTP saving admins_simple.');
				Storage::createFtpDriver([
					'host'     => $this->ftp_host,
					'username' => $this->ftp_user,
					'password' => $this->ftp_password,
					'root'     => $this->ftp_root,
				])->put('admins_simple.ini', $view);

				Daemon::consoleLog('Sending RCON update');
				Daemon::updateSourceMod($this);
			}
		} catch (\Exception $e) {
			flash()->error('Error syncing server: ' . $e->getMessage());

			\Log::info('Error syncing');

			return redirect()->route('home');
		}

		Daemon::consoleLog('Finished');
		\Log::info('Done syncing');

		return true;
	}
}
