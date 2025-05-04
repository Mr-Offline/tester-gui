<div class="min-h-screen min-w-screen grid place-items-center">
    <button
        wire:click="openProject"
        class="bg-blue-500 border border-blue-600 outline-none px-4 py-2 text-white rounded-md hover:brightness-125 cursor-pointer transition">
        Open Project
    </button>
    @foreach($projects as $project)
        <a href="{{route('projects.show', [$project->id])}}">{{$project->title}}</a>
    @endforeach
</div>
