<x-app-layout>
    @php
        $isEditPage = isset($album)
    @endphp

    @push('scripts')
        @vite(['resources/js/albums.js'])
    @endpush

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if($isEditPage)
                Edit Album
            @else
                Add Album
            @endif
        </h2>
    </x-slot>


    <form method="POST" action="{{($isEditPage) ? route('albums.update', ['id'=>$album->id]) : route('albums.store')}}"
          autocomplete="off" enctype="multipart/form-data">
        @csrf
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-6 bg-white border-b border-gray-200 m-10">
            @if($isEditPage)
                @method('PUT')
                <input type="hidden" name="id" id="albumId" value={{$album->id}}>
            @endif

            <x-label for="name" value="Name"/>
            @error('name')
            <div style="color: red">{{ $message }}</div>
            @enderror
            <x-input id="name" class="block mt-1 mb-10 w-full" type="text" name="name"
                     value="{{($isEditPage) ? $album->name : ''}}" required/>

            <x-label for="artist" value="Artist"/>
            @error('artist')
            <div style="color: red">{{ $message }}</div>
            @enderror
            <x-input list="artistList" id="artist" name="artist" class="block mt-1 mb-10 w-full" type="text"
                     value="{{($isEditPage) ? $artist->name : ''}}" required/>

            <datalist id="artistList"></datalist>

            <x-label for="image" value="Image"/>
            @error('img')
            <div style="color: red">{{ $message }}</div>
            @enderror
            <input type="file" id="image" name="img" class="block mt-1 mb-10 w-full"  accept="image/*"
                     required/>

            <img id="albumImagePreview" src="{{($isEditPage) ? url("images/albums/{$album->img}") : ''}}"
                 alt="" class="backup_picture_album">

            <x-label for="description" value="Description"/>
            @error('description')
            <div style="color: red">{{ $message }}</div>
            @enderror
            <textarea id="description" rows="6" name="description"
                      class="block mt-1 mb-10 w-full">{{($isEditPage) ? $album->description : ''}}</textarea>

            <x-button>
                @if($isEditPage)
                    Edit
                @else
                    Add
                @endif
            </x-button>
        </div>
    </form>
</x-app-layout>
