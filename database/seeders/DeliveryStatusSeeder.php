<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeliveryStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $data = [
            ['code' => 'BI1', 'group' => 'TRANSIT', 'desc' => 'RECEIVED AT KOTA TUJUAN', 'dashboard_category' => 'Sudah Di Kota Tujuan'],
            ['code' => 'CR1', 'group' => 'UNDEL', 'desc' => 'SHIPMENT RETURN', 'dashboard_category' => 'Proses Pengembalian Ke Kamu'],
            ['code' => 'CR2', 'group' => 'UNDEL', 'desc' => 'CANCEL BY SHIPPER', 'dashboard_category' => 'Dibatalkan Oleh Kamu'],
            ['code' => 'CR3', 'group' => 'SUKSES DELIVERY', 'desc' => 'SHIPMENT PICKED UP BY CONSIGNEE AS REQUESTED BY SHIPPER/CONSIGNEE', 'dashboard_category' => 'Sukses Diterima'],
            ['code' => 'CR4', 'group' => 'UNDEL', 'desc' => 'DESTROY', 'dashboard_category' => 'Proses Pengembalian Ke Kamu'],
            ['code' => 'CR5', 'group' => 'UNDEL', 'desc' => 'SHIPMENT BEING HELD AS SHIPPERS REQUEST', 'dashboard_category' => 'Proses Pengembalian Ke Kamu'],
            ['code' => 'CR6', 'group' => 'UNDEL', 'desc' => 'UNDEL', 'dashboard_category' => 'Proses Pengembalian Ke Kamu'],
            ['code' => 'CR7', 'group' => 'UNDEL', 'desc' => 'WAREHOUSE ORIGIN', 'dashboard_category' => 'Proses Pengembalian Ke Kamu'],
            ['code' => 'D01', 'group' => 'SUKSES DELIVERY', 'desc' => 'YANG BERSANGKUTAN', 'dashboard_category' => 'Sukses Diterima'],
            ['code' => 'D02', 'group' => 'SUKSES DELIVERY', 'desc' => 'RECEPTIONIST', 'dashboard_category' => 'Sukses Diterima'],
            ['code' => 'D03', 'group' => 'SUKSES DELIVERY', 'desc' => 'SEKRETARIS', 'dashboard_category' => 'Sukses Diterima'],
            ['code' => 'D04', 'group' => 'SUKSES DELIVERY', 'desc' => 'SECURITY', 'dashboard_category' => 'Sukses Diterima'],
            ['code' => 'D05', 'group' => 'SUKSES DELIVERY', 'desc' => 'MAILING ROOM', 'dashboard_category' => 'Sukses Diterima'],
            ['code' => 'D06', 'group' => 'SUKSES DELIVERY', 'desc' => 'SUAMI/ISTRI/ANAK', 'dashboard_category' => 'Sukses Diterima'],
            ['code' => 'D07', 'group' => 'SUKSES DELIVERY', 'desc' => 'PEMBANTU', 'dashboard_category' => 'Sukses Diterima'],
            ['code' => 'D08', 'group' => 'SUKSES DELIVERY', 'desc' => 'PENJAGA KOS', 'dashboard_category' => 'Sukses Diterima'],
            ['code' => 'D09', 'group' => 'SUKSES DELIVERY', 'desc' => 'KELUARGA/SAUDARA', 'dashboard_category' => 'Sukses Diterima'],
            ['code' => 'D10', 'group' => 'SUKSES DELIVERY', 'desc' => 'ATASAN/STAFF/KARYAWAN/BAWAHAN', 'dashboard_category' => 'Sukses Diterima'],
            ['code' => 'D11', 'group' => 'SUKSES DELIVERY', 'desc' => 'SUPIR', 'dashboard_category' => 'Sukses Diterima'],
            ['code' => 'D12', 'group' => 'SUKSES DELIVERY', 'desc' => 'OFFICE BOY', 'dashboard_category' => 'Sukses Diterima'],
            ['code' => 'D15', 'group' => 'SUKSES DELIVERY', 'desc' => 'CCC ORIGIN', 'dashboard_category' => 'Sukses Diterima'],
            ['code' => 'D16', 'group' => 'SUKSES DELIVERY', 'desc' => 'UNDEL ORIGIN', 'dashboard_category' => 'Sukses Diterima'],
            ['code' => 'D24', 'group' => 'SUKSES DELIVERY', 'desc' => 'SHIPMENT DAMAGE RECEIVED', 'dashboard_category' => 'Sukses Diterima'],
            ['code' => 'D25', 'group' => 'SUKSES DELIVERY', 'desc' => 'SUCCESS BREACH RECEIVED', 'dashboard_category' => 'Sukses Diterima'],
            ['code' => 'DB1', 'group' => 'SUKSES DELIVERY', 'desc' => 'PAKET SUDAH DI POP BOX', 'dashboard_category' => 'Sukses Diterima'],
            ['code' => 'DB2', 'group' => 'SUKSES DELIVERY', 'desc' => 'KIRIMAN SUDAH DIAMBIL', 'dashboard_category' => 'Sukses Diterima'],
            ['code' => 'DP5', 'group' => 'UNDEL', 'desc' => 'DELIVERY PROBLEM', 'dashboard_category' => 'Proses Pengembalian Ke Kamu'],
            ['code' => 'IP1', 'group' => 'TRANSIT', 'desc' => 'RECEIVED AT WAREHOUSE', 'dashboard_category' => 'Sudah Di Gudang JNE'],
            ['code' => 'IP2', 'group' => 'TRANSIT', 'desc' => 'RECEIVED AT DROP POINT', 'dashboard_category' => 'Sudah Di Gudang JNE'],
            ['code' => 'IP3', 'group' => 'TRANSIT', 'desc' => 'WITH DELIVERY COURIER', 'dashboard_category' => 'Dalam Proses'],
            ['code' => 'NT', 'group' => 'AU', 'desc' => 'NOT TIME DELIVERY', 'dashboard_category' => 'Butuh Dicek'],
            ['code' => 'OC', 'group' => 'UNDEL', 'desc' => 'DESTINATION CORRECTION', 'dashboard_category' => 'Proses Pengembalian Ke Kamu'],
            ['code' => 'OP1', 'group' => 'TRANSIT', 'desc' => 'RECEIVED AT SORTING CENTER', 'dashboard_category' => 'Sudah Di Gudang JNE'],
            ['code' => 'OP2', 'group' => 'TRANSIT', 'desc' => 'PROCESSED AT SORTING CENTER MEGAHUB', 'dashboard_category' => 'Sudah Di Gudang JNE'],
            ['code' => 'OP3', 'group' => 'TRANSIT', 'desc' => 'PROCESS AND FORWARD TO DESTINATION', 'dashboard_category' => 'Dalam Proses'],
            ['code' => 'OS', 'group' => 'UNDEL', 'desc' => 'OUT OF YES SERVICE AREA', 'dashboard_category' => 'Proses Pengembalian Ke Kamu'],
            ['code' => 'PS2', 'group' => 'UNDEL', 'desc' => 'SHIPMENT PROBLEM', 'dashboard_category' => 'Proses Pengembalian Ke Kamu'],
            ['code' => 'PU0', 'group' => 'TRANSIT', 'desc' => 'SHIPMENT PICKED UP BY JNE COURIER', 'dashboard_category' => 'Sudah Dijemput'],
            ['code' => 'PU1', 'group' => 'TRANSIT', 'desc' => 'SHIPMENT PICKED UP BY JNE COURIER', 'dashboard_category' => 'Sudah Dijemput'],
            ['code' => 'R01', 'group' => 'SUKSES RETURN', 'desc' => 'YANG BERSANGKUTAN', 'dashboard_category' => 'Sukses Dikembalikan Ke Kamu'],
            ['code' => 'R02', 'group' => 'SUKSES RETURN', 'desc' => 'RECEPTIONIST', 'dashboard_category' => 'Sukses Dikembalikan Ke Kamu'],
            ['code' => 'R03', 'group' => 'SUKSES RETURN', 'desc' => 'SEKRETARIS', 'dashboard_category' => 'Sukses Dikembalikan Ke Kamu'],
            ['code' => 'R04', 'group' => 'SUKSES RETURN', 'desc' => 'SECURITY', 'dashboard_category' => 'Sukses Dikembalikan Ke Kamu'],
            ['code' => 'R05', 'group' => 'SUKSES RETURN', 'desc' => 'MAILING ROOM', 'dashboard_category' => 'Sukses Dikembalikan Ke Kamu'],
            ['code' => 'R06', 'group' => 'SUKSES RETURN', 'desc' => 'SUAMI/ISTRI/ANAK', 'dashboard_category' => 'Sukses Dikembalikan Ke Kamu'],
            ['code' => 'R07', 'group' => 'SUKSES RETURN', 'desc' => 'PEMBANTU', 'dashboard_category' => 'Sukses Dikembalikan Ke Kamu'],
            ['code' => 'R08', 'group' => 'SUKSES RETURN', 'desc' => 'PENJAGA KOS', 'dashboard_category' => 'Sukses Dikembalikan Ke Kamu'],
            ['code' => 'R09', 'group' => 'SUKSES RETURN', 'desc' => 'KELUARGA/SAUDARA', 'dashboard_category' => 'Sukses Dikembalikan Ke Kamu'],
            ['code' => 'R10', 'group' => 'SUKSES RETURN', 'desc' => 'ATASAN/STAFF/KARYAWAN/BAWAHAN', 'dashboard_category' => 'Sukses Dikembalikan Ke Kamu'],
            ['code' => 'R11', 'group' => 'SUKSES RETURN', 'desc' => 'SUPIR', 'dashboard_category' => 'Sukses Dikembalikan Ke Kamu'],
            ['code' => 'R12', 'group' => 'SUKSES RETURN', 'desc' => 'OFFICE BOY', 'dashboard_category' => 'Sukses Dikembalikan Ke Kamu'],
            ['code' => 'R13', 'group' => 'SUKSES RETURN', 'desc' => 'AGEN PENGIRIMAN', 'dashboard_category' => 'Sukses Dikembalikan Ke Kamu'],
            ['code' => 'RC1', 'group' => 'TRANSIT', 'desc' => 'SHIPMENT RECEIVED AT AGEN', 'dashboard_category' => 'Dalam Proses'],
            ['code' => 'TP1', 'group' => 'TRANSIT', 'desc' => 'DEPART FROM TRANSIT', 'dashboard_category' => 'Dalam Proses'],
            ['code' => 'TP4', 'group' => 'TRANSIT', 'desc' => 'PROCESSED AT TRANSIT', 'dashboard_category' => 'Dalam Proses'],
            ['code' => 'TP5', 'group' => 'TRANSIT', 'desc' => 'RECEIVED AT TRANSIT', 'dashboard_category' => 'Dalam Proses'],
            ['code' => 'U01', 'group' => 'UNDEL', 'desc' => 'ALAMAT TIDAK LENGKAP/ TIDAK DIKENAL', 'dashboard_category' => 'Proses Pengembalian Ke Kamu'],
            ['code' => 'U02', 'group' => 'UNDEL', 'desc' => 'PENERIMA TIDAK DI KENAL', 'dashboard_category' => 'Proses Pengembalian Ke Kamu'],
            ['code' => 'U03', 'group' => 'UNDEL', 'desc' => 'PENERIMA PINDAH ALAMAT', 'dashboard_category' => 'Proses Pengembalian Ke Kamu'],
            ['code' => 'U04', 'group' => 'UNDEL', 'desc' => 'PENERIMA MENINGGAL DUNIA', 'dashboard_category' => 'Proses Pengembalian Ke Kamu'],
            ['code' => 'U05', 'group' => 'AU', 'desc' => 'RUMAH /KANTOR KOSONG (MASIH DIHUNI)', 'dashboard_category' => 'Butuh Dicek'],
            ['code' => 'U06', 'group' => 'UNDEL', 'desc' => 'DITOLAK OLEH PENERIMA (NON COD)', 'dashboard_category' => 'Dibatalkan Oleh Kamu'],
            ['code' => 'U07', 'group' => 'UNDEL', 'desc' => 'RUMAH /KANTOR TIDAK DIHUNI', 'dashboard_category' => 'Proses Pengembalian Ke Kamu'],
            ['code' => 'U08', 'group' => 'UNDEL', 'desc' => 'PENERIMA MENOLAK BAYAR (KIRIMAN COD)', 'dashboard_category' => 'Dibatalkan Oleh Kamu'],
            ['code' => 'U09', 'group' => 'AU', 'desc' => 'TUTUP PADA AKHIR PEKAN/ HARI LIBUR (NON COD)', 'dashboard_category' => 'Butuh Dicek'],
            ['code' => 'U10', 'group' => 'AU', 'desc' => 'FORCE MAJEURE', 'dashboard_category' => 'Butuh Dicek'],
            ['code' => 'U11', 'group' => 'UNDEL', 'desc' => 'DAMAGE CASE', 'dashboard_category' => 'Dalam Peninjauan'],
            ['code' => 'U12', 'group' => 'UNDEL', 'desc' => 'MISROUTE', 'dashboard_category' => 'Dalam Peninjauan'],
            ['code' => 'U13', 'group' => 'UNDEL', 'desc' => 'CRISS - CROSS', 'dashboard_category' => 'Proses Pengembalian Ke Kamu'],
            ['code' => 'U14', 'group' => 'UNDEL', 'desc' => 'MISSING', 'dashboard_category' => 'Dalam Peninjauan'],
            ['code' => 'U21', 'group' => 'UNDEL', 'desc' => 'PENERIMA MENOLAK MENERIMA KIRIMAN COD (TDK PESAN)', 'dashboard_category' => 'Dibatalkan Oleh Kamu'],
            ['code' => 'U22', 'group' => 'AU', 'desc' => 'LIBUR CUTI/DINAS LUAR KOTA (KIRIMAN COD)', 'dashboard_category' => 'Butuh Dicek'],
            ['code' => 'U23', 'group' => 'AU', 'desc' => 'TUTUP LIBUR AKHIR PEKAN (KIRIMAN COD)', 'dashboard_category' => 'Butuh Dicek'],
            ['code' => 'U24', 'group' => 'AU', 'desc' => 'MENUNGGU KONFIRMASI NILAI COD', 'dashboard_category' => 'Butuh Dicek'],
            ['code' => 'U25', 'group' => 'AU', 'desc' => 'MENUNGGU PEMBAYARAN COD', 'dashboard_category' => 'Butuh Dicek'],
            ['code' => 'UB2', 'group' => 'UNDEL', 'desc' => 'MELAMPAUI 3 HARI', 'dashboard_category' => 'Proses Pengembalian Ke Kamu'],
            ['code' => 'X1', 'group' => 'ON HOLD', 'desc' => 'SHIPMENT BEING HELD DUE TO DID NOT PASS X-RAY CHECK DANGEROUS GOODS [GATEWAY MEGAHUB]', 'dashboard_category' => 'Butuh Dicek'],
            ['code' => 'X10', 'group' => 'ON HOLD', 'desc' => 'SHIPMENT BEING HELD DUE TO DID NOT PASS X-RAY CHECK -DOCUMENTS ARE NOT COMPLETE [GATEWAY MEGAHUB]', 'dashboard_category' => 'Butuh Dicek'],
        ];

        DB::table('delivery_statuses')->insert($data);
    }
}
