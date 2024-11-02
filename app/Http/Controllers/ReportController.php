<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Http\Requests\Report\Transaction\IndexRequest as TransactionIndexRequest;
use App\Http\Requests\Report\Vehicle\IndexRequest as VehicleIndexRequest;
use App\Models\Membership;
use App\Models\Parking;
use App\Models\Payment;
use App\Models\Slot;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReportController
{
    function getTransactionReport(TransactionIndexRequest $request)
    {
        $queryBuilder = Payment::query();
        if (isset($request->vehicle_id)){
            $queryBuilder = $queryBuilder->where('paid_by_vehicle_id', $request->vehicle_id);
        }

        if (isset($request['end_date'])) {
            $queryBuilder  =  $queryBuilder->whereDate('created_at', '<=', Carbon::parse($request['end_date']));
        }

        if (isset($request['start_date'])) {
            $queryBuilder =  $queryBuilder->whereDate('created_at', '>=', Carbon::parse($request['start_date']));
        }
        if (isset($request['payment_type'])) {
            $queryBuilder =  $queryBuilder->where('payment_type', '=', $request['payment_type']);
        }
        if (isset($request['status'])) {
            $queryBuilder =  $queryBuilder->where('status', '=', $request['status']);
        }
        if (isset($request['method'])) {
            $queryBuilder =  $queryBuilder->where('method', '=', $request['method']);
        }
        if (isset($request['transaction_id'])) {
            $queryBuilder =  $queryBuilder->where('transaction_id', '=', $request['transaction_id']);
        }
        if (isset($request['discount_filter']) && $request['discount_filter'] == 'no_discount') {
            $queryBuilder =  $queryBuilder->where('membership_discount', '=', 0)
                ->where('discount_amount', '=', 0);
        }else {
            if (isset($request['discount_filter']) && $request['discount_filter'] == 'membership_discount') {
                $queryBuilder =  $queryBuilder->where('membership_discount', '!=', 0);
            }
            if (isset($request['discount_filter']) && $request['discount_filter'] == 'other_discount') {
                $queryBuilder =  $queryBuilder->where('discount_amount', '!=', 0);
            }
        }

        if (isset($request['category'])){
            $parkingQuery = Parking::query();
            $parkingIds = $parkingQuery->whereHas('category', function($query) use ($request){
                $query->where('id', '=', $request['category']);
            })->pluck('id')->toArray();

            $queryBuilder =  $queryBuilder->whereIn('parking_id', $parkingIds);
        }

        $dateWiseTransactions = $queryBuilder->select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %h:%i %p") as transaction_date'),
            DB::raw('COUNT(id) as transaction_count'),
            DB::raw('SUM(payable_amount) as total_payable'),
            DB::raw('SUM(paid_amount) as total_paid'),
            DB::raw('SUM(due_amount) as total_due'),
            'method', // Add any additional fields you need here
            'status',
            'received_by',
            'parking_id',
            'paid_by_vehicle_id',
            'discount_amount',
            'membership_discount',
            'payment_type',
            'paid_now',
            'id',
            'transaction_id',
        )
            ->with('vehicle')
            ->groupBy('transaction_date', 'method', 'status', 'received_by', 'parking_id', 'paid_by_vehicle_id', 'discount_amount', 'payment_type', 'id', 'paid_now', 'membership_discount', 'transaction_id') // Group by all selected fields except for the aggregate fields
            ->orderBy('transaction_date');

        $limit = !empty($request['per_page']) ? (int)$request['per_page'] : 50; // it's needed for pagination
        $orderBy = !empty($request['order_by']) ? $request['order_by'] : 'id';
        $orderDirection = !empty($request['order_direction']) ? $request['order_direction'] : 'desc';
        $queryBuilder->orderBy($orderBy, $orderDirection);

        $allTransactions = $dateWiseTransactions->paginate($limit);
        $pdfUrl = '';
        if ($request->get('format') == 'pdf') {

            $data = ['transactions' => $allTransactions, 'totals' => $this->prepareTotals($allTransactions)];
            if ($request->get('load') == 'view'){
                return view('transactions', $data);
            }

            $pdf = PDF::loadView('transactions', $data);
            $filePath = 'transactions.pdf';

            Storage::disk('public')->put($filePath, $pdf->stream('addendum.pdf'), 'public');

            $pdfUrl = asset('storage/transactions.pdf');
        }
        return response()->json([
            'data'=> $dateWiseTransactions->paginate($limit),
            'pdfUrl'=> $pdfUrl,
        ]);
    }

    function prepareTotals($transactions)
    {
        $acc = [
            'payable' => 0,
            'paid' => 0,
            // 'pending_payment' => 0,
            'discount' => 0,
            'due' => 0,
        ];

        if ($transactions && count($transactions)) {
            foreach ($transactions as $payment) {
                if ($payment['status'] === 'success') {
                    $acc['paid'] += (float) $payment['total_paid'];
                }
                $acc['payable'] += (float) $payment['total_payable'];
                $acc['due'] += (float) $payment['total_due'];
                $acc['discount'] += (float) $payment['discount_amount'];
                $acc['discount'] += (float) $payment['membership_discount'];
            }
        }

        return $acc;
    }

    function getVehicleReport(VehicleIndexRequest $request)
    {
        $queryBuilder = Parking::query();

        if (isset($request->vehicle_id)){
            $queryBuilder = $queryBuilder->where('vehicle_id', $request->vehicle_id);
        }

        if (isset($request['end_date'])) {
            $queryBuilder = $queryBuilder->whereDate('in_time', '<=', Carbon::parse($request['end_date']));
        }

        if (isset($request['start_date'])) {
            $queryBuilder = $queryBuilder->whereDate('in_time', '>=', Carbon::parse($request['start_date']));
        }

        $dateWiseVehicleEntries = $queryBuilder->select(
            DB::raw('DATE(in_time) as entry_date'),   // Extract the date part from in_time
            DB::raw('COUNT(id) as vehicle_entries')   // Count the vehicles for each date
        )
            ->whereNotNull('in_time')                     // Make sure in_time is not null
            ->groupBy('entry_date')                       // Group by the date part
            ->orderBy($orderBy ?? 'entry_date', $orderDirection ?? 'desc'); // Order results

        $limit = !empty($request['per_page']) ? (int) $request['per_page'] : 50;

        return response()->json([
            'data' => $dateWiseVehicleEntries->paginate($limit),  // Paginate the results
        ]);
    }

    function getDetailVehicleReport(Request $request)
    {
        $detailedEntries = Parking::query()
            ->select(
//                'place_id',
//                'category_id',
//                'slot_id',
//                'floor_id',
//                'tariff_id',
                'vehicle_id',
//                'barcode',
                'in_time',
                'out_time',
//                'duration',
//                'created_by',
//                'updated_by',
//                'deleted_by'
            )
            ->with('vehicle')
            ->whereDate('in_time', '=', Carbon::parse($request->entry_date)) // Get records for the selected date
            ->whereNotNull('in_time')
            ->get();

        return response()->json([
            'data' => [
                'details' => $detailedEntries  // Detailed vehicle entries for the clicked date
            ]  // Detailed vehicle entries for the clicked date
        ]);
    }

    function getSlotReport()
    {
        $bookedSlots = Slot::where('status', 'occupied')->count();
        $availableSlots = Slot::where('status', 'available')->count();
        $totalUser = User::count();
        $totalMember = Membership::count();
        $payments = Payment::where('status', '=', PaymentStatus::success)->whereDate('created_at', Carbon::today())->sum('paid_amount');


        return response()->json([
            'data'=> [
                'Total slots' => $bookedSlots + $availableSlots,
                'Currently parking' => $bookedSlots,
                'Available slots' => $availableSlots,
                'Total user' => $totalUser,
                'Total membership' => $totalMember,
                "Today's total collection" => $payments,
            ],
        ]);
    }
}
