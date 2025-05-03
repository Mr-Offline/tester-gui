<div>
    <div class="flex flex-col gap-0.5 inset-y-0 overflow-y-auto fixed w-64 max-w-64 bg-gray-800">
        @foreach($files as $file)
            <div class="text-sm p-0.5 cursor-pointer" wire:click="showContent('{{$file}}')" wire:key="{{$file}}">
                {{$file}}
            </div>
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
            <div class="text-sm p-0.5">
                <pre class="whitespace-pre-wrap">
                    <code>
                        {{$content}}
                    </code>
                </pre>
            </div>
        @endif
    </div>
</div>
