<div>
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
            Kelola Event Akademik
        </h3>
        <x-button wire:click="create">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Event
        </x-button>
    </div>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Judul</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tipe</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($events as $event)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 rounded-full" style="background-color: {{ $event->color }};"></div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $event->title }}</div>
                                    @if($event->description)
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($event->description, 50) }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                            {{ $event->start_date->format('d M Y') }}
                            @if($event->start_date != $event->end_date)
                                <br>- {{ $event->end_date->format('d M Y') }}
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                {{ ucfirst($event->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($event->is_active)
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400">Aktif</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <button wire:click="edit({{ $event->id }})" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 mr-3">
                                Edit
                            </button>
                            <button wire:click="delete({{ $event->id }})" onclick="return confirm('Yakin ingin menghapus event ini?')" class="text-red-600 hover:text-red-900 dark:text-red-400">
                                Hapus
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            Belum ada event
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4">
            {{ $events->links() }}
        </div>
    </div>

    {{-- Modal --}}
    <x-dialog-modal wire:model="showModal">
        <x-slot name="title">
            {{ $editingId ? 'Edit Event' : 'Tambah Event' }}
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <div>
                    <x-label for="title" value="Judul Event *" />
                    <x-input type="text" id="title" wire:model="title" class="mt-1 block w-full" />
                    <x-input-error for="title" class="mt-2" />
                </div>

                <div>
                    <x-label for="description" value="Deskripsi" />
                    <textarea id="description" wire:model="description" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"></textarea>
                    <x-input-error for="description" class="mt-2" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-label for="start_date" value="Tanggal Mulai *" />
                        <x-input type="date" id="start_date" wire:model="start_date" class="mt-1 block w-full" />
                        <x-input-error for="start_date" class="mt-2" />
                    </div>

                    <div>
                        <x-label for="end_date" value="Tanggal Selesai *" />
                        <x-input type="date" id="end_date" wire:model="end_date" class="mt-1 block w-full" />
                        <x-input-error for="end_date" class="mt-2" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-label for="type" value="Tipe Event *" />
                        <select id="type" wire:model="type" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="holiday">Libur</option>
                            <option value="exam">Ujian</option>
                            <option value="event">Event</option>
                            <option value="meeting">Rapat</option>
                            <option value="other">Lainnya</option>
                        </select>
                        <x-input-error for="type" class="mt-2" />
                    </div>

                    <div>
                        <x-label for="color" value="Warna *" />
                        <input type="color" id="color" wire:model="color" class="mt-1 block w-full h-10 border-gray-300 dark:border-gray-700 rounded-md" />
                        <x-input-error for="color" class="mt-2" />
                    </div>
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="is_active" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Event Aktif</span>
                    </label>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal" wire:loading.attr="disabled">
                Batal
            </x-secondary-button>

            <x-button class="ml-3" wire:click="save" wire:loading.attr="disabled">
                {{ $editingId ? 'Update' : 'Simpan' }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
