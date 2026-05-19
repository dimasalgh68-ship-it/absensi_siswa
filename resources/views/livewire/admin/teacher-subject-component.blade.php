<div>
    <div class="mb-6 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h3 class="text-lg font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Mata Pelajaran Guru
            </h3>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Kelola mata pelajaran yang diajarkan oleh setiap guru
            </p>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center">
        <div class="flex-1">
            <input 
                type="text" 
                wire:model.live="search" 
                placeholder="Cari nama guru..."
                class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400"
            >
        </div>
        <div class="flex gap-2">
            <button 
                wire:click="setSortBy('name')"
                class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 {{ $sortBy === 'name' ? 'bg-blue-50 border-blue-500 text-blue-700 dark:bg-blue-900 dark:border-blue-500 dark:text-blue-200' : '' }}"
            >
                Nama {{ $sortBy === 'name' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' }}
            </button>
            <button 
                wire:click="setSortBy('subjects_count')"
                class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 {{ $sortBy === 'subjects_count' ? 'bg-blue-50 border-blue-500 text-blue-700 dark:bg-blue-900 dark:border-blue-500 dark:text-blue-200' : '' }}"
            >
                Jumlah {{ $sortBy === 'subjects_count' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' }}
            </button>
        </div>
    </div>

    <!-- Teachers Table -->
    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
        <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
                        Nama Guru
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
                        Mata Pelajaran
                    </th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300">
                        Jumlah
                    </th>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Actions</span>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                @forelse ($teachers as $teacher)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $teacher->user->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                            @if ($teacher->subjects->count() > 0)
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($teacher->subjects as $subject)
                                        <div class="inline-flex items-center gap-2 rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            <span>{{ $subject->name }}</span>
                                            <button 
                                                wire:click="removeSubjectQuick({{ $teacher->id }}, {{ $subject->id }})"
                                                class="ml-1 text-blue-600 hover:text-blue-800 dark:text-blue-300 dark:hover:text-blue-100"
                                                title="Hapus mata pelajaran"
                                            >
                                                ×
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <span class="inline-block rounded-full bg-gray-100 px-3 py-1 text-sm text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                    Belum ada mata pelajaran
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center text-sm font-medium text-gray-900 dark:text-white">
                            <span class="inline-flex items-center justify-center rounded-full bg-gray-100 px-3 py-1 text-sm font-semibold text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                {{ $teacher->subjects->count() }}
                            </span>
                        </td>
                        <td class="relative flex justify-end gap-2 px-6 py-4">
                            <x-button wire:click="selectTeacher({{ $teacher->id }})">
                                Kelola
                            </x-button>
                            @if ($teacher->subjects->count() > 0)
                                <x-danger-button 
                                    wire:click="removeAllSubjects({{ $teacher->id }})"
                                    wire:confirm="Hapus semua mata pelajaran dari guru ini?"
                                >
                                    Hapus Semua
                                </x-danger-button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="mb-2 h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m0 0h6m-6-6H6m0 0H0"></path>
                                </svg>
                                <p>Tidak ada data guru</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal untuk mengelola mata pelajaran guru -->
    <x-dialog-modal wire:model="showModal" maxWidth="2xl">
        <x-slot name="title">
            Kelola Mata Pelajaran - {{ $selectedTeacher?->user?->name ?? 'Guru' }}
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Pilih mata pelajaran yang diajarkan oleh guru ini:
                </p>

                <div class="grid grid-cols-2 gap-4 max-h-96 overflow-y-auto rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-600 dark:bg-gray-900">
                    @forelse ($availableSubjects as $subject)
                        <label class="flex items-center space-x-3 cursor-pointer rounded-lg p-2 hover:bg-gray-100 dark:hover:bg-gray-800">
                            <input 
                                type="checkbox" 
                                wire:model.number="selectedSubjects" 
                                value="{{ $subject['id'] }}"
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700"
                            >
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ $subject['name'] }}
                            </span>
                        </label>
                    @empty
                        <p class="col-span-2 text-center text-sm text-gray-500 dark:text-gray-400">
                            Tidak ada mata pelajaran tersedia
                        </p>
                    @endforelse
                </div>

                @if ($selectedTeacher?->subjects->count() > 0)
                    <div class="rounded-lg bg-blue-50 p-3 dark:bg-blue-900">
                        <p class="text-sm text-blue-800 dark:text-blue-200">
                            <strong>Mata pelajaran saat ini:</strong>
                            {{ $selectedTeacher->subjects->pluck('name')->join(', ') }}
                        </p>
                    </div>
                @endif
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ml-2" wire:click="saveSubjects" wire:loading.attr="disabled">
                {{ __('Save') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
