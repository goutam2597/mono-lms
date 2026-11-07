<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $languages = [
            ['code' => 'en', 'name' => 'English', 'native_name' => 'English', 'direction' => 'ltr', 'flag' => 'us', 'is_active' => true, 'is_default' => true, 'sort_order' => 1],
            ['code' => 'hi', 'name' => 'Hindi', 'native_name' => 'हिन्दी', 'direction' => 'ltr', 'flag' => 'in', 'is_active' => true, 'is_default' => false, 'sort_order' => 2],
            ['code' => 'ar', 'name' => 'Arabic', 'native_name' => 'العربية', 'direction' => 'rtl', 'flag' => 'sa', 'is_active' => true, 'is_default' => false, 'sort_order' => 3],
        ];

        foreach ($languages as $language) {
            Language::updateOrCreate(['code' => $language['code']], $language);
        }

        Language::clearCache();
    }
}
