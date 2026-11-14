<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add canonical phone columns alongside the raw `phone` text columns.
     * `phone_e164` carries the dial-code-prefixed canonical value the SMS
     * transports deliver to; `phone_country` keeps the ISO-2 code of the
     * country whose dial code was matched at parse time.
     */
    public function up(): void
    {
        foreach (['students', 'instructors', 'admins'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->string('phone_e164', 16)->nullable()->index()->after('phone');
                $table->string('phone_country', 2)->nullable()->after('phone_e164');
            });
        }
    }

    public function down(): void
    {
        foreach (['students', 'instructors', 'admins'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropIndex(['phone_e164']);
                $table->dropColumn(['phone_e164', 'phone_country']);
            });
        }
    }
};
