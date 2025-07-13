@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="bg-white rounded-lg shadow-md p-4">
        <div class="stats">
            <div class="stat">
                <div class="stat-title text-gray-600">D01 - Pending</div>
                <div class="stat-value text-3xl font-bold">5,400</div>
                <div class="stat-desc text-gray-500">Total pending tasks</div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-4">
        <div class="stats">
            <div class="stat">
                <div class="stat-title text-gray-600">D02 - Processing</div>
                <div class="stat-value text-3xl font-bold">390</div>
                <div class="stat-desc text-gray-500">Currently processing</div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-4">
        <div class="stats">
            <div class="stat">
                <div class="stat-title text-gray-600">D03 - Failed</div>
                <div class="stat-value text-3xl font-bold">6</div>
                <div class="stat-desc text-red-500">Requires attention</div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-4">
        <div class="stats">
            <div class="stat">
                <div class="stat-title text-gray-600">D04 - Completed</div>
                <div class="stat-value text-3xl font-bold">2,047</div>
                <div class="stat-desc text-green-500">Successfully processed</div>
            </div>
        </div>
    </div>
</div>
@endsection