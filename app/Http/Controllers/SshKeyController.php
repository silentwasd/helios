<?php

namespace App\Http\Controllers;

use App\Http\Resources\SshKeyResource;
use App\Models\SshKey;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SshKeyController extends Controller
{
    public function index()
    {
        return SshKeyResource::collection(SshKey::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'private_key' => 'required|string'
        ]);

        SshKey::create([
            ...$data,
            'user_id' => auth()->id()
        ]);
    }

    public function update(Request $request, SshKey $sshKey)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'private_key' => 'required|string'
        ]);

        $sshKey->update($data);
    }

    public function destroy(SshKey $sshKey)
    {
        $sshKey->delete();
    }
}
