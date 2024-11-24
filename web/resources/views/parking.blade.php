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
                    <!-- far right button to Open in google maps -->
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-semibold text-black dark:text-white">
                                @if($parking->occupied_percentage != 100)
                                    <span class="text-green-500">FREE</span>
                                @else 
                                    <span class="text-red-500">FULL</span>
                                @endif
                                {{ $parking->name }} ({{ $parking->occupied }}/{{ $parking->capacity }} occupied)
                            </h2>
                            <p class="text-lg text-black dark:text-white">{{ $parking->address }}</p>
                        </div>
                        <a href="https://www.google.com/maps/search/?api=1&query={{ $parking->lat }},{{ $parking->lng }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-500 hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition duration-150 ease-in-out">
                            <i class="fa-solid fa-map-marker-alt mr-2"></i>
                            Open in Google Maps
                        </a>
                    </div>
                </div>
            </div>

            <!-- <p class="text-lg text-black dark:text-white">Available Spots: {{ $parking->capacity - $parking->occupied }}/{{ $parking->capacity }}</p> -->
           
            <div class="flex flex-wrap gap-4 flex-col md:flex-row">
                
                
                <div class="flex-1 px-4 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-4">
                    <!-- map of location -->
                    <div class=" p-6 text-gray-900 dark:text-gray-100">
                        <div id="map" style="height: 400px;"></div>
                        
                        <script>
                            
                            // await dom
                            document.addEventListener('DOMContentLoaded', function() {
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
                            });
                            
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
            <div class="flex flex-wrap gap-4 flex-col">
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
                                        data: {!! json_encode($parking->predictions) !!},
                                        backgroundColor: '#3b82f6'
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: true,
                                    aspectRatio: 2,
                                    plugins: {
                                        legend: {
                                            display: false
                                        },
                                        title: {
                                            display: true,
                                            text: 'Real & Prediction Data',
                                            color: '#D1D5DB'
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
            <div class="flex flex-wrap gap-4 flex-col md:flex-row">
                <div class="flex-1 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-4">
                    <!-- plot historical data -->
                     <!-- x-axis - time -->
                     <!-- y axis - occupancy -->
                    <!-- use pagination to go through pages of data -->
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <canvas id="lineChart" class="w-full h-full"></canvas>
                        <script>
                            let daysAgo = 1;
                            // Define pagination variables
                            const itemsPerPage = 24; // Show 24 hours of data per page
                            let historicData = {
                                times: {!! json_encode(collect($parking->historic_data)->pluck('time')) !!},
                                occupied: {!! json_encode(collect($parking->historic_data)->pluck('occupied')) !!}
                            };
                            // Calculate initial page to show latest data
                            let maxPage = Math.max(0, Math.floor((historicData.times.length - 1) / itemsPerPage))-1;
                            let currentPage = maxPage;

                            function updateChart() {
                                const startIndex = currentPage * itemsPerPage;
                                const endIndex = startIndex + itemsPerPage;
                                
                                var ctx = document.getElementById('lineChart').getContext('2d');
                                if (window.lineChart instanceof Chart) {
                                    window.lineChart.destroy();
                                }
                                
                                window.lineChart = new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        labels: historicData.times.slice(startIndex, endIndex),
                                        datasets: [{
                                            label: 'Occupancy',
                                            data: historicData.occupied.slice(startIndex, endIndex),
                                            borderColor: '#3b82f6',
                                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                            borderWidth: 2
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: true,
                                        aspectRatio: 2,
                                        plugins: {
                                            legend: {
                                                display: false
                                            },
                                            title: {
                                                display: true,
                                                text: 'Historical Data - '+daysAgo+' day/s ago',
                                                color: '#D1D5DB'
                                            }
                                        },
                                        scales: {
                                            x: {
                                                grid: { color: '#374151' },
                                                ticks: { color: '#D1D5DB' }
                                            },
                                            y: {
                                                grid: { color: '#374151' },
                                                ticks: { color: '#D1D5DB' }
                                            }
                                        }
                                    }
                                });
                            }

                            // Add pagination controls
                            document.write(`
                                <div class="flex justify-center gap-4 mt-4">
                                    <button onclick="previousPage()" class="px-4 py-2 bg-blue-500 text-white rounded">Previous</button>
                                    <button onclick="nextPage()" class="px-4 py-2 bg-blue-500 text-white rounded">Next</button>
                                </div>
                            `);

                            function previousPage() {
                                if (currentPage > 0) {
                                    currentPage--;
                                    daysAgo++;
                                    updateChart();
                                }
                            }

                            function nextPage() {
                                if ((currentPage) * itemsPerPage < historicData.times.length && currentPage < maxPage) {
                                    currentPage++;
                                    daysAgo--;
                                    updateChart();
                                }
                            }

                            // Calculate dates for the pagination
                            function getDateForPage(page) {
                                const date = new Date();
                                date.setDate(date.getDate() - Math.floor(page));
                                return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                            }

                            // Update title when page changes
                            function updateTitle() {
                                const chartTitle = `Historical Data - ${getDateForPage(currentPage)}`;
                                if (window.lineChart) {
                                    window.lineChart.options.plugins.title.text = chartTitle;
                                }
                            }
                            // Initial chart render
                            updateChart();
                    
                        </script>

                    

            </div>
        </div>
    </div>
</x-app-layout>