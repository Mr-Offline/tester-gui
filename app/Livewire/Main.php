<?php

namespace App\Livewire;

use App\Models\Project;
use Livewire\Component;
use Native\Laravel\Dialog;

class Main extends Component
{
    public function openProject()
    {
        $path = Dialog::new()
            ->folders()
            ->open();

        if ($path) {
            $project = Project::firstOrCreate([
                'path' => $path,
            ], [
                'title' => basename($path),
                'last_opened_at' => now(),
            ]);

            return to_route('projects.show', [$project]);
        }

        return false;
    }

    public function render()
    {
        $projects = Project::limit(10)->get();

        return view('livewire.main')->with([
            'projects' => $projects
        ]);
    }
}
