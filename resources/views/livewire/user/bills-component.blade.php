<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 p-4 sm:p-6 lg:p-8">
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

    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-400 dark:to-indigo-400">
                Tagihan Saya
            </h2>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Kelola pembayaran dan riwayat transaksi Anda.
            </p>
        </div>
    </div>

    <!-- Bills List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($bills as $bill)
            <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col h-full transform hover:-translate-y-1">
                <div class="p-6 flex-1 flex flex-col">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold shadow-md">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white line-clamp-1">{{ auth()->user()->name }}</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->nisn}}</p>
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

                    <div class="mb-6">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Tagihan</p>
                        <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white">
                            Rp {{ number_format($bill->amount, 0, ',', '.') }}
                        </h3>
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mt-2 line-clamp-2">
                            {{ $bill->description }}
                        </p>
                    </div>

                    <div class="mt-auto space-y-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Tenggat
                            </span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $bill->due_date ? $bill->due_date->format('d M Y') : '-' }}</span>
                        </div>
                        
                        @if($bill->status === 'unpaid')
                            <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-100 dark:border-blue-800">
                                <p class="text-xs text-blue-600 dark:text-blue-400 font-medium mb-1">Info Pembayaran:</p>
                                <p class="text-sm font-bold text-gray-800 dark:text-gray-200">BSI 70212123213</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">a.n Admin Absensi</p>
                            </div>
                        @endif

                        @if($bill->paid_at)
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Dibayar
                                </span>
                                <span class="font-semibold text-green-600 dark:text-green-400">{{ $bill->paid_at->format('d M Y H:i') }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 flex gap-2">
                        @if($bill->status === 'unpaid')
                            <button
                                wire:click="openUploadModal({{ $bill->id }})"
                                class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-semibold rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                {{ __('Upload Bukti') }}
                            </button>
                        @endif
                        
                        @if($bill->proof_path)
                            <a href="{{ Storage::url($bill->proof_path) }}" target="_blank" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm font-semibold rounded-xl transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                {{ __('Lihat Bukti') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="flex flex-col items-center justify-center py-20 bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-24 h-24 bg-blue-50 dark:bg-blue-900/20 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-12 h-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Tidak Ada Tagihan</h3>
                    <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto">
                        Saat ini Anda tidak memiliki tagihan yang perlu dibayar.
                    </p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Upload Modal -->
    <x-dialog-modal wire:model="showUploadModal">
        <x-slot name="title">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                {{ __('Upload Bukti Transfer') }}
            </h3>
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-100 dark:border-blue-800 mb-4">
                    <p class="text-sm text-blue-800 dark:text-blue-300 font-medium mb-1">Instruksi:</p>
                    <ul class="list-disc list-inside text-xs text-blue-700 dark:text-blue-400 space-y-1">
                        <li>Transfer sesuai nominal tagihan.</li>
                        <li>Pastikan foto bukti transfer jelas dan terbaca.</li>
                        <li>Format file: JPG, PNG, PDF (Max 2MB).</li>
                    </ul>
                </div>

                <div>
                    <x-label for="proof" value="{{ __('Pilih File') }}" class="mb-2" />
                    <div class="relative">
                        <input wire:model="proof" id="proof" type="file" class="block w-full text-sm text-gray-500
                            file:mr-4 file:py-2.5 file:px-4
                            file:rounded-full file:border-0
                            file:text-sm file:font-semibold
                            file:bg-blue-50 file:text-blue-700
                            hover:file:bg-blue-100
                            dark:file:bg-blue-900/30 dark:file:text-blue-300
                            cursor-pointer focus:outline-none" accept="image/*,.pdf" />
                    </div>
                    <x-input-error for="proof" class="mt-2" />
                </div>
                
                <div wire:loading wire:target="proof" class="text-sm text-blue-600 animate-pulse">
                    Mengupload file...
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeUploadModal" class="rounded-xl">
                {{ __('Batal') }}
            </x-secondary-button>

            <x-button wire:click="uploadProof" class="ml-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 border-0 rounded-xl shadow-md" wire:loading.attr="disabled">
                {{ __('Kirim Bukti') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
