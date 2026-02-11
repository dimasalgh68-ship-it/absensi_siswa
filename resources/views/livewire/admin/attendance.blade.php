@php
  use Illuminate\Support\Carbon;
  $m = Carbon::parse($month);
  $showUserDetail = !$month || $week || $date; // is week or day filter
  $isPerDayFilter = !empty($date); // Changed from isset to !empty
@endphp
<div>
  @pushOnce('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  @endpushOnce
  <h3 class="col-span-2 mb-4 text-lg font-semibold leading-tight text-gray-800 dark:text-gray-200">
    Data Absensi
  </h3>
  <div class="mb-1 text-sm dark:text-white">Filter:</div>
  <div class="mb-4 grid grid-cols-2 flex-wrap items-center gap-5 md:gap-8 lg:flex">
    <div class="flex flex-col gap-3 lg:flex-row lg:items-center">
      <x-label for="month_filter" value="Per Bulan"></x-label>
      <x-input type="month" name="month_filter" id="month_filter" wire:model.live="month" />
    </div>
    <div class="flex flex-col gap-3 lg:flex-row lg:items-center">
      <x-label for="week_filter" value="Per Minggu"></x-label>
      <x-input type="week" name="week_filter" id="week_filter" wire:model.live="week" />
    </div>
    <div class="col-span-2 flex flex-col gap-3 lg:flex-row lg:items-center">
      <x-label for="day_filter" value="Per Hari"></x-label>
      <x-input type="date" name="day_filter" id="day_filter" wire:model.live="date" />
    </div>
    <x-select id="division" wire:model.live="division">
      <option value="">{{ __('Select Division') }}</option>
      @foreach (App\Models\Division::all() as $_division)
        <option value="{{ $_division->id }}" {{ $_division->id == $division ? 'selected' : '' }}>
          {{ $_division->name }}
        </option>
      @endforeach
    </x-select>
    <x-select id="jobTitle" wire:model.live="jobTitle">
      <option value="">{{ __('Select Job Title') }}</option>
      @foreach (App\Models\JobTitle::all() as $_jobTitle)
        <option value="{{ $_jobTitle->id }}" {{ $_jobTitle->id == $jobTitle ? 'selected' : '' }}>
          {{ $_jobTitle->name }}
        </option>
      @endforeach
    </x-select>
    <x-select id="education" wire:model.live="education">
      <option value="">Pilih Kelas</option>
      @foreach (App\Models\Education::all() as $_education)
        <option value="{{ $_education->id }}" {{ $_education->id == $education ? 'selected' : '' }}>
          {{ $_education->name }}
        </option>
      @endforeach
    </x-select>
    <div class="col-span-2 flex items-center gap-2 lg:w-96">
      <x-input type="text" class="w-full" name="search" id="search" wire:model.live.debounce.500ms="search"
        placeholder="{{ __('Search') }}" />
      <x-button type="button" wire:click="$refresh" wire:loading.attr="disabled">{{ __('Search') }}</x-button>
      @if ($search)
        <x-secondary-button type="button" wire:click="$set('search', '')" wire:loading.attr="disabled">
          {{ __('Reset') }}
        </x-secondary-button>
      @endif
    </div>
    <div class="lg:hidden text-gray-500 text-xs">Geser ke kanan untuk melihat aksi & detail -></div>
    <x-secondary-button
      href="{{ route('admin.attendances.report', ['month' => $month, 'week' => $week, 'date' => $date, 'division' => $division, 'jobTitle' => $jobTitle]) }}"
      class="flex justify-center gap-2">
      Cetak Laporan
      <x-heroicon-o-printer class="h-5 w-5" />
    </x-secondary-button>
  </div>

  {{-- Bulk Action Bar --}}
  @if (!empty($selectedRows))
    <div class="mb-4 p-4 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800 rounded-lg flex flex-col md:flex-row items-center justify-between gap-4 animate-in fade-in slide-in-from-top-2 duration-300">
      <div class="flex items-center gap-2">
        <span class="text-sm font-medium text-indigo-700 dark:text-indigo-300">
          {{ count($selectedRows) }} Siswa Terpilih
        </span>
        <button wire:click="$set('selectedRows', [])" class="text-xs text-indigo-500 hover:text-indigo-700 dark:hover:text-indigo-400 underline">
          Batal
        </button>
      </div>
      
      <div class="flex flex-wrap items-center gap-2">
        <span class="text-sm text-gray-600 dark:text-gray-400 mr-2">Set Status:</span>
        <x-button type="button" wire:click="batchSetStatus('present')" wire:loading.attr="disabled" class="bg-green-600 hover:bg-green-700">Hadir</x-button>
        <x-button type="button" wire:click="batchSetStatus('late')" wire:loading.attr="disabled" class="bg-amber-500 hover:bg-amber-600">Terlambat</x-button>
        <x-button type="button" wire:click="batchSetStatus('excused')" wire:loading.attr="disabled" class="bg-blue-600 hover:bg-blue-700">Izin</x-button>
        <x-button type="button" wire:click="batchSetStatus('sick')" wire:loading.attr="disabled" class="bg-gray-600 hover:bg-gray-700">Sakit</x-button>
        <x-danger-button type="button" wire:click="batchSetStatus('absent')" wire:loading.attr="disabled">Alpha</x-danger-button>
        
        <div wire:loading wire:target="batchSetStatus" class="ml-2">
          <svg class="animate-spin h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
        </div>
      </div>
    </div>
  @endif

  <div class="overflow-x-scroll rounded-lg border border-gray-200 dark:border-gray-700">
    <table class="w-full divide-y divide-gray-200 dark:divide-gray-700 border-collapse">
      <thead class="bg-gray-50 dark:bg-gray-900/50">
        <tr>
          @if ($isPerDayFilter)
            <th scope="col" class="px-3 py-3 text-center">
              <input type="checkbox" 
                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900"
                wire:click="toggleSelectAll($event.target.checked)"
              >
            </th>
          @endif
          <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
            {{ $showUserDetail ? __('Name') : __('Name') . '/' . __('Date') }}
          </th>
          @if ($showUserDetail)
            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
              {{ __('NISN') }}
            </th>
            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
              {{ __('Division') }}
            </th>
            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
              {{ __('Job Title') }}
            </th>
            @if ($isPerDayFilter)
              <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
                {{ __('Shift') }}
              </th>
            @endif
          @endif
          @foreach ($dates as $date)
            @php
              if (!$isPerDayFilter && $date->isSunday()) {
                  // Minggu merah
                  $textClass = 'text-red-500 dark:text-red-300';
              } elseif (!$isPerDayFilter && $date->isFriday()) {
                  // Jumat hijau
                  $textClass = 'text-green-500 dark:text-green-300';
              } else {
                  $textClass = 'text-gray-500 dark:text-gray-300';
              }
            @endphp
            <th scope="col"
              class="{{ $textClass }} text-nowrap border border-gray-300 px-1 py-3 text-center text-xs font-medium dark:border-gray-600">
              @if ($isPerDayFilter)
                Status
              @else
                {{ $date->format('d/m') }}
              @endif
            </th>
          @endforeach
          @if ($isPerDayFilter)
            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
              {{ __('Time In') }}
            </th>
            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
              {{ __('Time Out') }}
            </th>
            <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300">
              Verifikasi
            </th>
          @endif
          @if (!$isPerDayFilter)
            @foreach (['H', 'T', 'I', 'S', 'A'] as $_st)
              <th scope="col"
                class="text-nowrap border border-gray-300 px-1 py-3 text-center text-xs font-medium text-gray-500 dark:border-gray-600 dark:text-gray-300">
                {{ $_st }}
              </th>
            @endforeach
          @endif
          @if ($isPerDayFilter)
            <th scope="col" class="relative">
              <span class="sr-only">Actions</span>
            </th>
          @endif
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
        @php
          $class = 'cursor-pointer px-4 py-3 text-sm font-medium text-gray-900 dark:text-white';
        @endphp
        @foreach ($employees as $employee)
          @php
            $attendances = $employee->attendances;
          @endphp
          <tr wire:key="{{ $employee->id }}" class="group transition-colors {{ in_array($employee->id, $selectedRows) ? 'bg-indigo-50/50 dark:bg-indigo-900/10' : '' }}">
            @if ($isPerDayFilter)
              <td class="px-3 py-3 text-center">
                <input type="checkbox" wire:model.live="selectedRows" value="{{ $employee->id }}"
                  class="row-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900">
              </td>
            @endif
            {{-- Detail siswa --}}
            <td class="{{ $class }} text-nowrap group-hover:bg-gray-50 dark:group-hover:bg-gray-700/50">
              {{ $employee->name }}
            </td>
            @if ($showUserDetail)
              <td class="{{ $class }} group-hover:bg-gray-100 dark:group-hover:bg-gray-700">
                {{ $employee->nisn }}
              </td>
              <td class="{{ $class }} text-nowrap group-hover:bg-gray-100 dark:group-hover:bg-gray-700">
                {{ $employee->division?->name ?? '-' }}
              </td>
              <td class="{{ $class }} text-nowrap group-hover:bg-gray-100 dark:group-hover:bg-gray-700">
                {{ $employee->jobTitle?->name ?? '-' }}
              </td>
              @if ($isPerDayFilter)
                @php
                  $attendance = $employee->attendances->isEmpty() ? null : $employee->attendances->first();
                  $timeIn = $attendance ? $attendance['time_in'] : null;
                  $timeOut = $attendance ? $attendance['time_out'] : null;
                @endphp
                <td class="{{ $class }} text-nowrap group-hover:bg-gray-100 dark:group-hover:bg-gray-700">
                  {{ $attendance['shift'] ?? '-' }}
                </td>
              @endif
            @endif

            {{-- Absensi --}}
            @php
              $presentCount = 0;
              $lateCount = 0;
              $excusedCount = 0;
              $sickCount = 0;
              $absentCount = 0;
            @endphp
            @foreach ($dates as $date)
              @php
                $isWeekend = $date->isWeekend();
                $attendance = $attendances->firstWhere(fn($v, $k) => $v['date'] === $date->format('Y-m-d'));
                $status = ($attendance ?? [
                    'status' => $isWeekend || !$date->isPast() ? '-' : 'absent',
                ])['status'];
                switch ($status) {
                    case 'present':
                        $shortStatus = 'H';
                        $bgColor =
                            'bg-green-200 dark:bg-green-800 hover:bg-green-300 dark:hover:bg-green-700 border border-green-300 dark:border-green-600';
                        $presentCount++;
                        break;
                    case 'late':
                        $shortStatus = 'T';
                        $bgColor =
                            'bg-amber-200 dark:bg-amber-800 hover:bg-amber-300 dark:hover:bg-amber-700 border border-amber-300 dark:border-amber-600';
                        $lateCount++;
                        break;
                    case 'excused':
                        $shortStatus = 'I';
                        $bgColor =
                            'bg-blue-200 dark:bg-blue-800 hover:bg-blue-300 dark:hover:bg-blue-700 border border-blue-300 dark:border-blue-600';
                        $excusedCount++;
                        break;
                    case 'sick':
                        $shortStatus = 'S';
                        $bgColor =
                            'hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-600';
                        $sickCount++;
                        break;
                    case 'absent':
                        $shortStatus = 'A';
                        $bgColor =
                            'bg-red-200 dark:bg-red-800 hover:bg-red-300 dark:hover:bg-red-700 border border-red-300 dark:border-red-600';
                        $absentCount++;
                        break;
                    default:
                        $shortStatus = '-';
                        $bgColor =
                            'hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-600';
                        break;
                }
              @endphp
              @php
                $canClick = ($attendance && ($attendance['attachment'] || $attendance['note'] || $attendance['coordinates'] || isset($attendance['face_photo_url']))) || !$isPerDayFilter;
              @endphp
              <td
                class="{{ $bgColor }} cursor-pointer text-center text-sm font-medium text-gray-900 dark:text-white relative p-0"
                wire:key="cell-{{ $employee->id }}-{{ $date->format('Y-m-d') }}">
                @if ($attendance)
                  <button class="w-full h-full py-3 px-1 block" 
                    wire:click="show({{ $attendance['id'] }})"
                    @if(isset($attendance['lat'])) onclick="setLocation({{ $attendance['lat'] }}, {{ $attendance['lng'] }})" @endif>
                    {{ $isPerDayFilter ? __($status) : $shortStatus }}
                    @if(isset($attendance['validation_method']) && $attendance['validation_method'] === 'face')
                      <span class="absolute top-1 right-1 w-2 h-2 bg-green-500 rounded-full" title="Face Verified"></span>
                    @endif
                  </button>
                @else
                  <div class="w-full h-full py-3 px-1">
                    {{ $isPerDayFilter ? __($status) : $shortStatus }}
                  </div>
                @endif
              </td>
            @endforeach

            {{-- Waktu masuk/keluar --}}
            @if ($isPerDayFilter)
              <td class="{{ $class }} group-hover:bg-gray-100 dark:group-hover:bg-gray-700">
                {{ $timeIn ?? '-' }}
              </td>
              <td class="{{ $class }} group-hover:bg-gray-100 dark:group-hover:bg-gray-700">
                {{ $timeOut ?? '-' }}
              </td>
              <td class="{{ $class }} text-center group-hover:bg-gray-100 dark:group-hover:bg-gray-700">
                @php
                  $attendance = $employee->attendances->isEmpty() ? null : $employee->attendances->first();
                @endphp
                @if($attendance && isset($attendance['validation_method']) && $attendance['validation_method'] === 'face')
                  <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Face
                  </span>
                @else
                  <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                    Manual
                  </span>
                @endif
              </td>
            @endif

            {{-- Total --}}
            @if (!$isPerDayFilter)
              @foreach ([$presentCount, $lateCount, $excusedCount, $sickCount, $absentCount] as $statusCount)
                <td
                  class="cursor-pointer border border-gray-300 px-1 py-3 text-center text-sm font-medium text-gray-900 group-hover:bg-gray-100 dark:border-gray-600 dark:text-white dark:group-hover:bg-gray-700">
                  {{ $statusCount }}
                </td>
              @endforeach
            @endif

            {{-- Action --}}
            @if ($isPerDayFilter)
              @php
                $attendance = $employee->attendances->isEmpty() ? null : $employee->attendances->first();
              @endphp
              <td class="cursor-pointer text-center text-sm font-medium text-gray-900 group-hover:bg-gray-50 dark:text-white dark:group-hover:bg-gray-700/50">
                <div class="flex items-center justify-center gap-2">
                  @if ($attendance)
                    {{-- Siswa sudah absen - tampilkan dropdown status untuk quick edit --}}
                    <div class="flex items-center gap-2">
                      <div class="relative" wire:key="status-select-{{ $attendance['id'] }}">
                        <select 
                          onchange="if(confirm('Ubah status absensi?')) { @this.updateStatus({{ $attendance['id'] }}, this.value) } else { this.value = '{{ $attendance['status'] }}' }"
                          class="text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                          wire:loading.attr="disabled"
                          wire:target="updateStatus">
                          <option value="present" {{ $attendance['status'] == 'present' ? 'selected' : '' }}>Hadir</option>
                          <option value="late" {{ $attendance['status'] == 'late' ? 'selected' : '' }}>Terlambat</option>
                          <option value="excused" {{ $attendance['status'] == 'excused' ? 'selected' : '' }}>Izin</option>
                          <option value="sick" {{ $attendance['status'] == 'sick' ? 'selected' : '' }}>Sakit</option>
                          <option value="absent" {{ $attendance['status'] == 'absent' ? 'selected' : '' }}>Alpha</option>
                        </select>
                        <div wire:loading wire:target="updateStatus" class="absolute inset-0 bg-white/50 dark:bg-gray-900/50 flex items-center justify-center rounded-md">
                          <svg class="animate-spin h-4 w-4 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                          </svg>
                        </div>
                      </div>
                      
                      <x-secondary-button type="button" wire:click="edit({{ $attendance['id'] }})" title="Edit Detail">
                        <x-heroicon-o-pencil class="h-4 w-4" />
                      </x-secondary-button>
                      
                      @if ($attendance['attachment'] || $attendance['note'] || $attendance['coordinates'] || isset($attendance['face_photo_url']))
                        <x-secondary-button type="button" wire:click="show({{ $attendance['id'] }})"
                          onclick="setLocation({{ $attendance['lat'] ?? 0 }}, {{ $attendance['lng'] ?? 0 }})"
                          title="Detail">
                          <x-heroicon-o-eye class="h-4 w-4" />
                        </x-secondary-button>
                      @endif
                      
                      <x-danger-button type="button" 
                        wire:click="deleteAttendance({{ $attendance['id'] }})"
                        wire:confirm="Hapus data absensi ini?"
                        title="Hapus">
                        <x-heroicon-o-trash class="h-4 w-4" />
                      </x-danger-button>
                    </div>
                  @else
                    {{-- Siswa belum absen - tampilkan dropdown untuk set status --}}
                    <div class="flex items-center justify-center gap-2">
                      <div class="relative" wire:key="create-select-{{ $employee->id }}">
                        <select 
                          onchange="if(this.value && confirm('Tambahkan status absensi?')) { @this.createAttendanceWithStatus('{{ $employee->id }}', this.value) } this.value = ''"
                          class="text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                          wire:loading.attr="disabled"
                          wire:target="createAttendanceWithStatus">
                          <option value="">-- Set Status --</option>
                          <option value="present">Hadir</option>
                          <option value="late">Terlambat</option>
                          <option value="excused">Izin</option>
                          <option value="sick">Sakit</option>
                          <option value="absent">Alpha</option>
                        </select>
                        <div wire:loading wire:target="createAttendanceWithStatus" class="absolute inset-0 bg-white/50 dark:bg-gray-900/50 flex items-center justify-center rounded-md">
                          <svg class="animate-spin h-4 w-4 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                          </svg>
                        </div>
                      </div>

                    </div>
                  @endif
                </div>
              </td>
            @endif
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @if ($employees->isEmpty())
    <div class="my-2 text-center text-sm font-medium text-gray-900 dark:text-gray-100">
      Tidak ada data
    </div>
  @endif
  <div class="mt-3">
    {{ $employees->links() }}
  </div>

  <x-attendance-detail-modal :current-attendance="$currentAttendance" />
  @stack('attendance-detail-scripts')
</div>

  {{-- Edit Attendance Modal --}}
  <x-dialog-modal wire:model="showEditModal">
    <x-slot name="title">
      Edit Data Absensi
    </x-slot>

    <x-slot name="content">
      <div class="space-y-4">
        {{-- Date --}}
        <div>
          <x-label for="editDate" value="Tanggal" />
          <x-input type="date" id="editDate" wire:model="editDate" class="mt-1 block w-full" />
          <x-input-error for="editDate" class="mt-2" />
        </div>

        {{-- Time In --}}
        <div>
          <x-label for="editTimeIn" value="Waktu Masuk" />
          <x-input type="time" id="editTimeIn" wire:model="editTimeIn" class="mt-1 block w-full" />
          <x-input-error for="editTimeIn" class="mt-2" />
        </div>

        {{-- Time Out --}}
        <div>
          <x-label for="editTimeOut" value="Waktu Keluar" />
          <x-input type="time" id="editTimeOut" wire:model="editTimeOut" class="mt-1 block w-full" />
          <x-input-error for="editTimeOut" class="mt-2" />
        </div>

        {{-- Status --}}
        <div>
          <x-label for="editStatus" value="Status" />
          <x-select id="editStatus" wire:model="editStatus" class="mt-1 block w-full">
            <option value="">Pilih Status</option>
            <option value="present">Hadir</option>
            <option value="late">Terlambat</option>
            <option value="excused">Izin</option>
            <option value="sick">Sakit</option>
            <option value="absent">Alpha</option>
          </x-select>
          <x-input-error for="editStatus" class="mt-2" />
        </div>

        {{-- Schedule --}}
        <div>
          <x-label for="editScheduleId" value="Jadwal" />
          <x-select id="editScheduleId" wire:model="editScheduleId" class="mt-1 block w-full">
            <option value="">Pilih Jadwal</option>
            @foreach ($schedules as $schedule)
              <option value="{{ $schedule->id }}">{{ $schedule->name }} ({{ $schedule->start_time }} - {{ $schedule->end_time }})</option>
            @endforeach
          </x-select>
          <x-input-error for="editScheduleId" class="mt-2" />
        </div>

        {{-- Note --}}
        <div>
          <x-label for="editNote" value="Keterangan" />
          <textarea id="editNote" wire:model="editNote" 
            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
            rows="3"></textarea>
          <x-input-error for="editNote" class="mt-2" />
        </div>
      </div>
    </x-slot>

    <x-slot name="footer">
      <x-secondary-button wire:click="closeEditModal" wire:loading.attr="disabled">
        Batal
      </x-secondary-button>

      <x-button class="ml-3" wire:click="updateAttendance" wire:loading.attr="disabled">
        Simpan Perubahan
      </x-button>
    </x-slot>
  </x-dialog-modal>

  {{-- Create Attendance Modal --}}
  <x-dialog-modal wire:model="showCreateModal">
    <x-slot name="title">
      Tambah Absensi Manual
    </x-slot>

    <x-slot name="content">
      <div class="space-y-4">
        {{-- Info --}}
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3">
          <p class="text-sm text-blue-800 dark:text-blue-200">
            <strong>ℹ️ Info:</strong> Anda sedang menambahkan data absensi secara manual untuk siswa yang belum melakukan absensi.
          </p>
        </div>

        {{-- Date --}}
        <div>
          <x-label for="createDate" value="Tanggal *" />
          <x-input type="date" id="createDate" wire:model="createDate" class="mt-1 block w-full" required />
          <x-input-error for="createDate" class="mt-2" />
        </div>

        {{-- Time In --}}
        <div>
          <x-label for="createTimeIn" value="Waktu Masuk" />
          <x-input type="time" id="createTimeIn" wire:model="createTimeIn" class="mt-1 block w-full" />
          <x-input-error for="createTimeIn" class="mt-2" />
          <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Kosongkan jika tidak ada waktu masuk</p>
        </div>

        {{-- Time Out --}}
        <div>
          <x-label for="createTimeOut" value="Waktu Keluar" />
          <x-input type="time" id="createTimeOut" wire:model="createTimeOut" class="mt-1 block w-full" />
          <x-input-error for="createTimeOut" class="mt-2" />
          <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Kosongkan jika belum pulang</p>
        </div>

        {{-- Status --}}
        <div>
          <x-label for="createStatus" value="Status *" />
          <x-select id="createStatus" wire:model="createStatus" class="mt-1 block w-full" required>
            <option value="present">Hadir</option>
            <option value="late">Terlambat</option>
            <option value="excused">Izin</option>
            <option value="sick">Sakit</option>
            <option value="absent">Alpha</option>
          </x-select>
          <x-input-error for="createStatus" class="mt-2" />
        </div>

        {{-- Schedule --}}
        <div>
          <x-label for="createScheduleId" value="Jadwal" />
          <x-select id="createScheduleId" wire:model="createScheduleId" class="mt-1 block w-full">
            <option value="">Pilih Jadwal</option>
            @foreach ($schedules as $schedule)
              <option value="{{ $schedule->id }}">{{ $schedule->name }} ({{ $schedule->start_time }} - {{ $schedule->end_time }})</option>
            @endforeach
          </x-select>
          <x-input-error for="createScheduleId" class="mt-2" />
        </div>

        {{-- Note --}}
        <div>
          <x-label for="createNote" value="Keterangan" />
          <textarea id="createNote" wire:model="createNote" 
            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
            rows="3"
            placeholder="Contoh: Absen manual karena lupa scan wajah"></textarea>
          <x-input-error for="createNote" class="mt-2" />
        </div>
      </div>
    </x-slot>

    <x-slot name="footer">
      <x-secondary-button wire:click="closeCreateModal" wire:loading.attr="disabled">
        Batal
      </x-secondary-button>

      <x-button class="ml-3" wire:click="createAttendance" wire:loading.attr="disabled">
        Tambah Absensi
      </x-button>
    </x-slot>
  </x-dialog-modal>
