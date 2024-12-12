<?php

namespace App\Http\Controllers\Ai;

use App\Http\Controllers\Controller;
use App\Models\Server;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    public function executeCommand(Request $request, Server $server)
    {
        $data = $request->validate([
            'command' => 'required|string|max:2048'
        ]);

        $process = $server->executeSsh([$data['command']]);

        if (!$process->isSuccessful())
            return response()->json([
                'error'  => $process->getErrorOutput(),
                'status' => $process->getExitCode()
            ]);

        return response()->json([
            'success' => $process->getOutput(),
            'status'  => $process->getExitCode()
        ]);
    }
}
