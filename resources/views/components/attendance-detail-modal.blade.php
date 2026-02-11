<x-modal wire:model="showDetail" onclose="removeMap()">
  <div class="px-6 py-4">
    @if ($currentAttendance)
      @php
        $isExcused = $currentAttendance['status'] == 'excused' || $currentAttendance['status'] == 'sick';
        $showMap = $currentAttendance['latitude'] && $currentAttendance['longitude'] && !$isExcused;
      @endphp
      <h3 class="mb-3 text-xl font-semibold dark:text-white">{{ $currentAttendance['name'] }}</h3>
      <div class="mb-3 w-full">
        <x-label for="nisn" value="{{ __('NISN') }}"></x-label>
        <x-input type="text" class="w-full" id="nisn" disabled value="{{ $currentAttendance['nisn'] }}"></x-input>
      </div>
      <div class="mb-3 flex w-full gap-3">
        <div class="w-full">
          <x-label for="date" value="{{ __('Date') }}"></x-label>
          <x-input type="text" class="w-full" id="date" disabled
            value="{{ $currentAttendance['date'] }}"></x-input>
        </div>
        <div class="w-full">
          <x-label for="status" value="{{ __('Status') }}"></x-label>
          <select 
            onchange="if(confirm('Ubah status absensi ({{ $currentAttendance['name'] }})?')) { @this.updateStatus({{ $currentAttendance['id'] }}, this.value) } else { this.value = '{{ $currentAttendance['status'] }}' }"
            class="w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
            wire:loading.attr="disabled"
          >
            <option value="present" {{ $currentAttendance['status'] == 'present' ? 'selected' : '' }}>Hadir</option>
            <option value="late" {{ $currentAttendance['status'] == 'late' ? 'selected' : '' }}>Terlambat</option>
            <option value="excused" {{ $currentAttendance['status'] == 'excused' ? 'selected' : '' }}>Izin</option>
            <option value="sick" {{ $currentAttendance['status'] == 'sick' ? 'selected' : '' }}>Sakit</option>
            <option value="absent" {{ $currentAttendance['status'] == 'absent' ? 'selected' : '' }}>Alpha</option>
          </select>
        </div>
      </div>
      @if ($isExcused)
        <div class="mb-3 w-full">
          <x-label for="address" value="{{ __('Address') }}" />
          <x-input type="text" class="w-full" id="address" disabled value="{{ $currentAttendance['address'] }}" />
        </div>
      @endif
      <div class="flex flex-col gap-3">
        @if ($currentAttendance['attachment'])
          <x-label for="attachment" value="{{ __('Attachment') }}"></x-label>
          <img src="{{ $currentAttendance['attachment'] }}" alt="Attachment"
            class="max-h-48 object-contain sm:max-h-64 md:max-h-72">
        @endif
        @if ($currentAttendance['note'])
          <x-label for="note" value="Keterangan" />
          <x-textarea type="text" id="note" disabled value="{{ $currentAttendance['note'] }}" />
        @endif
        @if ($showMap)
          <x-label for="map" value="Koordinat Lokasi Absen"></x-label>
          <p class="dark:text-gray-300">
            {{ $currentAttendance['latitude'] }}, {{ $currentAttendance['longitude'] }}
          </p>
          <div class="my-2 h-52 w-full md:h-64" id="map"></div>
        @endif
        @if ($currentAttendance['time_in'] || $currentAttendance['time_out'])
          <div class="grid grid-cols-2 gap-3">
            <x-label for="time_in" value="Waktu Masuk"></x-label>
            <x-label for="time_out" value="Waktu Keluar"></x-label>
            <x-input type="text" id="time_in" disabled
              value="{{ $currentAttendance['time_in'] ?? '-' }}"></x-input>
            <x-input type="text" id="time_out" disabled
              value="{{ $currentAttendance['time_out'] ?? '-' }}"></x-input>
          </div>
        @endif

        @if (isset($currentAttendance['face_photo_url']) || isset($currentAttendance['face_photo_out_url']) || isset($currentAttendance['validation_method']))
          <div class="border-t border-gray-200 dark:border-gray-700 pt-3 mt-3">
            <x-label value="Verifikasi Wajah" class="mb-2"></x-label>
            
            @if (isset($currentAttendance['validation_method']))
              <div class="mb-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                  {{ $currentAttendance['validation_method'] === 'face' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                  @if ($currentAttendance['validation_method'] === 'face')
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Terverifikasi Wajah
                  @else
                    Manual
                  @endif
                </span>
              </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              {{-- Clock In Face --}}
              @if (isset($currentAttendance['face_photo_url']))
                <div>
                  <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Absen Masuk</p>
                  <img src="{{ $currentAttendance['face_photo_url'] }}" alt="Foto Wajah Absen Masuk" 
                    class="w-full h-48 object-cover rounded-lg border border-gray-200 dark:border-gray-700">
                  
                  @if (isset($currentAttendance['face_similarity_score']))
                    <div class="mt-2">
                      <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-400 mb-1">
                        <span>Skor Kemiripan:</span>
                        <span class="font-semibold">{{ number_format($currentAttendance['face_similarity_score'] * 100, 1) }}%</span>
                      </div>
                      <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                        <div class="bg-green-500 h-1.5 rounded-full transition-all" 
                          style="width: {{ $currentAttendance['face_similarity_score'] * 100 }}%"></div>
                      </div>
                    </div>
                  @endif
                </div>
              @endif

              {{-- Clock Out Face --}}
              @if (isset($currentAttendance['face_photo_out_url']))
                <div>
                  <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Absen Keluar</p>
                  <img src="{{ $currentAttendance['face_photo_out_url'] }}" alt="Foto Wajah Absen Keluar" 
                    class="w-full h-48 object-cover rounded-lg border border-gray-200 dark:border-gray-700">
                  
                  @if (isset($currentAttendance['face_similarity_score_out']))
                    <div class="mt-2">
                      <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-400 mb-1">
                        <span>Skor Kemiripan:</span>
                        <span class="font-semibold">{{ number_format($currentAttendance['face_similarity_score_out'] * 100, 1) }}%</span>
                      </div>
                      <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                        <div class="bg-green-500 h-1.5 rounded-full transition-all" 
                          style="width: {{ $currentAttendance['face_similarity_score_out'] * 100 }}%"></div>
                      </div>
                    </div>
                  @endif
                </div>
              @endif
            </div>
          </div>
        @endif

        <div class="flex gap-3">
          @if ($currentAttendance['shift'] ?? false)
            <div class="w-full">
              <x-label for="shift" value="Shift"></x-label>
              <x-input class="w-full" type="text" id="shift" disabled
                value="{{ $currentAttendance['shift']['name'] }}"></x-input>
            </div>
          @endif
        </div>
      </div>
    @endif
  </div>
</x-modal>

@push('attendance-detail-scripts')
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  <script>
    let map = null;

    function setLocation(lat, lng) {
      removeMap();
      setTimeout(() => {
        const mapContainer = document.getElementById('map');
        if (mapContainer) {
          map = L.map('map').setView([Number(lat), Number(lng)], 19);
          L.marker([Number(lat), Number(lng)]).addTo(map);
          L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 21,
          }).addTo(map);
        }
      }, 500);
    }

    function removeMap() {
      if (map !== null) map.remove();
      map = null;
    }
  </script>
@endpush
