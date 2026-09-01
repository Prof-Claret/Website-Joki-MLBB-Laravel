<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['setting_key' => 'app_name', 'setting_value' => 'Escam Joki', 'type' => 'string', 'group_name' => 'general', 'is_public' => true, 'is_active' => true],
            ['setting_key' => 'app_tagline', 'setting_value' => 'Joki Game Multi Platform', 'type' => 'string', 'group_name' => 'branding', 'is_public' => true, 'is_active' => true],
            ['setting_key' => 'logo_path', 'setting_value' => '', 'type' => 'string', 'group_name' => 'branding', 'is_public' => true, 'is_active' => true],
            ['setting_key' => 'favicon_path', 'setting_value' => '', 'type' => 'string', 'group_name' => 'branding', 'is_public' => true, 'is_active' => true],
            ['setting_key' => 'instagram_url', 'setting_value' => 'https://instagram.com', 'type' => 'string', 'group_name' => 'social', 'is_public' => true, 'is_active' => true],
            ['setting_key' => 'whatsapp_url', 'setting_value' => 'https://wa.me/6280000000000', 'type' => 'string', 'group_name' => 'social', 'is_public' => true, 'is_active' => true],
            ['setting_key' => 'telegram_url', 'setting_value' => '', 'type' => 'string', 'group_name' => 'social', 'is_public' => true, 'is_active' => true],
            ['setting_key' => 'admin_name', 'setting_value' => 'Admin Escam', 'type' => 'string', 'group_name' => 'profile', 'is_public' => true, 'is_active' => true],
        ];

        foreach ($settings as $setting) {
            SiteSetting::query()->updateOrCreate(
                ['setting_key' => $setting['setting_key']],
                $setting,
            );
        }
    }
}
