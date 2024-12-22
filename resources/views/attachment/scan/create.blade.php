<x-app-layout>

    <x-slot name="header">
        <!-- App CSS -->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="header-buttons mb-4">
                        <a href="{{ url()->previous() }}" class="btn btn-custom-back">
                            {{ __('word.back') }}
                        </a>
                    </div>

                    <div class="container mx-auto p-4">

                        <h1 class="text-2xl font-bold mb-4">مسح ضوئي</h1>

                        <!-- Device Selection -->
                        <div class="mb-4">
                            <label for="device" class="block text-sm font-medium text-gray-700">
                                Select Scanner
                            </label>
                            <select id="device" name="device_id"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">-- اختر الجهاز --</option>
                                <!-- Options will be populated here -->
                            </select>
                        </div>

                        <!-- Start Scan Button -->
                        <button type="button" id="scan-button"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            ابدأ
                        </button>

                        <!-- Loading Spinner -->
                        <div id="loading" class="hidden mt-4">
                            <span class="text-sm text-gray-500">جاري عملية المسح الضوئي الرجاء الانتظار...</span>
                        </div>

                        <!-- Scan Result -->
                        <div id="scan-result" class="mt-4" style="width: 300px; height: 600px;"></div>

                        <!-- Error Message -->
                        <div id="error-message" class="hidden mt-4 text-red-600 text-sm"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const devices = @json($devices); // Pass devices from your backend
            const scanButton = document.getElementById('scan-button');
            const deviceSelect = document.getElementById('device');
            const loading = document.getElementById('loading');
            const scanResult = document.getElementById('scan-result');
            const errorMessage = document.getElementById('error-message');

            const model = @json($model); // The model passed from the controller
            const recordId = @json($record->id); // The record ID passed from the controller
            // Populate the device select options
            devices.forEach(device => {
                const option = document.createElement('option');
                option.value = device.id;
                option.textContent = device.name;
                deviceSelect.appendChild(option);
            });
            scanButton.addEventListener('click', function() {
                const deviceId = deviceSelect.value;

                if (!deviceId) {
                    alert('يرجى اختيار الجهاز.');
                    return;
                }

                // Show loading spinner
                loading.classList.remove('hidden');
                scanResult.innerHTML = ''; // Clear previous results
                errorMessage.classList.add('hidden'); // Hide previous error messages

                fetch(`/scan/${model}/${recordId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify({
                            device_id: deviceId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Hide loading spinner
                        loading.classList.add('hidden');

                        if (data.image_path) {
                            scanResult.innerHTML = `
                            <img src="${data.image_path}" alt="Scanned Image" class="img-fluid rounded" />
                        `;
                        } else if (data.error) {
                            errorMessage.classList.remove('hidden');
                            errorMessage.textContent = `Error: ${data.error}`;
                        }
                    })
                    .catch(error => {
                        // Hide loading spinner
                        loading.classList.add('hidden');
                        errorMessage.classList.remove('hidden');
                        errorMessage.textContent = `Error: ${error.message}`;
                    });
            });
        });
    </script>

</x-app-layout>
