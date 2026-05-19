@if(Auth::check() && Auth::user()->isTeacher)
<x-teacher-layout>
    @if(isset($title))
    <x-slot name="title">{{ $title }}</x-slot>
    @endif
    @if(isset($header))
    <x-slot name="header">{{ $header }}</x-slot>
    @endif
    {{ $slot }}
</x-teacher-layout>
@else
<x-admin-layout>
    @if(isset($title))
    <x-slot name="title">{{ $title }}</x-slot>
    @endif
    @if(isset($header))
    <x-slot name="header">{{ $header }}</x-slot>
    @endif
    {{ $slot }}
</x-admin-layout>
@endif
