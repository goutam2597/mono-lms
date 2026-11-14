<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * An inbound SMS forwarded by an Android gateway device.
 */
class SmsInboundMessage extends Model
{
    protected $fillable = [
        'sms_gateway_device_id',
        'message_uid',
        'from_phone',
        'body',
        'sim_number',
        'subscription_id',
        'received_at',
    ];

    protected $casts = [
        'received_at' => 'datetime',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(SmsGatewayDevice::class, 'sms_gateway_device_id');
    }
}
