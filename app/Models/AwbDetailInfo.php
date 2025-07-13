<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AwbDetailInfo extends Model
{
    protected $table = "awb_detail_infos";

    protected $fillable = [
        'awb_tracking_id',
        'reference_number',
        'origin',
        'destination',
        'service_code',
        'service_type',
        'cust_no',
        'cnote_date',
        'goods_description',
        'amount',
        'weight',
        'shipper_name',
        'shipper_address',
        'shipper_city',
        'receiver_name',
        'receiver_address',
        'receiver_city',
    ];

    public function tracking()
    {
        return $this->belongsTo(AwbTracking::class);
    }
}
