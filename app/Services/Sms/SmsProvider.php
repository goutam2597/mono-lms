<?php

namespace App\Services\Sms;

interface SmsProvider
{
    /** Transport identifier (twilio | gateway). */
    public function name(): string;

    /** Whether the transport has the configuration/device needed to send. */
    public function configured(): bool;

    /**
     * Send an SMS.
     *
     * @return array{ok: bool, id?: string, error?: string}
     */
    public function send(string $to, string $body, string $kind = 'other'): array;
}
