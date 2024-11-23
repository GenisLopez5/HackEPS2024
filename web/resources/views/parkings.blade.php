<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Parking List') }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 text-2xl">
                    {{ __("Parking List") }}
                    <!-- change to Parking Map -->
                    <a href="{{ 1 }}" class="text-blue-500 dark:text-blue-300">View Parking Map</a>
                </div>
            </div>
            @foreach($parkings as $parking)
            <a href="{{ route('parking', $parking->id) }}" class="no-underline">
                <div class="parking-info bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-4 mt-4">

                    <h2 class="text-2xl font-semibold mb-2 text-black dark:text-white">{{ $parking->name }} ({{ $parking->occupied_percentage }}% full)</h2>
                    <p class="text-lg text-black dark:text-white">{{ $parking->address }}</p>

                    <p class="text-lg text-black dark:text-white">Available Spots: {{ $parking->capacity - $parking->occupied }}/{{ $parking->capacity }}</p>
                </div>
            </a>
            @endforeach

        </div>
    </div>
</x-app-layout>