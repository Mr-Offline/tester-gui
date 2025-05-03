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
        return view('livewire.main');
    }
}
