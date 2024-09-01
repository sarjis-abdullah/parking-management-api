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

        $dateWiseTransactions = $queryBuilder->select(
            DB::raw('DATE(created_at) as transaction_date'),
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
            'payment_type',
            'id',
        )
            ->with('vehicle')
            ->groupBy('transaction_date', 'method', 'status', 'received_by', 'parking_id', 'paid_by_vehicle_id', 'discount_amount', 'payment_type', 'id') // Group by all selected fields except for the aggregate fields
            ->orderBy('transaction_date');

        return response()->json([
            'data'=> $dateWiseTransactions->paginate(50),
        ]);
    }

    function getVehicleReport(VehicleIndexRequest $request)
    {
        $queryBuilder = Parking::query();

        if (isset($request->vehicle_id)){
            $queryBuilder = $queryBuilder->where('vehicle_id', $request->vehicle_id);
        }
        if (isset($request['end_date'])) {
            $queryBuilder  =  $queryBuilder->whereDate('in_time', '<=', Carbon::parse($request['end_date']));
        }

        if (isset($request['start_date'])) {
            $queryBuilder =  $queryBuilder->whereDate('in_time', '>=', Carbon::parse($request['start_date']));
        }

        $dateWiseVehicleEntries = $queryBuilder->select(DB::raw('DATE(in_time) as entry_date'), DB::raw('COUNT(id) as vehicle_entries'))
            ->whereNotNull('in_time')
            ->groupBy('entry_date')
            ->orderBy('entry_date')
            ->get();

        return response()->json([
            'data'=> $dateWiseVehicleEntries,
        ]);
    }
}
