<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    private function getReportData(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;
        $period = $request->period;

        if ($period == 'today') {
            $start = Carbon::today()->format('Y-m-d');
            $end = Carbon::today()->format('Y-m-d');
        }

        if ($period == 'week') {
            $start = Carbon::now()->startOfWeek()->format('Y-m-d');
            $end = Carbon::now()->endOfWeek()->format('Y-m-d');
        }

        if ($period == 'month') {
            $start = Carbon::now()->startOfMonth()->format('Y-m-d');
            $end = Carbon::now()->endOfMonth()->format('Y-m-d');
        }

        $ordersQuery = Order::with(['payment', 'items.product'])
            ->where('status', 'completed');

        if ($start && $end) {
            $ordersQuery->whereDate('order_time', '>=', $start)
                ->whereDate('order_time', '<=', $end);
        }

        $orders = $ordersQuery->latest()->get();

        $totalOrders = $orders->count();
        $totalIncome = $orders->sum('total_price');
        $totalDiscount = $orders->sum('discount_amount');
        $averageTransaction = $totalOrders > 0 ? $totalIncome / $totalOrders : 0;

        $items = OrderItem::with(['product', 'order'])
            ->whereHas('order', function ($query) use ($start, $end) {
                $query->where('status', 'completed');

                if ($start && $end) {
                    $query->whereDate('order_time', '>=', $start)
                        ->whereDate('order_time', '<=', $end);
                }
            })
            ->get();

        $totalItemsSold = $items->sum('quantity');

        $totalHpp = $items->sum(function ($item) {
            return $item->hpp * $item->quantity;
        });

        $totalProfit = $items->sum(function ($item) {
            return ($item->selling_price - $item->hpp) * $item->quantity;
        }) - $totalDiscount;

        $menusSummary = $items
            ->groupBy('product_id')
            ->map(function ($group) {
                $first = $group->first();

                return [
                    'name' => $first->product->name ?? '-',
                    'total_qty' => $group->sum('quantity'),
                    'total_income' => $group->sum('subtotal'),
                    'total_profit' => $group->sum(function ($item) {
                        return ($item->selling_price - $item->hpp) * $item->quantity;
                    }),
                ];
            });

        return [
            'orders' => $orders,
            'items' => $items,
            'totalOrders' => $totalOrders,
            'totalIncome' => $totalIncome,
            'totalHpp' => $totalHpp,
            'totalProfit' => $totalProfit,
            'totalDiscount' => $totalDiscount,
            'totalItemsSold' => $totalItemsSold,
            'averageTransaction' => $averageTransaction,
            'bestSellingMenus' => $menusSummary->sortByDesc('total_qty'),
            'mostProfitableMenus' => $menusSummary->sortByDesc('total_profit'),
            'start' => $start,
            'end' => $end,
            'period' => $period,
        ];
    }

    public function index(Request $request)
    {
        return view('reports.index', $this->getReportData($request));
    }

    public function print(Request $request)
    {
        return view('reports.print', $this->getReportData($request));
    }

    public function exportPdf(Request $request)
    {
        $pdf = Pdf::loadView('reports.pdf', $this->getReportData($request))
            ->setPaper('a4', 'portrait');

        return $pdf->download('laporan-penjualan.pdf');
    }

    public function exportCsv(Request $request)
    {
        $data = $this->getReportData($request);

        $filename = 'laporan-penjualan.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');

            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fwrite($file, "sep=;\n");

            fputcsv($file, ['PERJALANAN KOPI'], ';');
            fputcsv($file, ['LAPORAN PENJUALAN'], ';');
            fputcsv($file, ['Periode', ($data['start'] ?? '-') . ' sampai ' . ($data['end'] ?? '-')], ';');
            fputcsv($file, ['Tanggal Cetak', now()->format('d-m-Y H:i:s')], ';');
            fputcsv($file, ['Dicetak Oleh', auth()->user()->name ?? '-'], ';');
            fputcsv($file, [], ';');

            fputcsv($file, ['RINGKASAN LAPORAN'], ';');
            fputcsv($file, ['Total Order', 'Total Item Terjual', 'Pendapatan', 'Total HPP', 'Total Diskon', 'Keuntungan', 'Rata-rata Transaksi'], ';');
            fputcsv($file, [
                $data['totalOrders'],
                $data['totalItemsSold'],
                $data['totalIncome'],
                $data['totalHpp'],
                $data['totalDiscount'],
                $data['totalProfit'],
                round($data['averageTransaction']),
            ], ';');

            fputcsv($file, [], ';');

            fputcsv($file, ['MENU PALING LAKU'], ';');
            fputcsv($file, ['No', 'Nama Menu', 'Total Terjual', 'Total Pendapatan', 'Total Keuntungan'], ';');

            foreach ($data['bestSellingMenus'] as $index => $menu) {
                fputcsv($file, [
                    $index + 1,
                    $menu['name'],
                    $menu['total_qty'],
                    $menu['total_income'],
                    $menu['total_profit'],
                ], ';');
            }

            fputcsv($file, [], ';');

            fputcsv($file, ['MENU PALING UNTUNG'], ';');
            fputcsv($file, ['No', 'Nama Menu', 'Total Terjual', 'Total Pendapatan', 'Total Keuntungan'], ';');

            foreach ($data['mostProfitableMenus'] as $index => $menu) {
                fputcsv($file, [
                    $index + 1,
                    $menu['name'],
                    $menu['total_qty'],
                    $menu['total_income'],
                    $menu['total_profit'],
                ], ';');
            }

            fputcsv($file, [], ';');

            fputcsv($file, ['DETAIL ORDER SELESAI'], ';');
            fputcsv($file, ['No', 'Customer', 'Total', 'Diskon', 'Metode Bayar', 'Waktu', 'Item Pesanan'], ';');

            foreach ($data['orders'] as $index => $order) {
                $itemsText = $order->items->map(function ($item) {
                    return ($item->product->name ?? '-') . ' x' . $item->quantity;
                })->implode(', ');

                fputcsv($file, [
                    $index + 1,
                    $order->customer_name,
                    $order->total_price,
                    $order->discount_amount ?? 0,
                    $order->payment->payment_method ?? '-',
                    $order->order_time,
                    $itemsText,
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportExcel(Request $request)
    {
        $data = $this->getReportData($request);

        $filename = 'laporan-penjualan.xls';

        return response()
            ->view('reports.excel', $data)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}