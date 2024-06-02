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
        $dateWiseTransactions = $queryBuilder->select(DB::raw('DATE(created_at) as transaction_date'), DB::raw('COUNT(id) as transaction_count'), DB::raw('SUM(payable_amount) as total_payable'), DB::raw('SUM(paid_amount) as total_paid'), DB::raw('SUM(due_amount) as total_due'))

            ->groupBy('transaction_date')
            ->orderBy('transaction_date');

        return response()->json([
            'data'=> $dateWiseTransactions->get(),
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
