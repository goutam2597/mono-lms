<?php

namespace App\Console\Commands;

use App\Services\CheckoutService;
use Illuminate\Console\Command;

final class CancelExpiredOrders extends Command
{
    protected $signature = 'orders:cancel-expired';

    protected $description = 'Cancel unpaid course orders whose 60-minute payment reservation expired';

    public function handle(CheckoutService $checkout): int
    {
        $cancelled = $checkout->cancelExpired();

        if ($cancelled > 0) {
            $this->info("Cancelled {$cancelled} expired order(s).");
        }

        return self::SUCCESS;
    }
}
