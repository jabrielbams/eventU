@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="py-10 text-center">
    <h1 class="text-4xl font-black uppercase text-telkom-red mb-4">Dashboard</h1>
    <div class="neo-box bg-white p-6 max-w-2xl mx-auto">
        <p class="text-xl font-bold">Welcome, {{ auth()->user()->name }}!</p>
        <p class="mt-2 text-gray-600">You are logged in as <span class="uppercase font-black">{{ auth()->user()->role }}</span>.</p>
    </div>
</div>
@endsection
