<?php

namespace App\Http\Controllers;

use App\Http\Resources\ServerResource;
use App\Models\Server;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    public function index()
    {
        return ServerResource::collection(Server::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'host'       => 'required|string|max:255',
            'port'       => 'required|integer|min:0',
            'username'   => 'required|string|max:255',
            'ssh_key_id' => 'required|exists:ssh_keys,id'
        ]);

        $server = new Server([
            ...$data,
            'user_id' => auth()->id()
        ]);

        if (($result = $server->check()) && $result !== true)
            abort(404, "Connection failed:\n" . $result);

        $server->save();

        return new ServerResource($server);
    }

    public function show(Server $server)
    {
        return new ServerResource($server);
    }

    public function update(Request $request, Server $server)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'host'       => 'required|string|max:255',
            'port'       => 'required|integer|min:0',
            'username'   => 'required|string|max:255',
            'ssh_key_id' => 'required|exists:ssh_keys,id'
        ]);

        $server->fill($data);

        if (($result = $server->check()) && $result !== true)
            abort(404, "Connection failed:\n" . $result);

        $server->save();
    }

    public function destroy(Server $server)
    {
        $server->delete();
    }
}
