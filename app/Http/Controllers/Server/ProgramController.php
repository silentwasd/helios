<?php

namespace App\Http\Controllers\Server;

use App\Enums\ProgramStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProgramResource;
use App\Jobs\InstallProgramJob;
use App\Jobs\UninstallProgramJob;
use App\Models\Program;
use App\Models\Server;
use App\Repositories\ProgramRepository;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index(ProgramRepository $programRepo, Server $server)
    {
        return ProgramResource::collection(
            $programRepo->all()->map(function (\App\Contracts\Program $program) use ($server) {
                $model = $server->programs()->where('name', $program->name())->first();
                return [
                    'id'          => $model?->id ?? 0,
                    'name'        => $program->name(),
                    'label'       => $program->label(),
                    'description' => $program->description(),
                    'status'      => $model?->status ?? ProgramStatus::NotInstalled
                ];
            })
        );
    }

    public function store(ProgramRepository $programRepo, Request $request, Server $server)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        if (!($program = $programRepo->find($data['name'])))
            abort(404, 'Program not found');

        $model = $server->programs()->updateOrCreate(
            ['name' => $program->name()],
            ['status' => ProgramStatus::Installing]
        );

        if (!$server->os()->checkProgram($program))
            InstallProgramJob::dispatch($model);
        else
            $model->update(['status' => ProgramStatus::Installed]);

        return new ProgramResource([
            ...$model->fresh()->toArray(),
            'name'        => $program->name(),
            'label'       => $program->label(),
            'description' => $program->description()
        ]);
    }

    public function destroy(Server $server, Program $program)
    {
        if (!$program->data())
            abort(404, 'Program not found');

        $program->update(['status' => ProgramStatus::Uninstalling]);

        if ($server->os()->checkProgram($program->data()))
            UninstallProgramJob::dispatch($program);
        else
            $program->update(['status' => ProgramStatus::Uninstalled]);

        return new ProgramResource([
            ...$program->fresh()->toArray(),
            'name'        => $program->data()->name(),
            'label'       => $program->data()->label(),
            'description' => $program->data()->description()
        ]);
    }
}
