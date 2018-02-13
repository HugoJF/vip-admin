<?php

namespace App\Http\Controllers;

use App\Server;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilderTrait;

class ServersCOntroller extends Controller
{
	use FormBuilderTrait;

	public function index()
	{
		$servers = Server::all();

		return view('servers.index', [
			'servers' => $servers,
		]);
	}

	public function delete(Server $server) {
		$deleted = $server->delete();

		if($deleted) {
			flash()->success('Server deleted!');
		} else {
			flash()->error('Could not delete server!');
		}

		return redirect()->route('servers.index');
	}

	public function create()
	{
		$form = $this->form('App\Forms\ServerForm', [
			'method' => 'POST',
			'route'  => 'servers.store',
		]);

		return view('servers.create', [
			'form' => $form,
		]);
	}

	public function store(Request $request)
	{
		$server = Server::make();

		$server->name = $request->input('server-name');
		$server->ip = $request->input('server-ip');
		$server->port = $request->input('server-port');
		$server->password = $request->input('server-password');

		$saved = $server->save();

		if($saved) {
			flash()->success('Server added to database successfully!');
		} else {
			flash()->error('Could not save server to database!');
		}

		return redirect()->route('servers.index');
	}
}
