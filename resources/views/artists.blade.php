<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Artists
        </h2>
    </x-slot>

    @foreach($artists as $artist)
        <div class="album-container">
            <img src="{{$artist->img}}" width="150px" height="150px" class="album-image"/>
            <p class="headingMd">Name: {{$artist->name}}</p>
        </div>
    @endforeach

    <br>

    <div class="d-flex justify-content-center mb-10 ml-60 mr-60 mt-5">
        {{ $artists->links() }}
    </div>
</x-app-layout>
