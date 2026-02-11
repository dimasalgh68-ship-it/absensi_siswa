<x-admin-layout>
  <x-slot name="header">
    <h1 class="h3 mb-0 text-gray-800">
      {{ __('Detail Absensi') }}
    </h1>
  </x-slot>

  <div class="card shadow mb-4">
    <div class="card-body">
          <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
              <x-label for="user" value="{{ __('Nama') }}" />
              <x-input id="user" type="text" class="block w-full mt-1" value="{{ $attendance->user->name }}" readonly />
            </div>

            <div>
              <x-label for="nim" value="{{ __('NISN') }}" />
              <x-input id="nim" type="text" class="block w-full mt-1" value="{{ $attendance->user->nisn}}" readonly />
            </div>

            <div>
              <x-label for="date" value="{{ __('Tanggal') }}" />
              <x-input id="date" type="text" class="block w-full mt-1" value="{{ $attendance->date->format('d/m/Y') }}" readonly />
            </div>

            <div>
              <x-label for="shift" value="{{ __('Jadwal') }}" />
              <x-input id="shift" type="text" class="block w-full mt-1" value="{{ $attendance->shift->name ?? '-' }}" readonly />
            </div>

            <div>
              <x-label for="time_in" value="{{ __('Jam Masuk') }}" />
              <x-input id="time_in" type="text" class="block w-full mt-1" value="{{ $attendance->time_in ? $attendance->time_in->format('H:i') : '-' }}" readonly />
            </div>

            <div>
              <x-label for="time_out" value="{{ __('Jam Keluar') }}" />
              <x-input id="time_out" type="text" class="block w-full mt-1" value="{{ $attendance->time_out ? $attendance->time_out->format('H:i') : '-' }}" readonly />
            </div>

            <div>
              <x-label for="status" value="{{ __('Status') }}" />
              <x-input id="status" type="text" class="block w-full mt-1" value="{{ $attendance->status }}" readonly />
            </div>

            <div>
              <x-label for="note" value="{{ __('Catatan') }}" />
              <x-textarea id="note" class="block w-full mt-1" readonly>{{ $attendance->note }}</x-textarea>
            </div>

            <div class="md:col-span-2">
              <x-label for="attachment" value="{{ __('Lampiran') }}" />
              @if($attendance->attachment)
                <div class="mt-2">
                  <a href="{{ $attendance->attachment_url }}" target="_blank" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                    {{ __('Lihat Lampiran') }}
                  </a>
                </div>
              @else
                <x-input id="attachment" type="text" class="block w-full mt-1" value="-" readonly />
              @endif
            </div>

            @if($attendance->lat_lng)
              <div class="md:col-span-2">
                <x-label value="{{ __('Lokasi') }}" />
                <div id="map" class="w-full h-64 mt-2 rounded-lg"></div>
                <script>
                  document.addEventListener('DOMContentLoaded', function() {
                    const latLng = @json($attendance->lat_lng);
                    const map = L.map('map').setView([latLng.lat, latLng.lng], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(map);
                    L.marker([latLng.lat, latLng.lng]).addTo(map);
                  });
                </script>
              </div>
            @endif
          </div>

          <div class="flex items-center justify-end mt-6">
            <x-secondary-button onclick="window.history.back()">
              {{ __('Kembali') }}
            </x-secondary-button>
          </div>
        </x-admin-layout>
