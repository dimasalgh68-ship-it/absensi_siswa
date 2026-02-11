<x-admin-layout>
  <x-slot name="header">
    <h1 class="h3 mb-0 text-gray-800">
      {{ __('Attendance') }}
    </h1>
  </x-slot>

  <div class="card shadow mb-4">
    <div class="card-body">
          @livewire('admin.attendance-component')
        </div>
</div>
</x-admin-layout>
