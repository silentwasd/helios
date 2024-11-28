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
            'name'        => 'required|string|max:255|unique:ssh_keys,name',
            'private_key' => 'required|string'
        ]);

        SshKey::create($data);
    }

    public function update(Request $request, SshKey $sshKey)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255', Rule::unique('ssh_keys')->ignore($sshKey->id)],
            'private_key' => 'required|string'
        ]);

        $sshKey->update($data);
    }

    public function destroy(SshKey $sshKey)
    {
        $sshKey->delete();
    }
}
