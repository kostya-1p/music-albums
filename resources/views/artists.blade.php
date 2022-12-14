<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Artists
        </h2>
    </x-slot>

    <form method="GET" action="{{ route('artists.indexFiltered') }}">
        <div class="center">
            <select name="artist">
                <option>All</option>
                @foreach($artistsForFilter as $artist)
                    <option>{{$artist->name}}</option>
                @endforeach
            </select>
            <button type="submit" id="submit_filter" class="ml-3 btn btn-primary album_button" style="border-radius: 0">
                Filter
            </button>
        </div>
    </form>

    @foreach($artists as $artist)
        <div class="album-container">
            <img src="{{ url('images/artists/'.$artist->img) }}" width="150px" height="150px"
                 class="album-image backup_picture_artist" alt="Image not found"/>
            <p class="headingMd">Name: {{$artist->name}}</p>
        </div>

        @auth
            <a href="{{route('artists.edit', ['id'=>$artist->id])}}">
                <button class="album_button edit_btn_indent">
                    EDIT
                </button>
            </a>

            <form method="post" class="inline" action="{{route('artists.destroy', ['id'=>$artist->id])}}">
                @csrf
                @method('DELETE')
                <input type="hidden" name="id" value={{$artist->id}}>
                <input type="hidden" name="count" value="{{count($artists)}}">
                <input type="hidden" name="currentPage" value="{{$artists->currentPage()}}">
                <button class="album_button">DELETE</button>
            </form>
        @endauth
    @endforeach

    <br>

    @auth
        <a href="{{route('artists.create')}}">
            <button class="ml-60 mt-10 mb-10 button">
                Add Artist
            </button>
        </a>
    @endauth

    <div class="d-flex justify-content-center mb-10 ml-60 mr-60 mt-5">
        {{ $artists->withQueryString()->links() }}
    </div>
</x-app-layout>
