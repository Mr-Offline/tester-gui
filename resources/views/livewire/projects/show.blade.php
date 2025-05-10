<div>
    <div class="flex flex-col gap-1 inset-y-0 overflow-y-auto fixed w-64 max-w-64 bg-gray-800">
        @foreach($files as $path => $file)
            <div class="flex items-center gap-1 p-0.5 cursor-pointer" wire:click="runFile('{{str_replace('\\', '\\\\', $path)}}')" wire:key="{{$path}}">
                <span>▶️</span>
                {{str_replace($project->path, '', $path)}}
            </div>
            @foreach($file as $function)
            <div class="flex items-center gap-1 text-sm ps-5 cursor-pointer" wire:click="runTest('{{str_replace('\\', '\\\\', $path)}}', '{{$function}}')" wire:key="{{$path}}-{{$function}}">
                <span>▶️</span>
                {{$function}}
            </div>
            @endforeach
        @endforeach
    </div>
    <div class="ml-64">
        @if($selectedFile)
            <div class="flex flex-col gap-2">
                <div class="flex items-center gap-2 p-1 bg-gray-700 w-min">
                    <button wire:click="closeFile" class="text-sm text-gray-300 cursor-pointer hover:brightness-125">x</button>
                    <span class="text-sm">{{$selectedFile}}</span>
                </div>
            </div>
        @endif
        @if($content)
            <div class="text-sm p-0.5 whitespace-pre-line">
                {{$content}}
            </div>
        @endif
    </div>
</div>
