<x-admin-layout>
    <x-slot name="header">
        <h1 class="h3 mb-0 text-gray-800">
            {{ __('Kelola Lokasi Sekolah') }}
        </h1>
    </x-slot>

    
            <div class="card shadow mb-4">
                @livewire('admin.office-location-table')
            </div>
        </x-admin-layout>
