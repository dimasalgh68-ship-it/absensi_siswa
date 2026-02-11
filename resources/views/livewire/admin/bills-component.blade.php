<div class="min-h-screen bg-gray-50 dark:bg-gray-900 p-4 sm:p-6 lg:p-8">
    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="mb-6 bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 p-4 rounded-r-xl shadow-sm flex items-center justify-between animate-fade-in-down">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-green-700 dark:text-green-300 font-medium">{{ session('message') }}</span>
            </div>
            <button type="button" class="text-green-500 hover:text-green-700" onclick="this.parentElement.remove()">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif

    <!-- Header & Stats -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-400 dark:to-indigo-400">
                    Manajemen Tagihan
                </h2>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Kelola tagihan siswa dan pantau status pembayaran.
                </p>
            </div>
            <x-button wire:click="openCreateModal" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 border-0 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                {{ __('Buat Tagihan Baru') }}
            </x-button>
        </div>

        <!-- Search and Filter -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 flex flex-col sm:flex-row gap-4">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <x-input
                    wire:model.live.debounce.300ms="search"
                    type="text"
                    placeholder="{{ __('Cari nama atau NISN siswa...') }}"
                    class="w-full pl-10 rounded-xl border-gray-200 dark:border-gray-700 focus:ring-blue-500 focus:border-blue-500"
                />
            </div>
            <div class="sm:w-48">
                <x-select wire:model.live="statusFilter" class="w-full rounded-xl border-gray-200 dark:border-gray-700 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">{{ __('Semua Status') }}</option>
                    <option value="unpaid">{{ __('Belum Lunas') }}</option>
                    <option value="paid">{{ __('Lunas') }}</option>
                </x-select>
            </div>
        </div>
    </div>

    <!-- Bills Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($bills as $bill)
            <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col h-full">
                <div class="p-6 flex-1 flex flex-col">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-300 font-bold">
                                {{ substr($bill->user->name, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white line-clamp-1">{{ $bill->user->name }}</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $bill->user->nip }}</p>
                            </div>
                        </div>
                        @if($bill->status === 'paid')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800">
                                {{ __('Lunas') }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800">
                                {{ __('Belum Lunas') }}
                            </span>
                        @endif
                    </div>

                    <div class="mb-4">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
                            {{ $bill->description }}
                        </h3>
                        <div class="text-2xl font-extrabold text-gray-900 dark:text-white">
                            Rp {{ number_format($bill->amount, 0, ',', '.') }}
                        </div>
                    </div>

                    <div class="mt-auto space-y-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Tenggat:</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $bill->due_date ? $bill->due_date->format('d/m/Y') : '-' }}</span>
                        </div>
                        @if($bill->paid_at)
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Dibayar:</span>
                                <span class="font-medium text-green-600 dark:text-green-400">{{ $bill->paid_at->format('d/m/Y H:i') }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 flex flex-wrap gap-2">
                        @if($bill->status === 'unpaid')
                            <button
                                wire:click="markAsPaid({{ $bill->id }})"
                                wire:confirm="{{ __('Tandai sebagai lunas?') }}"
                                class="flex-1 inline-flex justify-center items-center px-3 py-2 bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/40 text-green-700 dark:text-green-400 text-xs font-semibold rounded-lg transition-colors border border-green-200 dark:border-green-800"
                            >
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ __('Lunas') }}
                            </button>
                        @endif
                        
                        @if($bill->proof_path)
                            <a href="{{ Storage::url($bill->proof_path) }}" target="_blank" class="flex-1 inline-flex justify-center items-center px-3 py-2 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/40 text-blue-700 dark:text-blue-400 text-xs font-semibold rounded-lg transition-colors border border-blue-200 dark:border-blue-800">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                {{ __('Bukti') }}
                            </a>
                        @endif

                        <button
                            wire:click="openEditModal({{ $bill->id }})"
                            class="p-2 text-gray-500 hover:text-yellow-600 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 rounded-lg transition-all"
                            title="Edit"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <button
                            wire:click="deleteBill({{ $bill->id }})"
                            wire:confirm="{{ __('Hapus tagihan ini?') }}"
                            class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all"
                            title="Hapus"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="flex flex-col items-center justify-center py-20 bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Belum ada tagihan') }}</h3>
                    <p class="mt-2 text-gray-500 dark:text-gray-400 max-w-sm mx-auto">
                        {{ __('Silakan buat tagihan baru untuk siswa.') }}
                    </p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($bills->hasPages())
        <div class="mt-8">
            {{ $bills->links() }}
        </div>
    @endif

    <!-- Create/Edit Modal -->
    <x-dialog-modal wire:model="showCreateModal">
        <x-slot name="title">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                {{ __('Buat Tagihan Baru') }}
            </h3>
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <div>
                    <x-label for="user_id" value="{{ __('Siswa') }}" />
                    <x-select wire:model="user_id" id="user_id" class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">{{ __('Pilih Siswa') }}</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->nip }})</option>
                        @endforeach
                    </x-select>
                    <x-input-error for="user_id" />
                </div>

                <div>
                    <x-label for="description" value="{{ __('Keterangan') }}" />
                    <x-textarea wire:model="description" id="description" class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500" rows="3" placeholder="Contoh: SPP Kelas 10 2024" />
                    <x-input-error for="description" />
                </div>

                <div>
                    <x-label for="amount" value="{{ __('Jumlah (Rp)') }}" />
                    <x-input wire:model="amount" id="amount" type="number" step="0.01" class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="0" />
                    <x-input-error for="amount" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-label for="status" value="{{ __('Status') }}" />
                        <x-select wire:model="status" id="status" class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="unpaid">{{ __('Belum Lunas') }}</option>
                            <option value="paid">{{ __('Lunas') }}</option>
                        </x-select>
                        <x-input-error for="status" />
                    </div>

                    <div>
                        <x-label for="due_date" value="{{ __('Tenggat Waktu') }}" />
                        <x-input wire:model="due_date" id="due_date" type="date" class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
                        <x-input-error for="due_date" />
                    </div>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeCreateModal" class="rounded-xl">
                {{ __('Batal') }}
            </x-secondary-button>

            <x-button wire:click="createBill" class="ml-3 bg-blue-600 hover:bg-blue-700 border-0 rounded-xl shadow-md">
                {{ __('Simpan') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <x-dialog-modal wire:model="showEditModal">
        <x-slot name="title">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                {{ __('Edit Tagihan') }}
            </h3>
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <div>
                    <x-label for="edit_user_id" value="{{ __('Siswa') }}" />
                    <x-select wire:model="user_id" id="edit_user_id" class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">{{ __('Pilih Siswa') }}</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->nip }})</option>
                        @endforeach
                    </x-select>
                    <x-input-error for="user_id" />
                </div>

                <div>
                    <x-label for="edit_description" value="{{ __('Keterangan') }}" />
                    <x-textarea wire:model="description" id="edit_description" class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500" rows="3" />
                    <x-input-error for="description" />
                </div>

                <div>
                    <x-label for="edit_amount" value="{{ __('Jumlah (Rp)') }}" />
                    <x-input wire:model="amount" id="edit_amount" type="number" step="0.01" class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
                    <x-input-error for="amount" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-label for="edit_status" value="{{ __('Status') }}" />
                        <x-select wire:model="status" id="edit_status" class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="unpaid">{{ __('Belum Lunas') }}</option>
                            <option value="paid">{{ __('Lunas') }}</option>
                        </x-select>
                        <x-input-error for="status" />
                    </div>

                    <div>
                        <x-label for="edit_due_date" value="{{ __('Tenggat Waktu') }}" />
                        <x-input wire:model="due_date" id="edit_due_date" type="date" class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
                        <x-input-error for="due_date" />
                    </div>
                </div>

                <div>
                    <x-label for="edit_proof" value="{{ __('Bukti Transfer (Opsional)') }}" />
                    <x-input wire:model="proof" id="edit_proof" type="file" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" accept="image/*,.pdf" />
                    <x-input-error for="proof" />
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeEditModal" class="rounded-xl">
                {{ __('Batal') }}
            </x-secondary-button>

            <x-button wire:click="updateBill" class="ml-3 bg-blue-600 hover:bg-blue-700 border-0 rounded-xl shadow-md">
                {{ __('Update') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
