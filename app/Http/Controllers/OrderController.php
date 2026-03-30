<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'orderItems.medication']);

        // Filter by lot number if provided
        if ($request->has('lot')) {
            $query->whereHas('orderItems.medication', function ($q) use ($request) {
                $q->where('lot_number', $request->lot);
            });
        }

        // Filter by date range if provided
        if ($request->has('start_date') && $request->start_date) {
            $query->where('purchase_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->where('purchase_date', '<=', $request->end_date);
        }

        $orders = $query->orderBy('purchase_date', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    public function show($id)
    {
        $order = Order::with(['customer', 'orderItems.medication'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }

    public function sendAlert(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
            'lot_number' => 'required|string'
        ]);

        $order = Order::with('customer')->findOrFail($id);

        try {
            Mail::to($order->customer->email)->send(
                new \App\Mail\CustomerAlertMail(
                    customerName: $order->customer->name,
                    alertMessage: $request->message,
                    lotNumber: $request->lot_number,
                    orderId: $order->id
                )
            );

            return response()->json([
                'success' => true,
                'message' => 'Alert sent successfully to ' . $order->customer->email
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send alert: ' . $e->getMessage()
            ], 500);
        }
    }
}
