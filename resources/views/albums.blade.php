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
            <p class="headingMd mb-2">Description:</p>
            <p class="description">{!! nl2br(e($album->description)) !!}</p>
        </div>

        @auth
            <button class="album_button edit_btn_indent">
                <a href="{{route('albums.edit', ['id'=>$album->id])}}">EDIT</a>
            </button>

            <form method="post" class="inline" action="{{route('albums.destroy', ['id'=>$album->id])}}">
                @csrf
                <input type="hidden" name="id" value={{$album->id}}>
                <button class="album_button">DELETE</button>
            </form>
        @endauth
    @endforeach

    <br>
    @auth
        <x-button class="ml-60 mt-10 mb-10">
            <a href="{{route('albums.create')}}">Add Album</a>
        </x-button>
    @endauth

    <div class="d-flex justify-content-center mb-10 ml-60 mr-60 mt-5">
        {{ $albums->links() }}
    </div>
</x-app-layout>
