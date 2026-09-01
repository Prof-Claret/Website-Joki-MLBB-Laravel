<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MidtransCallbackController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();

        $transactionId = $payload['transaction_id'] ?? $payload['order_id'] ?? null;

        if (! $transactionId) {
            return response()->json(['message' => 'Invalid payload.'], 400);
        }

        $transaction = Transaction::where('transaction_id', $transactionId)
            ->orWhere('id', $transactionId)
            ->first();

        if (! $transaction) {
            return response()->json(['message' => 'Transaction not found.'], 404);
        }

        $status = $payload['transaction_status'] ?? 'pending';
        $paymentStatus = match ($status) {
            'capture', 'settlement' => 'paid',
            'pending' => 'pending',
            'deny', 'expire', 'cancel' => 'failed',
            default => 'pending',
        };

        $transaction->update([
            'status' => $paymentStatus,
            'payload' => $payload,
            'paid_at' => $paymentStatus === 'paid' ? now() : null,
        ]);

        if ($transaction->order) {
            $transaction->order->update([
                'payment_status' => $paymentStatus,
                'status' => $paymentStatus === 'paid' ? 'paid' : $transaction->order->status,
            ]);
        }

        return response()->json(['message' => 'Callback processed successfully.']);
    }
}
