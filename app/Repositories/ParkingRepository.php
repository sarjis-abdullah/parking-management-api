<?php

namespace App\Repositories;

use App\Enums\ParkingStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\SlotStatus;
use App\Exceptions\CustomException;
use App\Exceptions\CustomValidationException;
use App\Library\SslCommerzNotification;
use App\Models\Discount;
use App\Models\Membership;
use App\Models\MembershipType;
use App\Models\Payment;
use App\Models\PaymentLog;
use App\Models\Slot;
use App\Models\Tariff;
use App\Models\Vehicle;
use App\Repositories\Contracts\InstrumentSupportedRepository;
use App\Repositories\Contracts\ParkingInterface;
use App\Repositories\Contracts\PlaceInterface;
use App\Repositories\Contracts\UserInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ParkingRepository extends EloquentBaseRepository implements ParkingInterface
{
    /*
    * @inheritdoc
    */
    public function findBy(array $searchCriteria = [], $withTrashed = false)
    {
        $queryBuilder = $this->model;

        if(isset($searchCriteria['query'])) {
            $searchCriteria['id'] = $queryBuilder->where('barcode', 'like', '%' . $searchCriteria['query'] . '%')
                ->orWhereHas('vehicle', function ($query) use ($searchCriteria) {
                    $query->where('number', 'like', '%' . $searchCriteria['query'] . '%');
                })->orWhereHas('payments', function ($query) use ($searchCriteria) {
                    $query->where('transaction_id', '=', $searchCriteria['query']);
                })
                ->pluck('id')->toArray();
            unset($searchCriteria['query']);
        }
        $queryBuilder = $queryBuilder->where('status', '!=', ParkingStatus::checked_out->value);

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
     * @throws Exception
     */
    public function save(array $data): \ArrayAccess
    {
        DB::beginTransaction();
        $data['barcode'] = uniqid();
        $data['in_time'] = now();
        $slot = Slot::find($data['slot_id']);

        $oldVehicle = Vehicle::where('number', $data['vehicle_no'])->first();
        $this->checkVehicleCheckedInToThrowError($oldVehicle);

        $vehicleData = [
            'number' => $data['vehicle_no'],
            'driver_name' => $data['driver_name'] ?? null,
            'driver_mobile' => $data['driver_mobile'] ?? null,
            'category_id' => $data['category_id'],
            'status' => ParkingStatus::checked_in->value,
        ];
        $vehicleId = null;
        if ($oldVehicle instanceof Vehicle){

//            if ($oldVehicle?->membership){
//                $membership = $oldVehicle->membership;
//                $membership_id = $oldVehicle->membership->id;
//                Membership::find($membership_id)->update(['points' => $membership->points + 5]);
//                addMembershipTypeToVehicleMembership($membership);
//            }
            $oldVehicle->update($vehicleData);

            $vehicleId = $oldVehicle->id;
        }else {
            $vehicle = Vehicle::create($vehicleData);
            $vehicleId = $vehicle->id;
        }

        $data['vehicle_id'] = $vehicleId;

        $data['tariff_id'] = $this->getValidatedTariff($data);


        if ($slot->status != SlotStatus::occupied->value){
            Slot::find($data['slot_id'])->update([
                'status' => SlotStatus::occupied->value
            ]);
            $item = parent::save($data);

            DB::commit();
            return $item;
        }
        throw new CustomValidationException('The name field must be an array.', 422, [
            'slot' => ['Slot is already occupied.'],
        ]);
    }

    /**
     * @throws CustomValidationException
     */
    protected function isTariffExpired(Tariff $tariff): void
    {
        if ($tariff?->end_date){
            $endDateCarbon = Carbon::parse($tariff->end_date);
            if ($endDateCarbon->isPast()){
                throw new CustomValidationException('The name field must be an array.', 422, [
                    'tariff_id' => ['Tariff is expired, add new tariff first.'],
                ]);
            }
        }
        if ($tariff?->start_date){
            $currentDate = Carbon::now();
            if ($currentDate->lessThan($tariff->start_date)) {
                throw new CustomValidationException('The name field must be an array.', 422, [
                    'tariff_id' => ['The default tariff is not started yet. This is set for future date.'],
                ]);
            }
        }
    }

    /**
     */
    public function update(\ArrayAccess $model, array $data): \ArrayAccess
    {
//        $data['barcode'] = uniqid();
//        $data['in_time'] = now();
//        $data['status'] = ParkingStatus::checked_in->value;
//        DB::beginTransaction();
//        if (!isset($data['tariff_id'])){
//            $data['tariff_id'] = Tariff::where('default', true)->orderBy('updated_at', 'desc')->first()->id;
//        }
//        $slot = Slot::find($data['slot_id']);
//
//        $vehicle = Vehicle::find($model->vehicle_id);
//        $this->checkVehicleCheckedInToThrowError($vehicle);
//        if ($slot->status != SlotStatus::occupied->value){
//            Slot::find($data['slot_id'])->update([
//                'status' => SlotStatus::occupied->value
//            ]);
//            $item = parent::update($model, $data);
//
//            DB::commit();
//            return $item;
//        } else {
//            throw new CustomValidationException('Slot is already occupied..', 422, [
//                'slot' => ['Slot is already occupied.'],
//            ]);
//        }
    }

    /**
     * @throws CustomValidationException
     */
    protected function checkVehicleCheckedInToThrowError($vehicle): void
    {
        if ($vehicle instanceof Vehicle && $vehicle->status == ParkingStatus::checked_in->value){
            throw new CustomValidationException('The vehicle is already in checked-in.', 422, [
                'status' => ['The vehicle is already in checked-in.'],
            ]);
        }
    }

    /**
     * @throws CustomValidationException
     */
    protected function checkVehicleCheckedOutToThrowError($vehicle): void
    {
        if ($vehicle instanceof Vehicle && $vehicle->status == ParkingStatus::checked_out->value){
            throw new CustomValidationException('The vehicle is already checked-out.', 422, [
                'status' => ['The vehicle is already checked-out.'],
            ]);
        }
    }

    /**
     * @throws CustomValidationException
     */
    public function handleCheckout(\ArrayAccess $model, array $data)
    {
        DB::beginTransaction();
//        throw new CustomValidationException('Error.', 422, [
//            'error' => 'We dont accept over amount',
//        ]);
        $vehicle = Vehicle::find($model->vehicle_id);
        $this->checkVehicleCheckedOutToThrowError($vehicle);

        Slot::find($model->slot_id)->update([
            'status' => SlotStatus::available->value
        ]);

        $payable_amount = (double)$data['payment']['payable_amount'];
        $paid_amount = (double)$data['payment']['paid_amount'];
        $discount_amount = (double)$data['payment']['discount_amount'];
        $membership_discount = (double)$data['payment']['membership_discount'];
        $payment_type = 'full';
        $dueAmount = $payable_amount - $paid_amount - $discount_amount - $membership_discount;
        if ($dueAmount > 0){
            $payment_type = 'partial';
        }

        $finalTotalAmount = $payable_amount - $discount_amount - $membership_discount;
        if ($finalTotalAmount < 0) {
            throw new CustomValidationException('Error.', 422, [
                'error' => 'Subtotal cannot be less than zero.',
            ]);
        }

        $parkingFee = $payable_amount;
        $amount = $paid_amount + $discount_amount + $membership_discount;
        if ($amount > $parkingFee) {
           throw new CustomValidationException('Error', 422, [
               'error' => 'Sum of receiving amount and discount cannot be greater than Parking fees.',
           ]);
        }
        if ($dueAmount < 0) {
            throw new CustomValidationException('Error.', 422, [
                'error' => 'Due amount cannot be less than zero.',
            ]);
        }

        if (isset($data['payment']['discount_id'])){
            $id = $data['payment']['discount_id'];
            Discount::find($id)->update([
                'promo_code' => Str::random(4),
            ]);
        }

        $status = $paid_amount == 0 ? PaymentStatus::unpaid->value : PaymentStatus::pending->value;
        $method = $paid_amount == 0 ? PaymentMethod::none->value : $data['payment']['method'];
        $payment = Payment::create([
            'method' => $method,
            'paid_amount' => $data['payment']['paid_amount'],
            'payable_amount' => $data['payment']['payable_amount'],
            'discount_amount' => $data['payment']['discount_amount'],
            'membership_discount' => $data['payment']['membership_discount'],
            'due_amount' => $dueAmount,
            'payment_type' => $payment_type,
//            'received_by' => auth()->id(),
            'parking_id' => $model->id,
            'paid_by_vehicle_id' => $model->vehicle_id,
            'transaction_id' => uniqid(),
            'status' => $status,
        ]);

        PaymentLog::create([
            'payment_id' => $payment->id,
            'status' => $payment->status,
            'method' => $payment->method,
            'amount' => $payment->paid_amount,
            'date' => now(),
        ]);

        $vehicle->update([
            'status' => ParkingStatus::checked_out->value,
        ]);

        $item = parent::update($model, $data);

        if ($payment->method == PaymentMethod::cash->value && $payment->status == PaymentStatus::pending->value){
            $payment->update([
                'status'        => PaymentStatus::success->value,
                'date'        => now(),
            ]);

            PaymentLog::create([
                'payment_id' => $payment->id,
                'status' => $payment->status,
                'method' => $payment->method,
                'amount' => $payment->paid_amount,
                'date' => now(),
            ]);

            DB::commit();
            return [
                'data' => [
                    'redirect_url' => env('CLIENT_URL').'/success?transaction_id='.$payment->transaction_id
                ]
            ];
        }

        DB::commit();

        $paymentData = [
            'amount' => $payment->paid_amount,
            'transaction_id' => $payment->transaction_id,
        ];
        if ($payment->paid_amount > 0){
            return [
                'data' => [
                    'redirect_url' => $this->payBySslCommerz($paymentData)
                ]
            ];
        }

        return $item;
    }

    public function getAmountToPay($payments): float|int
    {
        $totalPayableForSelectedTransaction = 0;

        // Assuming you have a collection of payments
        foreach ($payments as $payment) {
            if ($payment->status == 'success' && $payment->payment_type == "partial") {
                $totalPayableForSelectedTransaction += floatval($payment->due_amount);
            }elseif ($payment->status != 'success') {
                $amountToPay = floatval($payment->payable_amount) - floatval($payment->discount_amount) - floatval($payment->membership_discount);
                $totalPayableForSelectedTransaction += $amountToPay;
            }
        }
        return $totalPayableForSelectedTransaction;
    }

    public function applyBatchPayment($paymentIds, $amountToApply, $method)
    {
        DB::transaction(function () use ($paymentIds, $amountToApply, $method) {
            $payments = Payment::whereIn('id', $paymentIds)
                ->orderBy('id')
                ->get();

            foreach ($payments as $payment) {
                if ($amountToApply <= 0) {
                    throw new CustomValidationException('The name field must be an array.', 422, [
                        'error' => 'Not enough amount',
                    ]);
                }
                $amountForThisRow = 0;
                if ($payment->status == 'success' && $payment->payment_type == "partial") {
                    $amountForThisRow = floatval($payment->due_amount);
                    $payment->paid_amount += $amountForThisRow;
                    $payment->due_amount -= $amountForThisRow;
                }elseif ($payment->status != 'success') {
                    $amountForThisRow = floatval($payment->payable_amount) - floatval($payment->discount_amount) - floatval($payment->membership_discount);
                    $payment->paid_amount = $amountForThisRow;
                    $payment->due_amount = 0;
                }
                $status = $payment->due_amount == 0 ? PaymentStatus::success->value : $payment->status;
                PaymentLog::create([
                    'payment_id' => $payment->id,
                    'status' => $status,
                    'method' => $method,
                    'amount' => $amountForThisRow,
                    'date' => now(),
                ]);

                $oldStatus = $payment->status;
                $payment->status = $status;
                $payment->payment_type = $payment->due_amount == 0 ? 'full' : $payment->payment_type;

                if ($payment->method != $method && $oldStatus == PaymentStatus::success->value){
                    $payment->method = PaymentMethod::mixed->value;
                }else {
                    $payment->method = $method;
                }

                $payment->date = now();

                $payment->save();

                // Subtract the applied amount from the total amount
                $amountToApply -= $amountForThisRow;
            }
        });
    }


    function repay(Request $request)
    {

    }
    public function payBySslCommerz($payment)
    {
//        dd('exampleHostedCheckout');
        # Here you have to receive all the order data to initate the payment.
        # Let's say, your oder transaction informations are saving in a table called "orders"
        # In "orders" table, order unique identity is "transaction_id". "status" field contain status of the transaction, "amount" is the order amount to be paid and "currency" is for storing Site Currency which will be checked with paid currency.

        $post_data = array();
        $post_data['total_amount'] = $payment['amount']; # You cant not pay less than 10
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = $payment['transaction_id']; // tran_id must be unique

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = 'Customer Name';
        $post_data['cus_email'] = 'customer@mail.com';
        $post_data['cus_add1'] = 'Customer Address';
        $post_data['cus_add2'] = "";
        $post_data['cus_city'] = "";
        $post_data['cus_state'] = "";
        $post_data['cus_postcode'] = "";
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_phone'] = '8801XXXXXXXXX';
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION
        $post_data['ship_name'] = "Store Test";
        $post_data['ship_add1'] = "Dhaka";
        $post_data['ship_add2'] = "Dhaka";
        $post_data['ship_city'] = "Dhaka";
        $post_data['ship_state'] = "Dhaka";
        $post_data['ship_postcode'] = "1000";
        $post_data['ship_phone'] = "";
        $post_data['ship_country'] = "Bangladesh";

        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = "Computer";
        $post_data['product_category'] = "Goods";
        $post_data['product_profile'] = "physical-goods";

        # OPTIONAL PARAMETERS
        $post_data['value_a'] = "ref001";
        $post_data['value_b'] = "ref002";
        $post_data['value_c'] = "ref003";
        $post_data['value_d'] = "ref004";
        if (isset($payment['scan-checkout']))
            $post_data['scan-checkout'] = $payment['scan-checkout'];

        $sslc = new SslCommerzNotification();

        return $sslc->makePayment($post_data, 'hosted');
    }

    /**
     * @throws CustomValidationException
     */
    private function getValidatedTariff(array $data): int
    {
        if (!isset($data['tariff_id'])){
            $tariff = Tariff::where('default', true)->orderBy('updated_at', 'desc')->first();

            if ($tariff instanceof Tariff){
                $this->isTariffExpired($tariff);
                return $tariff->id;
            }else {
                throw new CustomValidationException('The name field must be an array.', 422, [
                    'tariff_id' => ['No tariff available, add a tariff first.'],
                ]);
            }
        }else {
            $tariff = Tariff::find($data['tariff_id']);
            $this->isTariffExpired($tariff);
        }
        return $data['tariff_id'];
    }
}

function addMembershipTypeToVehicleMembership(Membership $membership): void
{
    if ($membership?->id) {
        $membership_id = $membership->id;
        $membershipType = MembershipType::where('min_points', '<=', $membership->points)
            ->orderBy('min_points', 'desc')
            ->first();

        Membership::find($membership_id)->update([
            'membership_type_id' => $membershipType->id ?? null
        ]);
    }
}
