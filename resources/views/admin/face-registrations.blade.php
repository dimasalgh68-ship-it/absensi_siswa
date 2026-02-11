<x-admin-layout>
    <x-slot name="header">
        <h1 class="h3 mb-0 text-gray-800">
            {{ __('Manajemen Registrasi Wajah') }}
        </h1>
    </x-slot>

    
            <div class="card shadow mb-4">
                @livewire('admin.face-registration-table')
            </div>
        </x-admin-layout>
