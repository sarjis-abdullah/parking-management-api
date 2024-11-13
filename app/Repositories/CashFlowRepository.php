<?php

namespace App\Repositories;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Exceptions\CustomValidationException;
use App\Models\CashFlow;
use App\Models\Payment;
use App\Models\PaymentLog;
use App\Repositories\Contracts\CashFlowInterface;
use App\Repositories\Contracts\UserInterface;
use Carbon\Carbon;

class CashFlowRepository extends EloquentBaseRepository implements CashFlowInterface
{
    /*
    * @inheritdoc
    */
    public function findBy(array $searchCriteria = [], $withTrashed = false)
    {
        $queryBuilder = $this->model;

        if (isset($searchCriteria['end_date'])) {
            $queryBuilder = $queryBuilder->whereDate('date', '<=', Carbon::parse($searchCriteria['end_date']));
            unset($searchCriteria['end_date']);
        }

        if (isset($searchCriteria['start_date'])) {
            $queryBuilder = $queryBuilder->whereDate('date', '>=', Carbon::parse($searchCriteria['start_date']));
            unset($searchCriteria['start_date']);
        }

        $queryBuilder = $queryBuilder->where(function ($query) use ($searchCriteria) {
            $this->applySearchCriteriaInQueryBuilder($query, $searchCriteria);
        });

        $limit = !empty($searchCriteria['per_page']) ? (int)$searchCriteria['per_page'] : 15;
        $orderBy = !empty($searchCriteria['order_by']) ? $searchCriteria['order_by'] : 'id';
        $orderDirection = !empty($searchCriteria['order_direction']) ? $searchCriteria['order_direction'] : 'desc';
        $queryBuilder->orderBy($orderBy, $orderDirection);

        if ($withTrashed) {
            $queryBuilder->withTrashed();
        }

        if (empty($searchCriteria['withoutPagination'])) {
            return $queryBuilder->paginate($limit);
        } else {
            return $queryBuilder->get();
        }
    }
    /**
     * @throws CustomValidationException
     */
    public function save(array $data): \ArrayAccess
    {
        return $this->startDay($data); // TODO: Change the autogenerated stub
    }

    /**
     * @throws CustomValidationException
     */
    public function startDay($request)
    {
        $startingCash = $request['starting_cash'];

        // Check if there's already a record for today
        $todayCashFlow = CashFlow::whereDate('date', now()->toDateString())->first();

        if (!$todayCashFlow) {
            // Start a new day record
            $cashFlow = CashFlow::create([
                'starting_cash' => $startingCash,
                'date' => now(),
            ]);
        } else {
            throw new CustomValidationException('The name field must be an array.', 422, [
                'tariff_id' => ['Day already started.'],
            ]);
        }

        return $cashFlow;
    }
    public function update(\ArrayAccess $model, array $data): \ArrayAccess
    {
        return parent::update($model, $data); // TODO: Change the autogenerated stub
    }

    /**
     * @throws CustomValidationException
     */
    public function endDay()
    {
        $cashFlow = CashFlow::whereDate('date', now()->toDateString())->first();

        if (!$cashFlow) {
            throw new CustomValidationException('The name field must be an array.', 422, [
                'tariff_id' => ['No record found for today.'],
            ]);
        }

        $totalIncome = PaymentLog::where('method', PaymentMethod::cash->value)
            ->where('status', PaymentStatus::success->value)
            ->whereDate('date', now()->toDateString())
            ->sum('amount');

        // Calculate ending cash
        $endingCash = $cashFlow->starting_cash + $totalIncome;

        // Update the cash flow record with the ending cash
        $cashFlow->update([
            'income' => $totalIncome,
            'ending_cash' => $endingCash,
        ]);

        return $cashFlow;
    }

}
