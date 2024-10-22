<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Date-wise Report PDF</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 20px;">
<h1 style="text-align: center;">Date-wise Report</h1>

<h2 style="text-align: center; margin-top: 20px;">Date-wise Transactions</h2>
<table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
    <thead>
    <tr>
        <th style="border: 1px solid #ddd; padding: 8px; text-align: center; background-color: #f2f2f2;">SL No.</th>
        <th style="border: 1px solid #ddd; padding: 8px; text-align: center; background-color: #f2f2f2;">Vehicle</th>
        <th style="border: 1px solid #ddd; padding: 8px; text-align: center; background-color: #f2f2f2;">Date</th>
        <th style="border: 1px solid #ddd; padding: 8px; text-align: center; background-color: #f2f2f2;">Payable</th>
        <th style="border: 1px solid #ddd; padding: 8px; text-align: center; background-color: #f2f2f2;">Paid</th>
        <th style="border: 1px solid #ddd; padding: 8px; text-align: center; background-color: #f2f2f2;">Discount</th>
        <th style="border: 1px solid #ddd; padding: 8px; text-align: center; background-color: #f2f2f2;">Due</th>
        <th style="border: 1px solid #ddd; padding: 8px; text-align: center; background-color: #f2f2f2;">Type</th>
        <th style="border: 1px solid #ddd; padding: 8px; text-align: center; background-color: #f2f2f2;">Status</th>
        <th style="border: 1px solid #ddd; padding: 8px; text-align: center; background-color: #f2f2f2;">Method</th>
    </tr>
    </thead>
    <tbody>
        @foreach ($transactions as $item)
            <tr>
                <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                    {{$loop->index + 1}}
                </td>
                <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">{{$item->vehicle?->number}}</td>
                <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">{{$item->transaction_date}}</td>
                <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">{{$item->total_payable}}</td>
                <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">{{$item->total_paid}}</td>
                <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">{{$item->discount_amount}}</td>
                <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">{{$item->total_due}}</td>
                <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">{{$item->payment_type}}</td>
                <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">{{$item->status}}</td>
                <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">{{$item->method}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>
