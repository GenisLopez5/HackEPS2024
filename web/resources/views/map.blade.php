<x-app-layout>
<x-slot:extraScripts>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
        <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
        <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
        <script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>
        <style>
            #map {
                height: 50rem;
                width: 100%;
            }

            .leaflet-top .leaflet-routing-container:nth-of-type(1) {
                display: none;
            }

            /* Customize the appearance of the turn instruction container */
            .leaflet-routing-container {
                background-color: #fff;
                border: 2px solid #3388ff;
                border-radius: 5px;
                padding: 10px;
            }

            /* Customize the appearance of turn instruction text */
            .leaflet-routing-conain {
                background-color: #fff;
                color: #333;
                font-size: 14px;
            }

            /* Customize the appearance of turn instruction icons */
            .leaflet-routing-icon {
                background-color: #fff;
                width: 24px;
                height: 24px;
                margin-right: 5px;
            }
        </style>
    </x-slot:extraScripts>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __("Parking Map") }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 text-2xl">
                    {{ __("Parking Map") }}
                </div>
            </div> -->
            
            <div id="map"></div>
            
            <!-- generate mapbox map and render all parkings -->
            <script>
                var map = L.map('map').setView([41.6152682123882, 0.6214800949316924], 15);
                // var map = L.map('map').setView([{{ $parkings[0]->lat }} 4 {{ $parkings[0]->lng }}], 13);
                L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/satellite-v9/tiles/{z}/{x}/{y}?access_token={{ env("MAPBOX_API") }}', {
                                maxZoom: 19,
                                tileSize: 512,
                                zoomOffset: -1,
                                attribution: '© <a href="https://www.mapbox.com/about/maps/">Mapbox</a> © <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                            }).addTo(map);
                @foreach($parkings as $parking)
                L.marker([{{ $parking->lat }}, {{ $parking->lng }}]).addTo(map)
                    .bindPopup('<a href="{{ route("parking", $parking->id) }}" class="no-underline"><h2 class="text-2xl font-semibold mb-2 text-dark">{{ $parking->name }} ({{ floor($parking->occupied_percentage) }}% full)</h2><p class="text-lg text-dark">{{ $parking->address }}</p><p class="text-lg text-dark">Available Spots: {{ $parking->capacity - $parking->occupied }}/{{ $parking->capacity }}</p></a>')
                    // .bindPopup('{{ $parking->name }}')
                    .openPopup();
                @endforeach

            </script>

<!-- create a heat map layer -->
            <script>
                var heatData = [
                    @foreach($parkings as $parking)
                    [{{ $parking->lat }}, {{ $parking->lng }}, {{ $parking->occupied_percentage }}],
                    @endforeach
                ];
                var heat = L.heatLayer(heatData, {
                    radius: 70,
                    blur: 45,
                    maxZoom: 22,
                }).addTo(map);
            </script>

        </div>
    </div>
</x-app-layout>