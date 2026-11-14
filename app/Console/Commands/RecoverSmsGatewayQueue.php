<?php

namespace App\Console\Commands;

use App\Services\Sms\SmsGatewayService;
use Illuminate\Console\Command;

/**
 * Queue recovery safety net: reclaims stuck claims, parks exhausted messages, and re-nudges pending items.
 */
class RecoverSmsGatewayQueue extends Command
{
    protected $signature = 'sms-gateway:recover';

    protected $description = 'Reclaim stuck SMS gateway claims and re-nudge pending messages';

    public function handle(SmsGatewayService $service): int
    {
        $result = $service->recover();

        if ($result['reclaimed'] > 0 || $result['nudged'] > 0) {
            $this->line(sprintf(
                'Reclaimed %d stuck claim(s), nudged %d pending message(s).',
                $result['reclaimed'],
                $result['nudged'],
            ));
        }

        return self::SUCCESS;
    }
}
