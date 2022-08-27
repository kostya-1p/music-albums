<x-app-layout>
    {{$isEditPage = isset($album)}}

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if($isEditPage)
                Edit Album
            @else
                Add Album
            @endif
        </h2>
    </x-slot>


    <form method="POST">
        @csrf
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-6 bg-white border-b border-gray-200 m-10">

            <x-label for="name" value="Name"/>
            <x-input id="name" class="block mt-1 mb-10 w-full" type="text" name="name"
                     value="{{($isEditPage) ? $album->name : ''}}" required/>

            <x-label for="artist" value="Artist"/>
            <x-input id="artist" name="artist" class="block mt-1 mb-10 w-full" type="text"
                     value="{{($isEditPage) ? $album->artist : ''}}" required/>

            <x-label for="image" value="Image Link"/>
            <x-input id="image" name="img" class="block mt-1 mb-10 w-full" type="text"
                     value="{{($isEditPage) ? $album->img : ''}}" required/>

            <x-label for="description" value="Description"/>
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
