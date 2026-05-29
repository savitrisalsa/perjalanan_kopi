<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('payment')->latest();

        if ($request->search) {
            $query->where('customer_name', 'like', '%' . $request->search . '%');
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(5)->withQueryString();

        return view('orders.index', compact('orders'));
    }

    public function create(Request $request)
    {
        $query = Product::where('status', 'available')
            ->where('stock', '>', 0);

        if ($request->category) {
            $query->where('category', $request->category);
        }

        $products = $query->get();

        return view('orders.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required',
            'order_type' => 'required',
            'discount_amount' => 'nullable|integer|min:0',
        ]);

        if (!$request->products) {
            return back()->with('error', 'Pilih minimal satu menu.');
        }

        DB::beginTransaction();

        try {
            $discount = (int) ($request->discount_amount ?? 0);

            $order = Order::create([
                'user_id' => auth()->id(),
                'table_id' => null,
                'customer_name' => $request->customer_name,
                'order_time' => now(),
                'order_type' => $request->order_type,
                'note' => $request->note,
                'status' => 'pending',
                'total_price' => 0,
                'discount_amount' => $discount,
            ]);

            $subtotalAll = 0;

            foreach ($request->products as $product_id) {
                $product = Product::findOrFail($product_id);
                $qty = (int) $request->quantities[$product_id];

                if ($qty < 1) {
                    DB::rollBack();
                    return back()->with('error', 'Jumlah pesanan tidak valid.');
                }

                if ($product->stock < $qty) {
                    DB::rollBack();
                    return back()->with('error', 'Stok ' . $product->name . ' tidak cukup.');
                }

                $subtotal = $product->selling_price * $qty;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'selling_price' => $product->selling_price,
                    'hpp' => $product->hpp,
                    'subtotal' => $subtotal,
                ]);

                $product->stock -= $qty;

                if ($product->stock <= 0) {
                    $product->status = 'unavailable';
                }

                $product->save();

                $subtotalAll += $subtotal;
            }

            if ($discount > $subtotalAll) {
                DB::rollBack();
                return back()->with('error', 'Diskon tidak boleh lebih besar dari total pesanan.');
            }

            $order->update([
                'total_price' => $subtotalAll - $discount,
            ]);

            DB::commit();

            return redirect('/orders')->with('success', 'Order berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Order gagal dibuat.');
        }
    }

    public function edit(Order $order)
    {
        if ($order->status == 'completed') {
            return redirect('/orders')->with('error', 'Order yang sudah lunas tidak bisa diedit.');
        }

        $order->load('items');

        $products = Product::where('status', 'available')
            ->orWhereIn('id', $order->items->pluck('product_id'))
            ->get();

        return view('orders.edit', compact('order', 'products'));
    }

    public function update(Request $request, Order $order)
    {
        if ($order->status == 'completed') {
            return redirect('/orders')->with('error', 'Order yang sudah lunas tidak bisa diedit.');
        }

        $request->validate([
            'customer_name' => 'required',
            'order_type' => 'required',
            'discount_amount' => 'nullable|integer|min:0',
        ]);

        if (!$request->products) {
            return back()->with('error', 'Pilih minimal satu menu.');
        }

        DB::beginTransaction();

        try {
            foreach ($order->items as $oldItem) {
                if ($oldItem->product) {
                    $oldItem->product->stock += $oldItem->quantity;
                    $oldItem->product->status = 'available';
                    $oldItem->product->save();
                }
            }

            $order->items()->delete();

            $discount = (int) ($request->discount_amount ?? 0);
            $subtotalAll = 0;

            foreach ($request->products as $product_id) {
                $product = Product::findOrFail($product_id);
                $qty = (int) $request->quantities[$product_id];

                if ($qty < 1) {
                    DB::rollBack();
                    return back()->with('error', 'Jumlah pesanan tidak valid.');
                }

                if ($product->stock < $qty) {
                    DB::rollBack();
                    return back()->with('error', 'Stok ' . $product->name . ' tidak cukup.');
                }

                $subtotal = $product->selling_price * $qty;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'selling_price' => $product->selling_price,
                    'hpp' => $product->hpp,
                    'subtotal' => $subtotal,
                ]);

                $product->stock -= $qty;

                if ($product->stock <= 0) {
                    $product->status = 'unavailable';
                }

                $product->save();

                $subtotalAll += $subtotal;
            }

            if ($discount > $subtotalAll) {
                DB::rollBack();
                return back()->with('error', 'Diskon tidak boleh lebih besar dari total pesanan.');
            }

            $order->update([
                'customer_name' => $request->customer_name,
                'order_type' => $request->order_type,
                'note' => $request->note,
                'discount_amount' => $discount,
                'total_price' => $subtotalAll - $discount,
            ]);

            DB::commit();

            return redirect('/orders')->with('success', 'Order berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Order gagal diperbarui.');
        }
    }

    public function updateStatus(Request $request, Order $order)
    {
        if ($order->status == 'completed') {
            return back()->with('error', 'Order sudah selesai/lunas.');
        }

        $request->validate([
            'status' => 'required|in:pending,processing,ready',
        ]);

        $order->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Status order berhasil diperbarui.');
    }

    public function detail(Order $order)
    {
        $order->load(['items.product', 'payment']);

        return view('orders.detail', compact('order'));
    }

    public function payment(Order $order)
    {
        if ($order->status == 'completed') {
            return redirect('/orders')->with('error', 'Order ini sudah lunas.');
        }

        return view('orders.payment', compact('order'));
    }

    public function processPayment(Request $request, Order $order)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,qris',
            'amount_paid' => 'nullable|integer|min:0',
        ]);

        if ($order->status == 'completed') {
            return redirect('/orders')->with('error', 'Order ini sudah lunas.');
        }

        if ($request->payment_method == 'cash') {
            $amountPaid = (int) $request->amount_paid;

            if ($amountPaid < $order->total_price) {
                return back()->with('error', 'Uang pembayaran kurang.');
            }

            $change = $amountPaid - $order->total_price;
        } else {
            $amountPaid = $order->total_price;
            $change = 0;
        }

        Payment::create([
            'order_id' => $order->id,
            'payment_method' => $request->payment_method,
            'amount_paid' => $amountPaid,
            'change_amount' => $change,
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

        $order->update([
            'status' => 'completed',
        ]);

        return redirect('/orders/' . $order->id . '/receipt')->with('success', 'Pembayaran berhasil.');
    }

    public function receipt(Order $order)
    {
        $order->load(['items.product', 'payment']);

        return view('orders.receipt', compact('order'));
    }

    public function destroy(Order $order)
    {
        DB::beginTransaction();

        try {
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->stock += $item->quantity;

                    if ($item->product->stock > 0) {
                        $item->product->status = 'available';
                    }

                    $item->product->save();
                }
            }

            $order->items()->delete();

            if ($order->payment) {
                $order->payment->delete();
            }

            $order->delete();

            DB::commit();

            return redirect('/orders')->with('success', 'Order berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}