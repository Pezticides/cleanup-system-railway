<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Submit Clean-Up Report
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm rounded-2xl p-8">

                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">
                        Submit Clean-Up Report
                    </h1>

                    <p class="text-gray-600 mt-2">
                        Help improve the community by reporting environmental concerns.
                    </p>
                </div>

                @if($errors->any())
                    <div class="mb-6 bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-xl">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="/report" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Your Name
                        </label>

                        <input
                            type="text"
                            name="reporter_name"
                            required
                            value="{{ old('reporter_name', auth()->user()->name ?? '') }}"
                            class="w-full rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Location Name / Landmark
                        </label>

                        <input
                            type="text"
                            name="location"
                            id="locationInput"
                            required
                            value="{{ old('location') }}"
                            placeholder="Example: Zone 1, Barangay Carmen, CDO"
                            class="w-full rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500"
                        >
                    </div>

                    <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                    <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            Pin Exact Location
                        </label>

                        <div class="flex flex-wrap gap-3 mb-4">

                            <button
                                type="button"
                                onclick="useMyLocation()"
                                class="px-4 py-2 bg-green-700 text-white rounded-xl font-semibold hover:bg-green-800">
                                Use My Current Location
                            </button>

                            <button
                                type="button"
                                onclick="clearPin()"
                                class="px-4 py-2 bg-gray-600 text-white rounded-xl font-semibold hover:bg-gray-700">
                                Clear Pin
                            </button>

                        </div>

                        <p class="text-sm text-gray-500 mb-3" id="locationStatus">
                            Click the map to place the exact pin.
                        </p>

                        <div
                            id="map"
                            class="w-full h-[400px] rounded-2xl border border-gray-300 overflow-hidden">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Concern Type
                        </label>

                        <select
                            name="concern_type"
                            required
                            class="w-full rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500"
                        >
                            @foreach(['Garbage Dumping','Clogged Canal','Dirty Street','Unmanaged Waste','Flooding','Other'] as $type)
                                <option value="{{ $type }}" {{ old('concern_type') == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Description
                        </label>

                        <textarea
                            name="description"
                            required
                            rows="5"
                            class="w-full rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500"
                        >{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Upload Picture
                        </label>

                        <input
                            type="file"
                            name="photo"
                            id="photoInput"
                            accept="image/*"
                            class="block w-full text-sm text-gray-700"
                        >

                        <img
                            id="preview"
                            class="hidden mt-4 rounded-2xl w-64 h-64 object-cover border border-gray-200 shadow-sm"
                        >
                    </div>

                    <div class="flex flex-wrap gap-3 pt-4">

                        <button
                            type="submit"
                            class="px-6 py-3 bg-green-700 text-white rounded-xl font-semibold hover:bg-green-800">
                            Submit Report
                        </button>

                        <a
                            href="/"
                            class="px-6 py-3 bg-gray-600 text-white rounded-xl font-semibold hover:bg-gray-700">
                            Back to Reports
                        </a>

                    </div>

                </form>

            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        const oldLat = document.getElementById('latitude').value;
        const oldLng = document.getElementById('longitude').value;

        const defaultLat = oldLat || 8.4542;
        const defaultLng = oldLng || 124.6319;

        let map = L.map('map').setView(
            [defaultLat, defaultLng],
            oldLat && oldLng ? 17 : 13
        );

        L.tileLayer(
            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            {
                maxZoom: 19,
                attribution: 'OpenStreetMap'
            }
        ).addTo(map);

        let marker = null;

        function setPin(lat, lng, message = 'Pinned location') {

            document.getElementById('latitude').value = Number(lat).toFixed(7);

            document.getElementById('longitude').value = Number(lng).toFixed(7);

            if (marker) {

                marker.setLatLng([lat, lng]);

            } else {

                marker = L.marker(
                    [lat, lng],
                    { draggable: true }
                ).addTo(map);

                marker.on('dragend', e => {

                    const p = e.target.getLatLng();

                    setPin(p.lat, p.lng, 'Pin moved');

                });
            }

            map.setView([lat, lng], 17);

            document.getElementById('locationStatus').innerText =
                message + ': ' +
                Number(lat).toFixed(7) +
                ', ' +
                Number(lng).toFixed(7);
        }

        if (oldLat && oldLng) {

            setPin(oldLat, oldLng, 'Saved pin');

        }

        map.on('click', e =>

            setPin(
                e.latlng.lat,
                e.latlng.lng,
                'Manual pin set'
            )

        );

        function useMyLocation() {

            const status = document.getElementById('locationStatus');

            if (!navigator.geolocation) {

                status.innerText =
                    'Geolocation is not supported.';

                return;
            }

            status.innerText =
                'Getting GPS location...';

            navigator.geolocation.getCurrentPosition(

                pos =>

                    setPin(
                        pos.coords.latitude,
                        pos.coords.longitude,
                        'GPS pin set'
                    ),

                () =>

                    status.innerText =
                    'GPS failed. Click map manually.',

                {
                    enableHighAccuracy: true,
                    timeout: 10000
                }
            );
        }

        function clearPin() {

            if (marker) {

                map.removeLayer(marker);

                marker = null;
            }

            document.getElementById('latitude').value = '';

            document.getElementById('longitude').value = '';

            document.getElementById('locationStatus').innerText =
                'Pin cleared. Click map to set location.';
        }

        document
            .getElementById('photoInput')
            .addEventListener('change', function(event) {

                const file = event.target.files[0];

                const preview =
                    document.getElementById('preview');

                if (file) {

                    preview.src =
                        URL.createObjectURL(file);

                    preview.classList.remove('hidden');
                }
            });

        setTimeout(() => map.invalidateSize(), 300);
    </script>
</x-app-layout>