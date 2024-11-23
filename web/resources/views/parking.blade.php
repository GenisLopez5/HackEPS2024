<x-app-layout>
    <x-slot:extraScripts>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
        <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
        <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            {{ __('Dashboard') }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 text-2xl">
                    <h2 class="text-2xl font-semibold text-black dark:text-white">{{ $parking->name }} ({{ $parking->occupied * 100 / $parking->capacity }}% full)</h2>
                    <p class="text-lg text-black dark:text-white">{{ $parking->address }}</p>
                </div>
            </div>

            <!-- <p class="text-lg text-black dark:text-white">Available Spots: {{ $parking->capacity - $parking->occupied }}/{{ $parking->capacity }}</p> -->
            <div class="flex gap-4">

                <div class="w-full md:w-1/2 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-4">
                    <!-- map of location -->
                    <div class=" p-6 text-gray-900 dark:text-gray-100">
                        <div id="map" style="height: 400px;"></div>
                        
                        <script>
                            var map = L.map('map').setView([{{ $parking->lat }}, {{ $parking->lng }}], 16);
                            
                            L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/satellite-v9/tiles/{z}/{x}/{y}?access_token={{ env("MAPBOX_API") }}', {
                                maxZoom: 19,
                                tileSize: 512,
                                zoomOffset: -1,
                                attribution: '© <a href="https://www.mapbox.com/about/maps/">Mapbox</a> © <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                            }).addTo(map);
                            
                            L.marker([{{ $parking->lat }}, {{ $parking->lng }}]).addTo(map)
                            .bindPopup('{{ $parking->name }}')
                                .openPopup();
                        </script>
                    </div>
                </div>
                <div class="w-full md:w-1/2 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-4">
                    
                    <!-- print % of full as BIG Number -->
                    <div class="p-6 text-gray-900 dark:text-gray-100 flex items-center justify-center h-full">
                        <div class="relative">
                            <canvas id="parkingChart" class="w-full h-full"></canvas>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-2xl font-semibold text-black dark:text-white">{{ $parking->occupied * 100 / $parking->capacity }}%</span>
                            </div>
                        </div>
                    </div>
                    <script>
                        var ctx = document.getElementById('parkingChart').getContext('2d');
                        var parkingChart = new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                datasets: [{
                                    data: [{{ $parking->occupied }}, {{ $parking->capacity - $parking->occupied }}],
                                    backgroundColor: ['#3b82f6', '#e5e7eb'],
                                    borderWidth: 0
                                }]
                            },
                            options: {
                                cutout: '70%',
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                }
                            }
                        });
                    </script>
                    </div>



                </div>
            </div>
        </div>
    </div>
</x-app-layout>