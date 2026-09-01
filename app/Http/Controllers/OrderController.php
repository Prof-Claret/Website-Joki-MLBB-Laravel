<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Order;
use App\Models\Review;
use App\Models\Service;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = Order::with(['user', 'worker', 'game', 'service'])->latest()->get();

        return view('admin.orders.index', compact('orders'));
    }

    public function create(): View
    {
        $games = Game::with('services')->get();

        return view('orders.create', compact('games'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'game_id' => ['required', 'exists:games,id'],
            'service_id' => ['required', 'exists:services,id'],
            'rank_from_id' => ['nullable', 'exists:ranks,id'],
            'rank_to_id' => ['nullable', 'exists:ranks,id'],
            'wa_number' => ['required', 'string', 'max:20'],
            'request_hero' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'payment_method' => ['nullable', 'in:midtrans,manual'],
        ]);

        $service = Service::findOrFail($validated['service_id']);
        $price = $this->calculatePrice($validated, $service);

        $order = Order::create([
            'user_id' => auth()->id(),
            'game_id' => $validated['game_id'],
            'service_id' => $validated['service_id'],
            'rank_from_id' => $validated['rank_from_id'] ?? null,
            'rank_to_id' => $validated['rank_to_id'] ?? null,
            'order_number' => 'ORD-'.strtoupper(Str::random(8)),
            'status' => 'pending',
            'priority' => 'normal',
            'price' => $price,
            'payment_method' => $validated['payment_method'] ?? 'midtrans',
            'payment_status' => 'pending',
            'wa_number' => $validated['wa_number'],
            'account_credentials' => encrypt($request->input('account_credentials') ?? ''),
            'request_hero' => $validated['request_hero'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'tracking_code' => 'TRK-'.strtoupper(Str::random(10)),
            'worker_progress' => 0,
        ]);

        Transaction::create([
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'transaction_id' => 'TX-'.strtoupper(Str::random(10)),
            'gateway' => $order->payment_method,
            'amount' => $order->price,
            'fee' => 0,
            'status' => 'pending',
            'payment_type' => $order->payment_method,
        ]);

        return redirect()->route('orders.show', $order)->with('success', 'Pesanan berhasil dibuat.');
    }

    public function show(Order $order): View
    {
        $order->load(['user', 'worker', 'game', 'service', 'proofs']);

        return view('orders.show', compact('order'));
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string'],
            'worker_id' => ['nullable', 'exists:users,id'],
            'payment_status' => ['nullable', 'string'],
        ]);

        $order->update($validated);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function updateProgress(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'worker_progress' => ['required', 'integer', 'min:0', 'max:100'],
            'status' => ['nullable', 'string'],
        ]);

        $order->update([
            'worker_progress' => $validated['worker_progress'],
            'status' => $validated['status'] ?? $order->status,
        ]);

        return redirect()->back()->with('success', 'Progress pekerja berhasil diperbarui.');
    }

    public function uploadProof(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'image', 'max:2048'],
            'caption' => ['nullable', 'string', 'max:255'],
        ]);

        $path = $request->file('file')->store('order-proofs', 'public');

        $order->proofs()->create([
            'uploaded_by_user_id' => auth()->id(),
            'type' => 'screenshot',
            'file_path' => $path,
            'caption' => $validated['caption'] ?? null,
            'is_verified' => false,
        ]);

        return redirect()->back()->with('success', 'Bukti upload berhasil dikirim.');
    }

    public function storeReview(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $review = Review::updateOrCreate(
            ['order_id' => $order->id, 'user_id' => auth()->id()],
            [
                'worker_id' => $order->worker_id,
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
                'is_visible' => true,
            ]
        );

        $order->update([
            'customer_rating' => $validated['rating'],
            'customer_review' => $review->comment,
        ]);

        return redirect()->back()->with('success', 'Ulasan berhasil dikirim.');
    }

    protected function calculatePrice(array $data, Service $service): float
    {
        $from = $data['rank_from_id'] ?? null;
        $to = $data['rank_to_id'] ?? null;

        $base = (float) $service->base_price;
        $perStar = (float) $service->price_per_star;

        if ($from && $to) {
            $fromStar = (int) $service->rankFrom?->max_star ?? 0;
            $toStar = (int) $service->rankTo?->max_star ?? 0;

            $diff = max(0, $toStar - $fromStar);

            return $base + ($diff * $perStar);
        }

        return $base;
    }
}
