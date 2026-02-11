<div class="w-full bg-white dark:bg-gray-900 rounded-[1.25rem] p-4 md:p-6">
  @php
    use Illuminate\Support\Carbon;
  @endphp
  @pushOnce('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  @endpushOnce
  @pushOnce('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
      integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
      let currentMap = document.getElementById('currentMap');
      let map = document.getElementById('map');

      setTimeout(() => {
        toggleMap();
        toggleCurrentMap();
      }, 1000);

      function toggleCurrentMap() {
        const mapIsVisible = currentMap.style.display === "none";
        currentMap.style.display = mapIsVisible ? "block" : "none";
        document.querySelector('#toggleCurrentMap').innerHTML = mapIsVisible ?
          `<x-heroicon-s-chevron-up class="mr-2 h-5 w-5" />` :
          `<x-heroicon-s-chevron-down class="mr-2 h-5 w-5" />`;
      }

      function toggleMap() {
        const mapIsVisible = map.style.display === "none";
        map.style.display = mapIsVisible ? "block" : "none";
      }
    </script>
  @endpushOnce

  @if (!$isAbsence)
    <script src="{{ url('/assets/js/html5-qrcode.min.js') }}"></script>
  @endif

  <div class="flex flex-col lg:flex-row gap-8">
    {{-- Left Column: Scanner --}}
    @if (!$isAbsence)
      <div class="w-full lg:w-1/2 flex flex-col gap-4">
        <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
          <label for="shift" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pilih Shift</label>
          <x-select id="shift" class="block w-full rounded-lg border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500" wire:model="shift_id" disabled="{{ !is_null($attendance) }}">
            <option value="">{{ __('Select Shift') }}</option>
            @foreach ($shifts as $shift)
              <option value="{{ $shift->id }}" {{ $shift->id == $shift_id ? 'selected' : '' }}>
                {{ $shift->name . ' | ' . $shift->start_time . ' - ' . $shift->end_time }}
              </option>
            @endforeach
          </x-select>
          @error('shift_id')
            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
          @enderror
        </div>

        <div class="flex justify-center bg-gray-100 dark:bg-gray-800 rounded-2xl overflow-hidden relative aspect-square" wire:ignore>
            <div id="scanner" class="w-full h-full object-cover"></div>
            <div class="absolute inset-0 pointer-events-none border-[3px] border-white/30 rounded-2xl z-10"></div>
        </div>
        
        <div class="text-center">
             <p class="text-sm text-gray-500 dark:text-gray-400">Pastikan QR Code berada di dalam kotak area scan.</p>
        </div>
      </div>
    @endif

    {{-- Right Column: Info & Actions --}}
    <div class="w-full {{ !$isAbsence ? 'lg:w-1/2' : 'lg:w-full' }} space-y-6">
      
      {{-- Status Messages --}}
      <div>
          <h4 id="scanner-error" class="text-center text-sm font-semibold text-red-500 dark:text-red-400" wire:ignore></h4>
          <h4 id="scanner-result" class="hidden text-center text-lg font-bold text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20 p-3 rounded-xl border border-green-200 dark:border-green-800">
            {{ $successMsg }}
          </h4>
      </div>

      {{-- Location Info Card --}}
      <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <x-heroicon-o-map-pin class="w-5 h-5 text-blue-500"/>
                Lokasi Anda
            </h3>
            <span class="text-xs font-medium px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded text-gray-600 dark:text-gray-300">
                {{ now()->format('d M Y') }}
            </span>
        </div>
        
        <div class="text-sm text-gray-600 dark:text-gray-300">
             @if (!is_null($currentLiveCoords))
              <div class="flex flex-col gap-2">
                <div class="flex justify-between items-center">
                    <a href="{{ \App\Helpers::getGoogleMapsUrl($currentLiveCoords[0], $currentLiveCoords[1]) }}" target="_blank"
                    class="text-blue-600 hover:underline truncate max-w-[200px]">
                    {{ $currentLiveCoords[0] . ', ' . $currentLiveCoords[1] }}
                    </a>
                    <button class="text-gray-400 hover:text-gray-600" onclick="toggleCurrentMap()" id="toggleCurrentMap">
                    <x-heroicon-s-chevron-down class="h-5 w-5" />
                    </button>
                </div>
                <div class="h-40 w-full rounded-lg overflow-hidden border border-gray-200 dark:border-gray-600 mt-2" id="currentMap" wire:ignore></div>
              </div>
            @else
              <div class="flex items-center gap-2 text-amber-600 bg-amber-50 dark:bg-amber-900/20 p-2 rounded-lg">
                  <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                  <span>Mendeteksi lokasi...</span>
              </div>
            @endif
        </div>
      </div>

      {{-- Attendance Status Cards --}}
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <!-- Masuk -->
        <div class="relative overflow-hidden rounded-xl p-4 border {{ $attendance?->status == 'late' ? 'bg-red-50 border-red-100 dark:bg-red-900/20 dark:border-red-800' : 'bg-blue-50 border-blue-100 dark:bg-blue-900/20 dark:border-blue-800' }}">
            <div class="relative z-10">
                <p class="text-xs font-medium uppercase tracking-wider {{ $attendance?->status == 'late' ? 'text-red-600 dark:text-red-400' : 'text-blue-600 dark:text-blue-400' }}">Absen Masuk</p>
                <h4 class="text-2xl font-bold mt-1 {{ $attendance?->status == 'late' ? 'text-red-700 dark:text-red-300' : 'text-blue-700 dark:text-blue-300' }}">
                    @if ($isAbsence)
                        {{ __($attendance?->status) ?? '-' }}
                    @else
                        {{ $attendance?->time_in ? Carbon::parse($attendance?->time_in)->format('H:i') : '--:--' }}
                    @endif
                </h4>
                @if ($attendance?->status == 'late')
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 mt-2">
                        Terlambat
                    </span>
                @endif
            </div>
            <x-heroicon-o-arrow-right-end-on-rectangle class="absolute right-[-10px] bottom-[-10px] w-20 h-20 opacity-10 {{ $attendance?->status == 'late' ? 'text-red-600' : 'text-blue-600' }}" />
        </div>

        <!-- Keluar -->
        <div class="relative overflow-hidden rounded-xl p-4 border bg-purple-50 border-purple-100 dark:bg-purple-900/20 dark:border-purple-800">
            <div class="relative z-10">
                <p class="text-xs font-medium uppercase tracking-wider text-purple-600 dark:text-purple-400">Absen Keluar</p>
                <h4 class="text-2xl font-bold mt-1 text-purple-700 dark:text-purple-300">
                    @if ($isAbsence)
                        {{ __($attendance?->status) ?? '-' }}
                    @else
                        {{ $attendance?->time_out ? Carbon::parse($attendance?->time_out)->format('H:i') : '--:--' }}
                    @endif
                </h4>
            </div>
            <x-heroicon-o-arrow-left-start-on-rectangle class="absolute right-[-10px] bottom-[-10px] w-20 h-20 opacity-10 text-purple-600" />
        </div>
      </div>

      {{-- Map Toggle Button --}}
      <button
          class="w-full flex items-center justify-between p-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors group"
          {{ is_null($attendance?->lat_lng) ? 'disabled' : 'onclick=toggleMap()' }} id="toggleMap">
          <div class="flex items-center gap-3">
            <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg group-hover:bg-white dark:group-hover:bg-gray-600 transition-colors">
                <x-heroicon-o-map class="w-6 h-6 text-gray-600 dark:text-gray-300" />
            </div>
            <div class="text-left">
                <h4 class="font-semibold text-gray-900 dark:text-white">Lokasi Absen</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    @if (is_null($attendance?->lat_lng))
                    Belum ada data
                    @else
                    {{ Str::limit($attendance?->latitude . ', ' . $attendance?->longitude, 30) }}
                    @endif
                </p>
            </div>
          </div>
          <x-heroicon-s-chevron-down class="w-5 h-5 text-gray-400" />
      </button>
      <div class="h-52 w-full rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 hidden" id="map" wire:ignore></div>

      <hr class="border-gray-100 dark:border-gray-700">

      {{-- Quick Actions --}}
      <div class="grid grid-cols-3 gap-3" wire:ignore>
        <a href="{{ route('apply-leave') }}" class="group flex flex-col items-center gap-2 p-3 rounded-xl bg-gray-50 dark:bg-gray-800 hover:bg-blue-50 dark:hover:bg-blue-900/20 border border-transparent hover:border-blue-200 dark:hover:border-blue-800 transition-all">
            <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform">
                <x-heroicon-o-envelope-open class="h-6 w-6" />
            </div>
            <span class="text-xs font-medium text-gray-600 dark:text-gray-300 text-center">Izin</span>
        </a>
        
        <a href="{{ route('attendance-history') }}" class="group flex flex-col items-center gap-2 p-3 rounded-xl bg-gray-50 dark:bg-gray-800 hover:bg-purple-50 dark:hover:bg-purple-900/20 border border-transparent hover:border-purple-200 dark:hover:border-purple-800 transition-all">
            <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg text-purple-600 dark:text-purple-400 group-hover:scale-110 transition-transform">
                <x-heroicon-o-clock class="h-6 w-6" />
            </div>
            <span class="text-xs font-medium text-gray-600 dark:text-gray-300 text-center">Riwayat</span>
        </a>

        <a href="{{ route('user.tasks') }}" class="group flex flex-col items-center gap-2 p-3 rounded-xl bg-gray-50 dark:bg-gray-800 hover:bg-orange-50 dark:hover:bg-orange-900/20 border border-transparent hover:border-orange-200 dark:hover:border-orange-800 transition-all">
            <div class="p-2 bg-orange-100 dark:bg-orange-900/30 rounded-lg text-orange-600 dark:text-orange-400 group-hover:scale-110 transition-transform">
                <x-heroicon-o-document-text class="h-6 w-6" />
            </div>
            <span class="text-xs font-medium text-gray-600 dark:text-gray-300 text-center">Tugas</span>
        </a>
      </div>
    </div>
  </div>
</div>

@script
  <script>
    const errorMsg = document.querySelector('#scanner-error');
    getLocation();

    async function getLocation() {
      if (navigator.geolocation) {
        const map = L.map('currentMap');
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
          maxZoom: 21,
        }).addTo(map);
        const options = {
          enableHighAccuracy: true,
          timeout: 10000,
          maximumAge: 0
        };

        navigator.geolocation.watchPosition((position) => {
          console.log(position);
          $wire.$set('currentLiveCoords', [position.coords.latitude, position.coords.longitude]);
          map.setView([
            Number(position.coords.latitude),
            Number(position.coords.longitude),
          ], 13);
          L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
        }, (err) => {
          console.error(`ERROR(${err.code}): ${err.message}`);
          let msg = '{{ __('Please enable your location') }}';
          if (err.code === err.TIMEOUT) {
            msg = 'Terlalu lama mendeteksi lokasi. Pastikan GPS aktif dan sinyal bagus.';
          }
          alert(msg);
          document.querySelector('#scanner-error').innerHTML = msg;
        }, options);
      } else {
        document.querySelector('#scanner-error').innerHTML = "Gagal mendeteksi lokasi";
      }
    }

    if (!$wire.isAbsence) {
      const scanner = new Html5Qrcode('scanner');

      const config = {
        formatsToSupport: [Html5QrcodeSupportedFormats.QR_CODE],
        fps: 15,
        aspectRatio: 1,
        qrbox: {
          width: 280,
          height: 280
        },
        supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA]
      };

      async function startScanning() {
        if (scanner.getState() === Html5QrcodeScannerState.PAUSED) {
          return scanner.resume();
        }
        await scanner.start({
            facingMode: "environment"
          },
          config,
          onScanSuccess,
        );
      }

      async function onScanSuccess(decodedText, decodedResult) {
        console.log(`Code matched = ${decodedText}`, decodedResult);

        if (scanner.getState() === Html5QrcodeScannerState.SCANNING) {
          scanner.pause(true);
        }

        // Check if location is available
        if (!$wire.currentLiveCoords) {
          errorMsg.innerHTML = 'Location not available, please wait for location to load.';
          setTimeout(async () => {
            await startScanning();
          }, 500);
          return;
        }

        if (!(await checkTime())) {
          await startScanning();
          return;
        }

        const result = await $wire.scan(decodedText);

        if (result === true) {
          return onAttendanceSuccess();
        } else if (typeof result === 'string') {
          errorMsg.innerHTML = result;
        }

        setTimeout(async () => {
          await startScanning();
        }, 500);
      }

      async function checkTime() {
        const attendance = await $wire.getAttendance();

        if (attendance) {
          const timeIn = new Date(attendance.time_in).valueOf();
          const diff = (Date.now() - timeIn) / (1000 * 3600);
          const minAttendanceTime = 1;
          console.log(`Difference = ${diff}`);
          if (diff <= minAttendanceTime) {
            const timeIn = new Date(attendance.time_in).toLocaleTimeString([], {
              hour: 'numeric',
              minute: 'numeric',
              second: 'numeric',
              hour12: false,
            });
            const confirmation = confirm(
              `Anda baru saja absen pada ${timeIn}, apakah ingin melanjutkan untuk absen keluar?`
            );
            return confirmation;
          }
        }
        return true;
      }

      function onAttendanceSuccess() {
        scanner.stop();
        errorMsg.innerHTML = '';
        document.querySelector('#scanner-result').classList.remove('hidden');
      }

      const observer = new MutationObserver((mutationList, observer) => {
        const classes = ['text-white', 'bg-blue-500', 'dark:bg-blue-400', 'rounded-md', 'px-3', 'py-1'];
        for (const mutation of mutationList) {
          if (mutation.type === 'childList') {
            const startBtn = document.querySelector('#html5-qrcode-button-camera-start');
            const stopBtn = document.querySelector('#html5-qrcode-button-camera-stop');
            const fileBtn = document.querySelector('#html5-qrcode-button-file-selection');
            const permissionBtn = document.querySelector('#html5-qrcode-button-camera-permission');

            if (startBtn) {
              startBtn.classList.add(...classes);
              stopBtn.classList.add(...classes, 'bg-red-500');
              fileBtn.classList.add(...classes);
            }

            if (permissionBtn)
              permissionBtn.classList.add(...classes);
          }
        }
      });

      observer.observe(document.querySelector('#scanner'), {
        childList: true,
        subtree: true,
      });

      const shift = document.querySelector('#shift');
      const msg = 'Pilih shift terlebih dahulu';
      let isRendered = false;
      setTimeout(() => {
        if (!shift.value) {
          errorMsg.innerHTML = msg;
        } else {
          startScanning();
          isRendered = true;
        }
      }, 1000);
      shift.addEventListener('change', () => {
        if (!isRendered) {
          startScanning();
          isRendered = true;
          errorMsg.innerHTML = '';
        }
        if (!shift.value) {
          scanner.pause(true);
          errorMsg.innerHTML = msg;
        } else if (scanner.getState() === Html5QrcodeScannerState.PAUSED) {
          scanner.resume();
          errorMsg.innerHTML = '';
        }
      });

      const map = L.map('map').setView([
        Number({{ $attendance?->latitude }}),
        Number({{ $attendance?->longitude }}),
      ], 13);
      L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 21,
      }).addTo(map);
      L.marker([
        Number({{ $attendance?->latitude }}),
        Number({{ $attendance?->longitude }}),
      ]).addTo(map);
    }
  </script>
@endscript
