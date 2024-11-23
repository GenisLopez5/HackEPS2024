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
                <div class="p-6">
                    <button onclick="findNearestParking()" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Find Nearest Available Parking
                    </button>
                    <span id="locationStatus" class="ml-4 text-gray-600 dark:text-gray-400"></span>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 text-2xl">
                    {{ __("Parking List") }}
                </div>
            </div>

            <div id="parkingList">
                @foreach($parkings as $parking)
                <a href="{{ route('parking', $parking->id) }}" class="no-underline">
                    <div class="parking-info bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-4 mt-4"
                         data-lat="{{ $parking->latitude }}" 
                         data-lng="{{ $parking->longitude }}"
                         data-id="{{ $parking->id }}">
                        <h2 class="text-2xl font-semibold mb-2 text-black dark:text-white">
                            {{ $parking->name }} ({{ $parking->occupied_percentage }}% full)
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
                    const userLat = position.coords.latitude;
                    const userLng = position.coords.longitude;
                    sortParkingByDistance(userLat, userLng);
                    statusElement.textContent = 'Locations sorted by distance';
                },
                (error) => {
                    statusElement.textContent = 'Unable to get your location: ' + error.message;
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
            const parkingList = document.getElementById('parkingList');
            const parkings = Array.from(parkingList.children);

            // Calculate distances and store with original elements
            const parkingsWithDistances = parkings.map(parking => {
                const parkingElement = parking.querySelector('.parking-info');
                const lat = parseFloat(parkingElement.dataset.lat);
                const lng = parseFloat(parkingElement.dataset.lng);
                const distance = calculateDistance(userLat, userLng, lat, lng);
                
                // Display distance in the parking card
                const distanceInfo = parkingElement.querySelector('.distance-info');
                distanceInfo.textContent = ` (${distance.toFixed(1)} km away)`;

                return {
                    element: parking,
                    distance: distance
                };
            });

            // Sort by distance and available spots
            parkingsWithDistances.sort((a, b) => {
                const availableA = parseInt(a.element.querySelector('.parking-info').textContent.match(/Available Spots: (\d+)/)[1]);
                const availableB = parseInt(b.element.querySelector('.parking-info').textContent.match(/Available Spots: (\d+)/)[1]);
                
                // Prioritize available spots, then distance
                if (availableA === 0 && availableB > 0) return 1;
                if (availableB === 0 && availableA > 0) return -1;
                return a.distance - b.distance;
            });

            // Reorder elements
            parkingsWithDistances.forEach(item => {
                parkingList.appendChild(item.element);
            });
        }
    </script>
</x-app-layout>
