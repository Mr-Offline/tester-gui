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
        if (is_dir($file)) {
            return;
        }

        if (!is_file($file)) {
            return;
        }

        $this->selectedFile = $file;

        $content = file_get_contents($file);

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

        if (!array_search('phpunit.xml', $files)) {
            return to_route('home');
        }

        $xml = simplexml_load_file(implode(DIRECTORY_SEPARATOR, [$this->project->path, 'phpunit.xml']));

        $testPaths = array_map(fn($item) => (string)$item, $xml->xpath('//testsuites/testsuite/directory'));

        $this->project->update([
            'test_paths' => $testPaths,
        ]);

        // Get all files in the project test directory recursively
        $files = [];
        foreach ($testPaths as $testPath) {
            $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(implode(DIRECTORY_SEPARATOR, [$this->project->path, $testPath])));
            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    $files[] = $file->getPathname();
                }
            }
        }

        $this->files = $files;
    }

    public function render()
    {
        return view('livewire.projects.show');
    }
}
