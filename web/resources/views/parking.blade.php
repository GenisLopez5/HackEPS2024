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
                    <h2 class="text-2xl font-semibold text-black dark:text-white">
                            @if($parking->occupied_percentage != 100)
                                <span class="text-green-500">FREE</span>
                                @else 
                                <span class="text-red-500">FULL</span>
                            @endif
                        {{ $parking->name }} ({{ $parking->occupied }}/{{ $parking->capacity }} occupied)</h2>
                    <p class="text-lg text-black dark:text-white">{{ $parking->address }}</p>
                </div>
            </div>

            <!-- <p class="text-lg text-black dark:text-white">Available Spots: {{ $parking->capacity - $parking->occupied }}/{{ $parking->capacity }}</p> -->
           
            <div class="flex flex-wrap gap-4 flex-col md:flex-row">
                
                
                <div class="flex-1 px-4 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-4">
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
                <div class="flex-1 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-4">
                    
                    <!-- print % of full as BIG Number -->
                    <div class="p-6 text-gray-900 dark:text-gray-100 flex items-center justify-center h-full">
                        <div class="relative">
                            <canvas id="parkingChart" class="w-full h-full"></canvas>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-2xl font-semibold text-black dark:text-white">{{ floor($parking->occupied_percentage) }}%</span>
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
            <div class="flex flex-wrap gap-4 flex-col md:flex-row">
                <div class="flex-1 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-4">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <canvas id="barChart" class="w-full h-full"></canvas>
                        <script>
                            var ctx = document.getElementById('barChart').getContext('2d');
                            var barChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: ['00:00', '01:00', '02:00', '03:00', '04:00', '05:00', '06:00', '07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00'],
                                    datasets: [{
                                        label: 'Predicted Occupancy',
                                        data: [{{ implode(',', $parking->predictions) }}],
                                        backgroundColor: '#3b82f6'
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            display: false
                                        }
                                    },
                                    scales: {
                                        x: {
                                            grid: {
                                                color: '#374151'
                                            },
                                            ticks: {
                                                color: '#D1D5DB'
                                            }
                                        },
                                        y: {
                                            grid: {
                                                color: '#374151'
                                            },
                                            ticks: {
                                                color: '#D1D5DB'
                                            }
                                        }
                                    }
                                }
                            });
                        // Get current hour
                        const currentHour = new Date().getHours();

                        // Update background colors based on current hour
                        barChart.data.datasets[0].backgroundColor = barChart.data.labels.map((label, index) => {
                            return index <= currentHour ? '#ef4444' : '#3b82f6';
                        });

                        // Update the chart
                        barChart.update();
                        </script>
                </div>
            </div>

            </div>
        </div>
    </div>
</x-app-layout>