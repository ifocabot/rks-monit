@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Group AU -->
        <div>
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Group: AU</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-6 gap-2">
                <!-- ...stat cards di sini seperti D01 - D04... -->

                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="stats">
                        <div class="stat">
                            <div class="stat-title text-gray-600">NT - Not Time Delivery</div>
                            <div class="stat-value text-3xl font-bold">{{ $ntCount ?? 0 }}</div>
                            <div class="stat-desc text-gray-500">AU Group</div>
                        </div>
                    </div>
                </div>

                <!-- Empty House/Office -->
                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="stats">
                        <div class="stat">
                            <div class="stat-title text-gray-600">U05 - Empty House/Office</div>
                            <div class="stat-value text-3xl font-bold">{{ $u05Count ?? 0 }}</div>
                            <div class="stat-desc text-gray-500">AU Group</div>
                        </div>
                    </div>
                </div>

                <!-- Weekend/Holiday Closure -->
                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="stats">
                        <div class="stat">
                            <div class="stat-title text-gray-600">U09 - Weekend/Holiday Closure</div>
                            <div class="stat-value text-3xl font-bold">{{ $u09Count ?? 0 }}</div>
                            <div class="stat-desc text-gray-500">Non-COD</div>
                        </div>
                    </div>
                </div>

                <!-- Force Majeure -->
                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="stats">
                        <div class="stat">
                            <div class="stat-title text-gray-600">U10 - Force Majeure</div>
                            <div class="stat-value text-3xl font-bold">{{ $u10Count ?? 0 }}</div>
                            <div class="stat-desc text-gray-500">AU Group</div>
                        </div>
                    </div>
                </div>

                <!-- Leave/Out of Town -->
                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="stats">
                        <div class="stat">
                            <div class="stat-title text-gray-600">U22 - Leave/Out of Town</div>
                            <div class="stat-value text-3xl font-bold">{{ $u22Count ?? 0 }}</div>
                            <div class="stat-desc text-gray-500">COD Delivery</div>
                        </div>
                    </div>
                </div>

                <!-- Weekend Closure (COD) -->
                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="stats">
                        <div class="stat">
                            <div class="stat-title text-gray-600">U23 - Weekend Closure</div>
                            <div class="stat-value text-3xl font-bold">{{ $u23Count ?? 0 }}</div>
                            <div class="stat-desc text-gray-500">COD Delivery</div>
                        </div>
                    </div>
                </div>

                <!-- Awaiting COD Value -->
                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="stats">
                        <div class="stat">
                            <div class="stat-title text-gray-600">U24 - Awaiting COD Value</div>
                            <div class="stat-value text-3xl font-bold">{{ $u24Count ?? 0 }}</div>
                            <div class="stat-desc text-gray-500">COD Confirmation</div>
                        </div>
                    </div>
                </div>

                <!-- Awaiting COD Payment -->
                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="stats">
                        <div class="stat">
                            <div class="stat-title text-gray-600">U25 - Awaiting Payment</div>
                            <div class="stat-value text-3xl font-bold">{{ $u25Count ?? 0 }}</div>
                            <div class="stat-desc text-gray-500">COD Payment</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Group Undel -->
        <div>
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Group: On Hold</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-6 gap-2">
                <!-- X-Ray Check Status Cards -->
                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="stats">
                        <div class="stat">
                            <div class="stat-title text-gray-600">X1 - Dangerous Goods</div>
                            <div class="stat-value text-3xl font-bold">{{ $x1Count ?? 0 }}</div>
                            <div class="stat-desc text-gray-500">X-Ray Check Failed</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="stats">
                        <div class="stat">
                            <div class="stat-title text-gray-600">X10 - Incomplete Docs</div>
                            <div class="stat-value text-3xl font-bold">{{ $x10Count ?? 0 }}</div>
                            <div class="stat-desc text-gray-500">X-Ray Check Failed</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Group: On Hold</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-6 gap-2">
                <!-- X-Ray Check Status Cards -->
                <!-- Card Component -->
                @php
                    $deliveryCodes = [
                        [
                            'code' => 'CR3',
                            'desc' => 'SHIPMENT PICKED UP BY CONSIGNEE AS REQUESTED BY SHIPPER/CONSIGNEE',
                            'status' => 'SUKSES DELIVERY',
                        ],
                        ['code' => 'D01', 'desc' => 'YANG BERSANGKUTAN', 'status' => 'SUKSES DELIVERY'],
                        ['code' => 'D02', 'desc' => 'RECEPTIONIST', 'status' => 'SUKSES DELIVERY'],
                        ['code' => 'D03', 'desc' => 'SEKRETARIS', 'status' => 'SUKSES DELIVERY'],
                        ['code' => 'D04', 'desc' => 'SECURITY', 'status' => 'SUKSES DELIVERY'],
                        ['code' => 'D05', 'desc' => 'MAILING ROOM', 'status' => 'SUKSES DELIVERY'],
                        ['code' => 'D06', 'desc' => 'SUAMI/ISTRI/ANAK', 'status' => 'SUKSES DELIVERY'],
                        ['code' => 'D07', 'desc' => 'PEMBANTU', 'status' => 'SUKSES DELIVERY'],
                        ['code' => 'D08', 'desc' => 'PENJAGA KOS', 'status' => 'SUKSES DELIVERY'],
                        ['code' => 'D09', 'desc' => 'KELUARGA/SAUDARA', 'status' => 'SUKSES DELIVERY'],
                        ['code' => 'D10', 'desc' => 'ATASAN/STAFF/KARYAWAN/BAWAHAN', 'status' => 'SUKSES DELIVERY'],
                        ['code' => 'D11', 'desc' => 'SUPIR', 'status' => 'SUKSES DELIVERY'],
                        ['code' => 'D12', 'desc' => 'OFFICE BOY', 'status' => 'SUKSES DELIVERY'],
                        ['code' => 'D15', 'desc' => 'CCC ORIGIN', 'status' => 'SUKSES DELIVERY'],
                        ['code' => 'D16', 'desc' => 'UNDEL ORIGIN', 'status' => 'SUKSES DELIVERY'],
                        ['code' => 'D24', 'desc' => 'SHIPMENT DAMAGE RECEIVED', 'status' => 'SUKSES DELIVERY'],
                        ['code' => 'D25', 'desc' => 'SUCCESS BREACH RECEIVED', 'status' => 'SUKSES DELIVERY'],
                        ['code' => 'DB1', 'desc' => 'PAKET SUDAH DI POP BOX', 'status' => 'SUKSES DELIVERY'],
                        ['code' => 'DB2', 'desc' => 'KIRIMAN SUDAH DIAMBIL', 'status' => 'SUKSES DELIVERY'],
                    ];
                @endphp

                @foreach ($deliveryCodes as $item)
                    <div class="bg-white rounded-lg shadow-md p-4">
                        <div class="stats">
                            <div class="stat">
                                <div class="stat-title text-gray-600 break-words whitespace-normal">{{ $item['code'] }} -
                                    {{ $item['desc'] }}</div>
                                <div class="stat-value text-3xl font-bold break-words">
                                    {{ $deliveryCount[$item['code']] ?? 0 }}</div>
                                <div class="stat-desc text-gray-500">{{ $item['status'] }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

        <div>
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Group: On Hold</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-6 gap-2">
                <!-- X-Ray Check Status Cards -->
                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="stats">
                        <div class="stat">
                            <div class="stat-title text-gray-600">X1 - Dangerous Goods</div>
                            <div class="stat-value text-3xl font-bold">{{ $x1Count ?? 0 }}</div>
                            <div class="stat-desc text-gray-500">X-Ray Check Failed</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="stats">
                        <div class="stat">
                            <div class="stat-title text-gray-600">X10 - Incomplete Docs</div>
                            <div class="stat-value text-3xl font-bold">{{ $x10Count ?? 0 }}</div>
                            <div class="stat-desc text-gray-500">X-Ray Check Failed</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @include('awb.upload')
@endsection
