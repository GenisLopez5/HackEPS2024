@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-4">Parking Information</h1>
    <div class="parking-info bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-semibold mb-2">Occupancy Rate: {{ 1- $parking->occupied / $parking->capacity }}</h2>
        <p class="text-lg">Available Spots: {{ 1- $parking->occupied }}/{{ $parking->capacity }}</p>
    </div>
</div>
@endsection