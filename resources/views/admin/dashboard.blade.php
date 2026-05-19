<x-admin-layout>
  <x-slot name="header">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
  </x-slot>

  @if(Auth::user()->isTeacher)
    @livewire('teacher.teacher-dashboard-component')
  @else
    @livewire('admin.dashboard-component')
  @endif
</x-admin-layout>
