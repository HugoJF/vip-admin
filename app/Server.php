<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
		'ftp_root'
	];
}
