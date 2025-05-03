<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Livewire\Component;

class Show extends Component
{
    public Project $project;
    public $files = [];

    public $selectedFile;

    public $content;

    public function showContent(string $file)
    {
        $path = $this->project->path . '/' . $file;

        if (is_dir($path)) {
            return;
        }

        if (!is_file($path)) {
            return;
        }

        $this->selectedFile = $file;

        $content = file_get_contents($path);

        $this->content = $content;
    }

    public function closeFile()
    {
        $this->selectedFile = null;
        $this->content = null;
    }

    public function mount()
    {
        $files = scandir($this->project->path);
        $files = array_diff($files, ['.', '..']);

        $this->files = $files;
    }

    public function render()
    {
        return view('livewire.projects.show');
    }
}
