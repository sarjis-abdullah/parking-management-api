<?php

namespace App\Http\Controllers;

use App\Http\Requests\Report\Transaction\IndexRequest as TransactionIndexRequest;
use App\Http\Requests\Report\Vehicle\IndexRequest as VehicleIndexRequest;
use App\Models\Membership;
use App\Models\Parking;
use App\Models\Payment;
use App\Models\Slot;
use App\Models\User;
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

        $limit = !empty($request['per_page']) ? (int)$request['per_page'] : 50; // it's needed for pagination
        $orderBy = !empty($request['order_by']) ? $request['order_by'] : 'id';
        $orderDirection = !empty($request['order_direction']) ? $request['order_direction'] : 'desc';
        $queryBuilder->orderBy($orderBy, $orderDirection);

        return response()->json([
            'data'=> $dateWiseTransactions->paginate($limit),
        ]);
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

    function getSlotReport()
    {
        $bookedSlots = Slot::where('status', 'occupied')->count();
        $availableSlots = Slot::where('status', 'available')->count();
        $totalUser = User::count();
        $totalMember = Membership::count();

        return response()->json([
            'data'=> [
                'Total slots' => $bookedSlots + $availableSlots,
                'Currently parking' => $bookedSlots,
                'Available slots' => $availableSlots,
                'Total user' => $totalUser,
                'Total membership' => $totalMember,
            ],
        ]);
    }
}
