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

        @auth
            <button class="album_button edit_btn_indent">
                <a href="{{route('editAlbumPage', ['id'=>$album->id])}}">EDIT</a>
            </button>

            <button class="album_button">
                <a href="{{route('editAlbumPage', ['id'=>$album->id])}}">DELETE</a>
            </button>
        @endauth
    @endforeach

    <br>
    @auth
        <x-button class="ml-60 mt-10">
            <a href="{{route('createAlbumPage')}}">Add Album</a>
        </x-button>
    @endauth

    <div class="d-flex justify-content-center mb-10 ml-60 mr-60 mt-5">
        {{ $albums->links() }}
    </div>
</x-app-layout>
