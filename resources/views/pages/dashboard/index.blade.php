@extends('layouts.app')

@section('content')
    <div class="p-6 space-y-6">
        <!-- HEADER -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">📦 Dashboard Pengiriman</h1>
                <p class="text-sm text-gray-500">Monitor status AWB & aktivitas batch</p>
            </div>
            <div class="flex items-center gap-2">
                <!-- FILTER WAKTU -->
                <div x-data="{ range: '{{ request('range') }}', showCustom: '{{ request('range') === 'custom' ? 'true' : 'false' }}' == 'true' }" class="relative">
                    <form method="GET" class="flex gap-2 items-center">
                        <!-- Select dropdown -->
                        <select name="range" x-model="range" @change="showCustom = (range === 'custom'); $el.form.submit()"
                            class="select select-info pl-9">
                            <option disabled value="">Pilih Rentang</option>
                            <option value="today" {{ request('range') === 'today' ? 'selected' : '' }}>Hari ini</option>
                            <option value="7days" {{ request('range') === '7days' ? 'selected' : '' }}>7 Hari Terakhir
                            </option>
                            <option value="thismonth" {{ request('range') === 'thismonth' ? 'selected' : '' }}>Bulan Ini
                            </option>
                            <option value="custom" {{ request('range') === 'custom' ? 'selected' : '' }}>Custom Range
                            </option>
                        </select>
                        <i data-lucide="calendar" class="absolute left-2 top-1/2 -translate-y-1/2 w-5 h-5 text-info"></i>

                        <!-- Date range inputs -->
                        <template x-if="showCustom">
                            <div class="flex gap-2 items-center">
                                <input type="date" name="start_date" class="input input-bordered input-sm"
                                    value="{{ request('start_date') }}" required>
                                <input type="date" name="end_date" class="input input-bordered input-sm"
                                    value="{{ request('end_date') }}" required>
                                <button class="btn btn-info btn-sm">Terapkan</button>
                            </div>
                        </template>
                    </form>
                </div>
                <form method="POST" action="{{ route('awb.syncStatus') }}">
                    @csrf
                    <button type="submit" class="btn btn-warning btn-sm flex items-center gap-1">
                        <i data-lucide="refresh-ccw" class="w-4 h-4"></i>
                        Sync Status
                    </button>
                </form>
                <!-- UPLOAD BUTTON -->
                @include('awb.upload')
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="card bg-base-100 shadow-md">
                <div class="card-body">
                    <h2 class="text-sm text-gray-500">Total Resi</h2>
                    <p class="text-2xl font-bold">{{ $total_all }}</p>
                </div>
            </div>
            <div class="card bg-base-100 shadow-md">
                <div class="card-body">
                    <h2 class="text-sm text-gray-500">Resi Selesai</h2>
                    <p class="text-2xl font-bold text-green-600">{{ $completed }}</p>
                </div>
            </div>
            <div class="card bg-base-100 shadow-md">
                <div class="card-body">
                    <h2 class="text-sm text-gray-500">Resi Belum Selesai</h2>
                    <p class="text-2xl font-bold text-yellow-500">{{ $incomplete }}</p>
                </div>
            </div>
        </div>
        <!-- RINGKASAN STATUS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($summary as $item)
                <div class="card shadow-md bg-base-100">
                    <div class="card-body">
                        <h2 class="text-sm font-medium text-gray-500">{{ $item->dashboard_category }}</h2>
                        <div class="flex items-baseline gap-2">
                            <p class="text-2xl font-bold">{{ $item->total }}</p>
                            <span class="text-xs text-gray-400">({{ $item->percentage }}%)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                            <div class="bg-info h-1.5 rounded-full" style="width: {{ $item->percentage }}%"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <!-- BATCH TABLE PREVIEW (LIVE FROM upload_batches) -->
        <div class="mt-8">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <h2 class="text-xl font-bold">🗂️ Aktivitas Upload Terbaru</h2>
                    <div class="badge badge-info">{{ count($batches) }} entries</div>
                </div>
                <div class="flex gap-2">
                    <button class="btn btn-ghost btn-sm">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                        Refresh
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto bg-base-100 rounded-box shadow-lg">
                <table class="table table-zebra">
                    <thead class="bg-base-200">
                        <tr>
                            <th class="font-bold">Batch ID</th>
                            <th class="font-bold">Uploader</th>
                            <th class="font-bold">Jumlah AWB</th>
                            <th class="font-bold">Berhasil</th>
                            <th class="font-bold">Gagal</th>
                            <th class="font-bold">Waktu Upload</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($batches as $batch)
                            <tr class="hover transition-colors">
                                <td>
                                    <div class="font-mono font-semibold">#{{ $batch->id }}</div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="avatar placeholder">
                                            <div class="bg-neutral text-neutral-content rounded-full w-8">
                                                <img src="https://i.pravatar.cc/40" alt="Avatar">
                                            </div>
                                        </div>
                                        <div class="font-medium">{{ $batch->uploader }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="badge badge-primary badge-lg">{{ $batch->total_rows }}</div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-1">
                                        <div class="badge badge-success badge-lg gap-2">
                                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                                            {{ $batch->inserted }}
                                        </div>
                                        <span class="text-xs text-success">
                                            ({{ round(($batch->inserted / $batch->total_rows) * 100) }}%)
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-1">
                                        <div class="badge badge-error badge-lg gap-2">
                                            <i data-lucide="x-circle" class="w-4 h-4"></i>
                                            {{ $batch->failed }}
                                        </div>
                                        <span class="text-xs text-error">
                                            ({{ round(($batch->failed / $batch->total_rows) * 100) }}%)
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex flex-col">
                                        <span class="font-medium">
                                            {{ \Carbon\Carbon::parse($batch->uploaded_at)->format('d M Y') }}
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($batch->uploaded_at)->format('H:i') }}
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
