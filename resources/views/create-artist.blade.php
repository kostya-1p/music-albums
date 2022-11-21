<x-app-layout>
    {{$isEditPage = isset($artist)}}

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if($isEditPage)
                Edit Artist
            @else
                Add Artist
            @endif
        </h2>
    </x-slot>


    <form method="POST"
          action="{{($isEditPage) ? route('artists.update', ['id'=>$artist->id]) : route('artists.store')}}"
          autocomplete="off">
        @csrf
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-6 bg-white border-b border-gray-200 m-10">
            @if($isEditPage)
                <input type="hidden" name="id" id="artist_id" value={{$artist->id}}>
                @method('PUT')
            @endif

            <x-label for="name" value="Name"/>
            @error('name')
            <div style="color: red">{{ $message }}</div>
            @enderror
            <x-input id="name" class="block mt-1 mb-10 w-full" type="text" name="name"
                     value="{{($isEditPage) ? $artist->name : ''}}" required/>

            <x-label for="image" value="Image Link"/>
            @error('img')
            <div style="color: red">{{ $message }}</div>
            @enderror
            <x-input list="imageList" id="image" name="img" class="block mt-1 mb-10 w-full" type="text" required/>

            <datalist id="imageList"></datalist>

            <img id="artist_image_preview" src="" alt="">

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
