<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use App\Utils\Helper;
use Illuminate\Support\Facades\Process;
use Livewire\Component;

class Show extends Component
{
    public Project $project;
    public $files = [];

    public $selectedFile;

    public $content;

    public function runFile(string $file)
    {
        if (is_dir($file)) {
            return;
        }

        if (!is_file($file)) {
            return;
        }

        $testFramework = Helper::checkTestFramework($this->project->path);

        switch ($testFramework) {
            case 'PestPHP':
                $command = 'pest';
                break;
            case 'PHPUnit':
                $command = 'phpunit';
                break;
            default:
                return;
        }

        $result = Process::run([$command]);
        dd($result->output());

        $this->selectedFile = $file;

        $content = file_get_contents($file);
        $tokens = token_get_all($content);
        $functions = [];
        $captureNextString = false;
        foreach ($tokens as $token) {
            if (is_array($token) && $token[0] === T_FUNCTION) {
                $captureNextString = true;
            } elseif ($captureNextString && is_array($token) && $token[0] === T_STRING) {
                $functions[] = $token[1];
                $captureNextString = false;
            }
        }

        dd($functions);

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
                    $content = file_get_contents($file);
                    $tokens = token_get_all($content);
                    $functions = [];
                    $captureNextString = false;
                    foreach ($tokens as $token) {
                        if (is_array($token) && $token[0] === T_FUNCTION) {
                            $captureNextString = true;
                        } elseif ($captureNextString && is_array($token) && $token[0] === T_STRING) {
                            $functions[] = $token[1];
                            $captureNextString = false;
                        }
                    }
                    $files[realpath($file->getPathname())] = $functions;
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
