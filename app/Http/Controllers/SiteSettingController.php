<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteSettingController extends Controller
{
    public function index(): View
    {
        $settings = SiteSetting::orderBy('group_name')->orderBy('setting_key')->get();

        return view('developer.settings.index', compact('settings'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'setting_key' => ['required', 'string', 'max:255', 'unique:site_settings,setting_key'],
            'setting_value' => ['nullable'],
            'type' => ['required', 'in:string,boolean,json,integer,text'],
            'group_name' => ['required', 'string', 'max:100'],
            'is_public' => ['boolean'],
            'is_active' => ['boolean'],
        ]);

        SiteSetting::create($validated);

        return redirect()->route('developer.settings.index')->with('success', 'Pengaturan situs berhasil ditambahkan.');
    }

    public function update(Request $request, SiteSetting $setting): RedirectResponse
    {
        $validated = $request->validate([
            'setting_key' => ['required', 'string', 'max:255', 'unique:site_settings,setting_key,'.$setting->id],
            'setting_value' => ['nullable'],
            'type' => ['required', 'in:string,boolean,json,integer,text'],
            'group_name' => ['required', 'string', 'max:100'],
            'is_public' => ['boolean'],
            'is_active' => ['boolean'],
        ]);

        $setting->update($validated);

        return redirect()->route('developer.settings.index')->with('success', 'Pengaturan situs berhasil diperbarui.');
    }
}
