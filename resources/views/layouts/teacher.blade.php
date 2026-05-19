@extends('layouts.admin')

@section('content')
    @if(isset($header))
        <x-slot name="header">
            {{ $header }}
        </x-slot>
    @endif

    {{ $slot }}
@endsection
