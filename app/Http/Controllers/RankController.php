<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Rank;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RankController extends Controller
{
    public function index(): View
    {
        $games = Game::with('ranks')->get();

        return view('developer.ranks.index', compact('games'));
    }

    public function create(): View
    {
        $games = Game::all();

        return view('developer.ranks.create', compact('games'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'game_id' => ['required', 'exists:games,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:ranks,slug'],
            'star_system' => ['required', 'string', 'max:50'],
            'min_star' => ['nullable', 'integer', 'min:0'],
            'max_star' => ['nullable', 'integer', 'min:0'],
            'icon_path' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        if ($request->hasFile('icon_path')) {
            $validated['icon_path'] = $request->file('icon_path')->store('ranks', 'public');
        }

        Rank::create($validated);

        return redirect()->route('developer.ranks.index')->with('success', 'Rank berhasil disimpan.');
    }

    public function show(Rank $rank): View
    {
        return view('developer.ranks.show', compact('rank'));
    }

    public function edit(Rank $rank): View
    {
        $games = Game::all();

        return view('developer.ranks.edit', compact('rank', 'games'));
    }

    public function update(Request $request, Rank $rank): RedirectResponse
    {
        $validated = $request->validate([
            'game_id' => ['required', 'exists:games,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:ranks,slug,'.$rank->id],
            'star_system' => ['required', 'string', 'max:50'],
            'min_star' => ['nullable', 'integer', 'min:0'],
            'max_star' => ['nullable', 'integer', 'min:0'],
            'icon_path' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        if ($request->hasFile('icon_path')) {
            $validated['icon_path'] = $request->file('icon_path')->store('ranks', 'public');
        }

        $rank->update($validated);

        return redirect()->route('developer.ranks.index')->with('success', 'Rank berhasil diperbarui.');
    }

    public function destroy(Rank $rank): RedirectResponse
    {
        $rank->delete();

        return redirect()->route('developer.ranks.index')->with('success', 'Rank berhasil dihapus.');
    }
}
