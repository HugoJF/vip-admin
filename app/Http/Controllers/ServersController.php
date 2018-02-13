<?php

namespace App\Http\Controllers;

use App\Server;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilderTrait;

class ServersController extends Controller
{
    use FormBuilderTrait;

    public function index()
    {
        $servers = Server::all();

        return view('servers.index', [
            'servers' => $servers,
        ]);
    }

    public function delete(Server $server)
    {
        $deleted = $server->delete();

        if ($deleted) {
            flash()->success('Server deleted!');
        } else {
            flash()->error('Could not delete server!');
        }

        return redirect()->route('servers.index');
    }

    public function edit(Server $server)
    {
        $form = $this->form('App\Forms\ServerForm', [
            'method' => 'PATCH',
            'route'  => ['servers.update', $server],
            'model'  => $server,
        ]);

        return view('servers.form', [
            'form' => $form,
        ]);
    }

    public function update(Request $request, Server $server)
    {
        $server->fill($request->all());

        $saved = $server->save();

        if ($saved) {
            flash()->success('Server edited to database successfully!');
        } else {
            flash()->error('Could not edit server!');
        }

        return redirect()->route('servers.index');
    }

    public function create()
    {
        $form = $this->form('App\Forms\ServerForm', [
            'method' => 'POST',
            'route'  => 'servers.store',
        ]);

        return view('servers.form', [
            'form' => $form,
        ]);
    }

    public function store(Request $request)
    {
        $server = Server::make();

        $server->fill($request->all());

        $saved = $server->save();

        if ($saved) {
            flash()->success('Server added to database successfully!');
        } else {
            flash()->error('Could not save server to database!');
        }

        return redirect()->route('servers.index');
    }
}
