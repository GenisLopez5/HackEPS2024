<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Parking List') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search nearest parking button -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-4">
                
                
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">

                        <div class="text-gray-900 dark:text-gray-100 text-2xl">
                            {{ __("Parking List") }}
                        </div>
                        <div class="text-base my-2">

                            <button onclick="findNearestParking()" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-2">
                            
                            <i class="fa-solid fa-location-arrow"></i>
                            
                            Find Nearest Available Parking
                        </button>
                        <span id="locationStatus" class="ml-4 text-gray-600 dark:text-gray-400 d-inline mt-4"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div id="parkingList">
                @foreach($parkings as $parking)
                <a href="{{ route('parking', $parking->id) }}" class="no-underline" id="{{ $parking->id }}" data-occupied="{{ $parking->occupied_percentage }}" data-lat="{{ $parking->lat }}" data-lng="{{ $parking->lng }}">
                    
                    <div class="parking-info bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-4 mt-4"
                         data-lat="{{ $parking->latitude }}" 
                         data-lng="{{ $parking->longitude }}"
                         data-id="{{ $parking->id }}">
                        <h2 class="text-2xl font-semibold mb-2 text-black dark:text-white">
                            <!-- if occupied != 100 print FREE in gree -->
                            @if($parking->occupied_percentage != 100)
                                <span class="text-green-500">FREE</span>
                                @else 
                                <span class="text-red-500">FULL</span>
                            @endif

                            {{ $parking->name }} 
                            <span class="occupied_percentage">
                                ({{ floor($parking->occupied_percentage) }}% occupied)
                            </span>
                                <span class="distance-info text-sm text-gray-600 dark:text-gray-400"></span>
                        </h2>
                        <p class="text-lg text-black dark:text-white">{{ $parking->address }}</p>
                        <p class="text-lg text-black dark:text-white">
                            Available Spots: {{ $parking->capacity - $parking->occupied }}/{{ $parking->capacity }}
                        </p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        function findNearestParking() {
            const statusElement = document.getElementById('locationStatus');
            statusElement.textContent = 'Getting your location...';

            if (!navigator.geolocation) {
                statusElement.textContent = 'Geolocation is not supported by your browser';
                return;
            }

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    // const userLat = position.coords.latitude;
                    const userLat = 41.608183387096865;
                    // const userLng = position.coords.longitude;
                    const userLng = 0.6234979539189234;
                    statusElement.textContent = 'Locations sorted by distance';
                    sortParkingByDistance(userLat, userLng);
                },
                (error) => {
                    const userLat = 41.608183387096865;
                    const userLng = 0.6234979539189234;
                    statusElement.textContent = 'Locations sorted by distance';
                    sortParkingByDistance(userLat, userLng);
                }
            );
        }

        function calculateDistance(lat1, lon1, lat2, lon2) {
            // Haversine formula for calculating distance between two points
            const R = 6371; // Radius of the Earth in km
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = 
                Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
                Math.sin(dLon/2) * Math.sin(dLon/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            return R * c; // Distance in km
        }

        function sortParkingByDistance(userLat, userLng) {
            debugger;
            const parkingList = document.getElementById('parkingList');
            const parkingsWithDistances = [];
            const filledParkings = [];
            // Calculate distance for each parking
            parkingList.childNodes.forEach(item => {
                if (item.nodeName === 'A') {
                    const parkingLat = parseFloat(item.dataset.lat);
                    const parkingLng = parseFloat(item.dataset.lng);
                    const distance = calculateDistance(userLat, userLng, parkingLat, parkingLng); // Distance in km
                    if(item.dataset.occupied == 100){
                        filledParkings.push({
                            element: item,
                            distance: distance
                        });
                    } else {
                        parkingsWithDistances.push({
                            element: item,
                            distance: distance
                       });
                    }
                }
            });

            // Sort by distance
            parkingsWithDistances.sort((a, b) => {
                return a.distance - b.distance;
            });


            // append filled parkings to the end of the list
            // Reorder elements
            parkingsWithDistances.forEach(item => {
                parkingList.appendChild(item.element);
                // for each parking, print the distance
                const distanceInfo = item.element.querySelector('.distance-info');
                distanceInfo.textContent = `(${item.distance.toFixed(2)} km)`;

                // 
            });
            if(filledParkings.length > 0){
                filledParkings.forEach(item => {
                    parkingList.appendChild(item.element);
                });
            }
            
        }
        
    </script>
</x-app-layout>
