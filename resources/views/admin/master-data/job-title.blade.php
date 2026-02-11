<x-admin-layout>
  <x-slot name="header">
    <h1 class="h3 mb-0 text-gray-800">
      {{ __('Job Title') }}
    </h1>
  </x-slot>

  
      <div class="card shadow mb-4">
        <div class="card-body">
          @livewire('admin.master-data.job-title-component')
        </x-admin-layout>
