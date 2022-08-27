<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Music Albums
        </h2>
    </x-slot>

    @foreach($albums as $album)
        <div class="album-container">
            <img src="{{$album->img}}" width="150px" height="150px" class="album-image"/>
            <p class="headingMd">Name: {{$album->name}}</p>
            <p class="headingMd">Artist: {{$album->artist}}</p>
            <p class="headingMd">Description:</p>
            <p>{{$album->description}}</p>
        </div>
    @endforeach

    @auth
        <x-button class="ml-60">
            <a href="{{route('createAlbumPage')}}">Add Album</a>
        </x-button>
    @endauth
</x-app-layout>
