<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">

        </h2>
    </x-slot>

    @foreach($albums as $album)
        {{$album->name}}
        <img src="{{$album->img}}" alt="{{$album->name}}"/>
    @endforeach
</x-app-layout>
