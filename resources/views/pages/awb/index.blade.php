@extends('layouts.app')

@section('content')
    <div class="p-2 space-y-6">
        <!-- HEADER -->

        <div class="">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <h2 class="text-xl font-bold">🗂️ Aktivitas Upload Terbaru</h2>
                </div>
                <div class="flex gap-2">
                    <button class="btn btn-ghost btn-sm">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                        Refresh
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto bg-base-100 rounded-box shadow-lg">
                <div class="bg-base-100 p-6 border-b space-y-6">
                    <div class="flex-1">
                        <!-- Filter Toggle Button -->
                        <div class="flex justify-between items-center mb-4">
                            <button type="button" onclick="toggleFilters()" class="btn btn-ghost btn-sm">
                                <i data-lucide="filter" class="w-4 h-4 mr-2"></i>
                                Show/Hide Filters
                            </button>

                            <div class="dropdown dropdown-end">
                                <label tabindex="0" class="btn btn-success btn-sm">
                                    <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                                    Export Data
                                </label>
                                <ul tabindex="0"
                                    class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                                    <li><a href="#"><i data-lucide="file-spreadsheet" class="w-4 h-4 mr-2"></i>Excel
                                            (.xlsx)</a></li>
                                    <li><a href="#"><i data-lucide="file-text" class="w-4 h-4 mr-2"></i>CSV</a></li>
                                    <li><a href="#"><i data-lucide="file" class="w-4 h-4 mr-2"></i>PDF</a></li>
                                </ul>
                            </div>
                        </div>

                        <!-- Active Filters Display -->
                        <div class="flex flex-wrap gap-2 mb-4">
                            @if (request('search'))
                                <div class="badge badge-info gap-2">
                                    AWB: {{ request('search') }}
                                    <a href="{{ request()->fullUrlWithQuery(['search' => '']) }}" class="text-xs">×</a>
                                </div>
                            @endif
                            @if (request('status_codes'))
                                @foreach ((array) request('status_codes') as $code)
                                    <div class="badge badge-info gap-2">
                                        Status Code: {{ $code }}
                                        @php
                                            $statusDesc = $status_code->where('code', $code)->first()->desc ?? '';
                                        @endphp
                                        <span class="font-mono text-xs ml-1">({{ $statusDesc }})</span>
                                        <a href="{{ request()->fullUrlWithQuery(['status_codes' => array_diff((array) request('status_codes'), [$code])]) }}"
                                            class="text-xs ml-2">×</a>
                                    </div>
                                @endforeach
                            @endif
                            @if (request('completed') !== null)
                                <div class="badge badge-info gap-2">
                                    Completion: {{ request('completed') ? 'Completed' : 'Not Completed' }}
                                    <a href="{{ request()->fullUrlWithQuery(['completed' => '']) }}" class="text-xs">×</a>
                                </div>
                            @endif
                            @if (request('date_from') || request('date_to'))
                                <div class="badge badge-info gap-2">
                                    Date: {{ request('date_from') ?? 'Any' }} to {{ request('date_to') ?? 'Any' }}
                                    <a href="{{ request()->fullUrlWithQuery(['date_from' => '', 'date_to' => '']) }}"
                                        class="text-xs">×</a>
                                </div>
                            @endif
                        </div>

                        <!-- Filter Form -->
                        <form method="GET" class="space-y-6 hidden" id="filterForm">
                            <!-- Search and Status Filters -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-medium">AWB Number</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                            <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                                        </span>
                                        <input type="text" name="search" value="{{ request('search') }}"
                                            placeholder="Enter AWB number..." class="input input-bordered w-full pl-10">
                                    </div>
                                </div>

                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-medium">Status Label</span>
                                    </label>
                                    <select name="status_code" class="select select-bordered w-full">
                                        <option value="">All Status Codes</option>
                                        @foreach (['DELIVERED', 'IN_TRANSIT', 'PENDING', 'PICKUP', 'RETURN', 'CANCELLED'] as $status)
                                            <option value="{{ $status }}"
                                                {{ request('status_code') === $status ? 'selected' : '' }}>
                                                {{ $status }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-medium">By Code</span>
                                    </label>
                                    <button type="button" onclick="openStatusModal()"
                                        class="btn btn-outline w-full flex justify-between items-center">
                                        <span id="selectedStatus">Select Status...</span>
                                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                                    </button>

                                    <!-- Status Selection Modal -->
                                    <dialog id="status_modal" class="modal">
                                        <div class="modal-box w-11/12 max-w-5xl">
                                            <div class="flex justify-between items-center border-b pb-4 mb-4">
                                                <h3 class="font-bold text-lg">Select Status</h3>
                                                <button onclick="closeStatusModal()"
                                                    class="btn btn-sm btn-circle">✕</button>
                                            </div>

                                            <div class="space-y-4">
                                                @php
                                                    $groupedStatus = $status_code->groupBy('group');
                                                @endphp

                                                @foreach ($groupedStatus as $group => $statuses)
                                                    <div class="card bg-base-200">
                                                        <div class="card-body p-4">
                                                            <h4 class="card-title text-sm mb-3 flex items-center gap-2">
                                                                @switch(strtolower($group))
                                                                    @case('delivered')
                                                                        <i data-lucide="package-check"
                                                                            class="w-5 h-5 text-success"></i>
                                                                    @break

                                                                    @case('in transit')
                                                                        <i data-lucide="truck" class="w-5 h-5 text-info"></i>
                                                                    @break

                                                                    @case('pending')
                                                                        <i data-lucide="clock" class="w-5 h-5 text-warning"></i>
                                                                    @break

                                                                    @case('return')
                                                                        <i data-lucide="package-x" class="w-5 h-5 text-error"></i>
                                                                    @break

                                                                    @default
                                                                        <i data-lucide="package" class="w-5 h-5"></i>
                                                                @endswitch
                                                                {{ $group }}
                                                            </h4>
                                                            <div class="pt-2 grid grid-cols-3 gap-2">
                                                                @foreach ($statuses as $status)
                                                                    <label
                                                                        class="flex items-center gap-2 cursor-pointer hover:bg-base-300 p-2 rounded-lg"><input
                                                                            type="checkbox" name="status_codes[]"
                                                                            value="{{ $status->code }}"
                                                                            class="checkbox checkbox-sm"
                                                                            {{ in_array($status->code, (array) request('status_codes')) ? 'checked' : '' }}
                                                                            onchange="updateSelectedStatuses()"><span
                                                                            class="text-sm"><span
                                                                                class="font-medium mr-1">[{{ $status->code }}]</span><span
                                                                                class="font-mono text-primary-content/70">{{ $status->desc }}</span>
                                                                            @if ($status->dashboard_category)
                                                                                <span
                                                                                    class="font-mono text-primary-content/70">({{ $status->dashboard_category }})</span>
                                                                            @endif
                                                                        </span></label>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <div class="modal-action mt-6 border-t pt-4">
                                                <button type="button" onclick="closeStatusModal()"
                                                    class="btn btn-primary">Done</button>
                                            </div>
                                        </div>
                                    </dialog>

                                    <script>
                                        function openStatusModal() {
                                            document.getElementById('status_modal').showModal();
                                        }

                                        function closeStatusModal() {
                                            document.getElementById('status_modal').close();
                                        }

                                        function updateSelectedStatuses() {
                                            const checkboxes = document.querySelectorAll('input[name="status_codes[]"]:checked');
                                            const selectedCount = checkboxes.length;
                                            const selectedStatus = document.getElementById('selectedStatus');

                                            if (selectedCount === 0) {
                                                selectedStatus.textContent = 'Select Status...';
                                            } else {
                                                selectedStatus.textContent = `${selectedCount} status selected`;
                                            }
                                        }

                                        // Initialize on page load
                                        document.addEventListener('DOMContentLoaded', updateSelectedStatuses);
                                    </script>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-medium">Completion Status</span>
                                    </label>
                                    <select name="completed" class="select select-bordered w-full">
                                        <option value="">All Status</option>
                                        <option value="1" {{ request('completed') === '1' ? 'selected' : '' }}>
                                            Completed</option>
                                        <option value="0" {{ request('completed') === '0' ? 'selected' : '' }}>Not
                                            Completed</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Date Range Picker -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-medium">From Date</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                            <i data-lucide="calendar" class="w-4 h-4 text-gray-400"></i>
                                        </span>
                                        <input type="datetime-local" name="date_from" value="{{ request('date_from') }}"
                                            class="input input-bordered w-full pl-10">
                                    </div>
                                </div>

                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-medium">To Date</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                            <i data-lucide="calendar" class="w-4 h-4 text-gray-400"></i>
                                        </span>
                                        <input type="datetime-local" name="date_to" value="{{ request('date_to') }}"
                                            class="input input-bordered w-full pl-10">
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-wrap items-center gap-3">
                                <button type="submit" class="btn btn-primary">
                                    <i data-lucide="filter" class="w-4 h-4 mr-2"></i>
                                    Apply Filters
                                </button>

                                <a href="{{ url()->current() }}" class="btn btn-ghost">
                                    <i data-lucide="x" class="w-4 h-4 mr-2"></i>
                                    Reset
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <table class="table table-zebra">
                    <thead class="bg-base-200">
                        <tr>
                            <th class="font-bold w-12">#</th>
                            <th class="font-bold">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'awb_number', 'direction' => request('sort') === 'awb_number' && request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                    class="flex items-center gap-1">
                                    AWB Number
                                    @if (request('sort') === 'awb_number')
                                        <i data-lucide="{{ request('direction') === 'asc' ? 'arrow-up' : 'arrow-down' }}"
                                            class="w-4 h-4"></i>
                                    @endif
                                </a>
                            </th>
                            <th class="font-bold">Status Code</th>
                            <th class="font-bold">Status Label</th>
                            <th class="font-bold">Last Checked</th>
                            <th class="font-bold">Delivered At</th>
                            <th class="font-bold">POD Receiver</th>
                            <th class="font-bold">Completed</th>
                            <th class="font-bold">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($awb as $index => $item)
                            <tr class="hover transition-colors">
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="font-mono font-semibold">{{ $item->awb_number }}</div>
                                </td>
                                <td>
                                    <div class="badge badge-info badge-lg">{{ $item->status_code }}</div>
                                </td>
                                <td>
                                    <div
                                        class="badge {{ $item->is_completed ? 'badge-success' : 'badge-warning' }} badge-lg">
                                        {{ $item->status_label }}
                                    </div>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($item->last_checked_at)->format('d M Y H:i') }}</td>
                                <td>{{ $item->delivered_at ? \Carbon\Carbon::parse($item->delivered_at)->format('d M Y') : '-' }}
                                </td>
                                <td>{{ $item->pod_receiver ?? '-' }}</td>
                                <td>
                                    <div
                                        class="badge {{ $item->is_completed ? 'badge-success' : 'badge-error' }} badge-lg">
                                        {{ $item->is_completed ? 'Yes' : 'No' }}
                                    </div>
                                </td>
                                <td>
                                    <label for="drawer-{{ $item->awb_number }}" class="btn btn-primary btn-sm">
                                        Detail
                                    </label>
                                </td>



                                <div class="drawer drawer-end">
                                    <input id="drawer-{{ $item->awb_number }}" type="checkbox" class="drawer-toggle" />
                                    <div class="drawer-content">
                                        <!-- Drawer trigger button is in the parent table cell -->
                                    </div>
                                    <div class="drawer-side">
                                        <label for="drawer-{{ $item->awb_number }}" aria-label="close sidebar"
                                            class="drawer-overlay"></label>
                                        <div class="menu bg-base-100 min-h-full w-[500px] p-4 text-base-content">
                                            <div class="flex items-center justify-between border-b pb-4">
                                                <h5 class="text-base font-semibold flex items-center gap-2">
                                                    <i data-lucide="package" class="w-5 h-5"></i>
                                                    Detail AWB | {{ $item->awb_number }}
                                                </h5>
                                                <label for="drawer-{{ $item->awb_number }}"
                                                    class="btn btn-sm btn-circle">
                                                    <i data-lucide="x" class="w-4 h-4"></i>
                                                </label>
                                            </div>

                                            <div class="py-4 space-y-4">
                                                <div class="flex flex-col space-y-6">
                                                    <!-- AWB Basic Information -->
                                                    <div class="card bg-base-100 shadow-lg">
                                                        <div class="card-body">
                                                            <h3
                                                                class="card-title text-lg font-mono flex items-center gap-2">
                                                                <i data-lucide="package" class="w-5 h-5"></i>
                                                                AWB Information
                                                            </h3>

                                                            <div class="grid grid-cols-2 gap-4 mt-4">
                                                                <div class="space-y-3">
                                                                    <div
                                                                        class="flex items-center gap-2 p-2 bg-base-200 rounded-lg">
                                                                        <i data-lucide="layers"
                                                                            class="w-4 h-4 text-primary"></i>
                                                                        <div class="flex flex-col">
                                                                            <span
                                                                                class="text-xs text-base-content/70">Batch
                                                                                Number</span>
                                                                            <span
                                                                                class="font-medium">#{{ $item->batch->id }}</span>
                                                                        </div>
                                                                    </div>

                                                                    <div
                                                                        class="flex items-center gap-2 p-2 bg-base-200 rounded-lg">
                                                                        <i data-lucide="user"
                                                                            class="w-4 h-4 text-primary"></i>
                                                                        <div class="flex flex-col">
                                                                            <span
                                                                                class="text-xs text-base-content/70">Uploaded
                                                                                By</span>
                                                                            <span
                                                                                class="font-medium">{{ $item->user->name }}</span>
                                                                        </div>
                                                                    </div>

                                                                    <div
                                                                        class="flex items-center gap-2 p-2 bg-base-200 rounded-lg">
                                                                        <i data-lucide="calendar"
                                                                            class="w-4 h-4 text-primary"></i>
                                                                        <div class="flex flex-col">
                                                                            <span
                                                                                class="text-xs text-base-content/70">Upload
                                                                                Time</span>
                                                                            <span
                                                                                class="font-medium">{{ $item->created_at }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="space-y-3">
                                                                    <div
                                                                        class="flex items-center gap-2 p-2 bg-base-200 rounded-lg">
                                                                        <i data-lucide="clock"
                                                                            class="w-4 h-4 text-info"></i>
                                                                        <div class="flex flex-col">
                                                                            <span class="text-xs text-base-content/70">Last
                                                                                Check</span>
                                                                            <span
                                                                                class="font-medium">{{ $item->last_checked_at }}</span>
                                                                        </div>
                                                                    </div>

                                                                    <div
                                                                        class="flex items-center gap-2 p-2 bg-base-200 rounded-lg">
                                                                        <i data-lucide="check-circle"
                                                                            class="w-4 h-4 text-success"></i>
                                                                        <div class="flex flex-col">
                                                                            <span
                                                                                class="text-xs text-base-content/70">Delivery
                                                                                Time</span>
                                                                            <span
                                                                                class="font-medium">{{ $item->delivered_at }}</span>
                                                                        </div>
                                                                    </div>

                                                                    <div
                                                                        class="flex items-center gap-2 p-2 bg-base-200 rounded-lg">
                                                                        <i data-lucide="user-check"
                                                                            class="w-4 h-4 text-success"></i>
                                                                        <div class="flex flex-col">
                                                                            <span
                                                                                class="text-xs text-base-content/70">Received
                                                                                By</span>
                                                                            <span
                                                                                class="font-medium">{{ $item->pod_receiver }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="divider"></div>

                                                            <div class="grid grid-cols-3 gap-4">
                                                                <div class="stat bg-base-200 rounded-lg p-4">
                                                                    <div class="stat-title text-xs">Status Code</div>
                                                                    <div class="stat-value text-primary text-lg">
                                                                        {{ $item->status_code }}</div>
                                                                </div>

                                                                <div class="stat bg-base-200 rounded-lg p-4">
                                                                    <div class="stat-title text-xs">Status Label</div>
                                                                    <div class="stat-value text-primary text-lg">
                                                                        {{ $item->status_label }}</div>
                                                                </div>

                                                                <div class="stat bg-base-200 rounded-lg p-4">
                                                                    <div class="stat-title text-xs">Completion Status</div>
                                                                    <div
                                                                        class="stat-value {{ $item->is_completed ? 'text-success' : 'text-error' }} text-lg">
                                                                        {{ $item->is_completed ? 'Completed' : 'Pending' }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- AWB Detail Information -->
                                                    <div class="card bg-base-100 shadow-lg">
                                                        <div class="card-body">
                                                            <h3
                                                                class="card-title text-lg font-mono flex items-center gap-2">
                                                                <i data-lucide="file-text" class="w-5 h-5"></i>
                                                                AWB Detail Information
                                                            </h3>
                                                        </div>
                                                    </div>

                                                    <!-- AWB Photos -->
                                                    <div class="card bg-base-100 shadow-lg">
                                                        <div class="card-body">
                                                            <h3
                                                                class="card-title text-lg font-mono flex items-center gap-2">
                                                                <i data-lucide="image" class="w-5 h-5"></i>
                                                                AWB Photos
                                                            </h3>
                                                        </div>
                                                    </div>

                                                    <!-- AWB History -->
                                                    <div class="card bg-base-100 shadow-lg">
                                                        <div class="card-body">
                                                            <h3
                                                                class="card-title text-lg font-mono flex items-center gap-2">
                                                                <i data-lucide="history" class="w-5 h-5"></i>
                                                                AWB History
                                                            </h3>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="p-4">
                    {{ $awb->links() }}
                </div>
            </div>

            <script>
                function toggleFilters() {
                    const filterForm = document.getElementById('filterForm');
                    filterForm.classList.toggle('hidden');
                }
            </script>
        </div>
    </div>
@endsection
