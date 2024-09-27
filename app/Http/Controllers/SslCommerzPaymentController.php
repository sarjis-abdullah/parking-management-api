<?php

namespace App\Http\Controllers;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Library\SslCommerzNotification;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SslCommerzPaymentController
{
    public function exampleEasyCheckout()
    {
        return view('exampleEasycheckout');
    }

    public function exampleHostedCheckout()
    {
        return view('exampleHosted');
    }

    public function pay(Request $request)
    {
//        dd('exampleHostedCheckout');
        # Here you have to receive all the order data to initate the payment.
        # Let's say, your oder transaction informations are saving in a table called "orders"
        # In "orders" table, order unique identity is "transaction_id". "status" field contain status of the transaction, "amount" is the order amount to be paid and "currency" is for storing Site Currency which will be checked with paid currency.

        $post_data = array();
        $post_data['total_amount'] = '10'; # You cant not pay less than 10
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = uniqid(); // tran_id must be unique

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

        $sslc = new SslCommerzNotification();
        # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
        return response()->json([
            'data' => $sslc->makePayment($post_data, 'hosted')
        ]);
    }

    public function payViaAjax(Request $request)
    {
        dd('exampleEasyCheckout payment');
        # Here you have to receive all the order data to initate the payment.
        # Lets your oder trnsaction informations are saving in a table called "orders"
        # In orders table order uniq identity is "transaction_id","status" field contain status of the transaction, "amount" is the order amount to be paid and "currency" is for storing Site Currency which will be checked with paid currency.

        $post_data = array();
        $post_data['total_amount'] = '10'; # You cant not pay less than 10
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = uniqid(); // tran_id must be unique

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


        #Before  going to initiate the payment order status need to update as Pending.
        $update_product = DB::table('orders')
            ->where('transaction_id', $post_data['tran_id'])
            ->updateOrInsert([
                'name' => $post_data['cus_name'],
                'email' => $post_data['cus_email'],
                'phone' => $post_data['cus_phone'],
                'amount' => $post_data['total_amount'],
                'status' => 'Pending',
                'address' => $post_data['cus_add1'],
                'transaction_id' => $post_data['tran_id'],
                'currency' => $post_data['currency']
            ]);

        $sslc = new SslCommerzNotification();
        # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
        $payment_options = $sslc->makePayment($post_data, 'checkout', 'json');

        if (!is_array($payment_options)) {
            print_r($payment_options);
            $payment_options = array();
        }

    }

    public function success(Request $request, $transactionId): string
    {
        $queryBuilder = Payment::query();
        $queryBuilder = $queryBuilder->where('transaction_id', $transactionId);
        $totalPayableAmount = $queryBuilder->sum('payable_amount');
        $totalDueAmount = $queryBuilder->sum('due_amount');

        $payments = $queryBuilder->get();
        $success = PaymentStatus::success->value;
        $complete = PaymentStatus::complete->value;
        $pending = PaymentStatus::pending->value;

        if ($payments->count() > 1) {
            foreach ($payments as $payment) {
                $status = $payment->status;
                if ($payment->status == $pending) {
                    $payment->update([
                        'status'        => PaymentStatus::success,
                        'method'        => PaymentMethod::ssl_commerz,
                        'paid_amount'   => $payment->payable_amount,
                    ]);
                    continue;
                } else if ($payment->status == $success && $payment->payment_type == 'partial'){
                    $payment->update([
                        'paid_amount'   => $payment->payable_amount,
                        'due_amount'    => 0,
                        'payment_type'  => 'full',
                    ]);
                    continue;
                }
                else if ($status == $success || $status == $complete) {
                    continue;
                } else {
                    continue;
                }
            }
            return redirect(env('CLIENT_URL').'/success');
        }
        else{
            $payment = $queryBuilder->first();

            $status = $payment->status;

            if ($payment->status == $pending) {
                $payment->update([
                    'status' => PaymentStatus::success,
                    'method' => PaymentMethod::ssl_commerz
                ]);
                return redirect(env('CLIENT_URL').'/success');
            } else if ($payment->status == $success && $payment->payment_type == 'partial'){
                $payment->update([
                    'paid_amount' => $payment->payable_amount,
                    'due_amount' => 0,
                    'payment_type' => 'full',
                ]);
                return redirect(env('CLIENT_URL').'/success');
            }
            else if ($status == $success || $status == $complete) {
                return redirect(env('CLIENT_URL').'/success');
            } else {
                return redirect(env('CLIENT_URL').'/failed');
            }
        }
    }

    public function fail(Request $request, $transactionId)
    {
        $payment = Payment::where('transaction_id', $transactionId)->first();
        $success = PaymentStatus::success->value;
        $complete = PaymentStatus::complete->value;
        $pending = PaymentStatus::pending->value;
        $status = $payment->status;

        if ($payment->status == $pending) {
            $payment->update(['status' => PaymentStatus::failed]);
            return redirect(env('CLIENT_URL').'/failed');
        } else if ($status == $success || $status == $complete) {
            return redirect(env('CLIENT_URL').'/success');
        } else {
            return redirect(env('CLIENT_URL').'/failed');
        }

    }

    public function cancel(Request $request, $transactionId)
    {
        $payment = Payment::where('transaction_id', $transactionId)->first();

        $success = PaymentStatus::success->value;
        $complete = PaymentStatus::complete->value;
        $pending = PaymentStatus::pending->value;
        $status = $payment->status;
        if ($status == $pending) {
            $payment->update(['status' => PaymentStatus::canceled]);
            return redirect(env('CLIENT_URL').'/cancel');
        } else if ($status == $success || $status == $complete) {
            return redirect(env('CLIENT_URL').'/success');
        } else {
            return redirect(env('CLIENT_URL').'/failed');
        }
    }

    public function ipn(Request $request)
    {
        #Received all the payement information from the gateway
        if ($request->input('tran_id')) #Check transation id is posted or not.
        {

            $tran_id = $request->input('tran_id');
            $transactionId = $tran_id;

            #Check order status in order tabel against the transaction id or order id.
            $payment = Payment::where('transaction_id', $transactionId)->first();

            if ($payment->status == 'Pending') {
                $sslc = new SslCommerzNotification();
                $validation = $sslc->orderValidate($request->all(), $tran_id, $payment->amount, $payment->currency);
                if ($validation == TRUE) {
                    /*
                    That means IPN worked. Here you need to update order status
                    in order table as Processing or Complete.
                    Here you can also sent sms or email for successful transaction to customer
                    */
                    $payment->update(['status' => 'Processing']);

                    echo "Transaction is successfully Completed";
                }
            } else if ($payment->status == 'Processing' || $payment->status == 'Complete') {

                #That means Order status already updated. No need to udate database.

                echo "Transaction is already successfully Completed";
            } else {
                #That means something wrong happened. You can redirect customer to your product page.

                echo "Invalid Transaction";
            }
        } else {
            echo "Invalid Data";
        }
    }
}
