<?php

namespace App\Http\Controllers;

use App\Http\Requests\Report\Transaction\IndexRequest as TransactionIndexRequest;
use App\Http\Requests\Report\Vehicle\IndexRequest as VehicleIndexRequest;
use App\Models\Parking;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController
{
    function getTransactionReport(TransactionIndexRequest $request)
    {
        $startDate = Carbon::parse('2024-01-01');
        $endDate = Carbon::parse('2024-07-31');

        $paymentQuery = Payment::query();
        if (isset($request->paid_by_vehicle_id)){
            $paymentQuery = $paymentQuery->where('paid_by_vehicle_id', $request->paid_by_vehicle_id);
        }
        $dateWiseTransactions = $paymentQuery->select(DB::raw('DATE(created_at) as transaction_date'), DB::raw('COUNT(id) as transaction_count'), DB::raw('SUM(payable_amount) as total_payable'), DB::raw('SUM(paid_amount) as total_paid'), DB::raw('SUM(due_amount) as total_due'))
            ->whereBetween('created_at', [$startDate, $endDate])

            ->groupBy('transaction_date')
            ->orderBy('transaction_date');

        return response()->json([
            'data'=> $dateWiseTransactions->get(),
        ]);
    }

    function getVehicleReport(VehicleIndexRequest $request)
    {
        $startDate = Carbon::parse('2024-01-01');
        $endDate = Carbon::parse('2024-07-31');

        $dateWiseVehicleEntries = Parking::select(DB::raw('DATE(in_time) as entry_date'), DB::raw('COUNT(id) as vehicle_entries'))
            ->whereNotNull('in_time')
            ->whereBetween('in_time', [$startDate, $endDate])
            ->groupBy('entry_date')
            ->orderBy('entry_date')
            ->get();

        return response()->json([
            'data'=> $dateWiseVehicleEntries,
        ]);
    }
}
