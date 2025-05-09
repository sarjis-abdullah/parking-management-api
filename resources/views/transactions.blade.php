@php use Carbon\Carbon; @endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Date-wise Report PDF</title>
</head>
<style>
    @font-face {
        font-family: 'Noto Sans Bengali';
        src: url('{{ storage_path('fonts/NotoSansBengali-VariableFont_wdth,wght.ttf') }}') format('truetype');
        font-weight: normal;
        font-style: normal;
    }
    body {
        font-family: 'Noto Sans Bengali', sans-serif;
        margin: auto;
        max-width: 1200px;
    }
</style>
<body style="font-family:'Noto Sans Bengali', sans-serif;">
<h1 style="text-align: center;">Transaction Reports</h1>
<table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
    <tbody>

        <tr>
            @foreach ($totals as $key => $value)
            <td style="border: 1px solid #ddd; font-size:12px; text-align: center; text-transform: capitalize;">{{$key}} : {{$value}}</td>
            @endforeach
        </tr>

    </tbody>
</table>
<table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
    <thead>
    <tr>
        <th style="border: 1px solid #ddd; font-size:12px; text-align: center; background-color: #f2f2f2;">SL No.</th>
        <th style="border: 1px solid #ddd; font-size:12px; text-align: center; background-color: #f2f2f2;">Vehicle</th>
        <th style="border: 1px solid #ddd; font-size:12px; text-align: center; background-color: #f2f2f2;">Date</th>
        <th style="border: 1px solid #ddd; font-size:12px; text-align: center; background-color: #f2f2f2;">Duration</th>
        <th style="border: 1px solid #ddd; font-size:12px; text-align: center; background-color: #f2f2f2;">Payable</th>
        <th style="border: 1px solid #ddd; font-size:12px; text-align: center; background-color: #f2f2f2;">Paid</th>
        <th style="border: 1px solid #ddd; font-size:12px; text-align: center; background-color: #f2f2f2;">Discount</th>
        <th style="border: 1px solid #ddd; font-size:12px; text-align: center; background-color: #f2f2f2;">Due</th>
        <th style="border: 1px solid #ddd; font-size:12px; text-align: center; background-color: #f2f2f2;">Type</th>
        <th style="border: 1px solid #ddd; font-size:12px; text-align: center; background-color: #f2f2f2;">Status</th>
        <th style="border: 1px solid #ddd; font-size:12px; text-align: center; background-color: #f2f2f2;">Method</th>
    </tr>
    </thead>
    <tbody>
        @foreach ($transactions as $item)
            <tr>
                <td style="border: 1px solid #ddd; font-size:12px; text-align: center;">
                    {{$loop->index + 1}}
                </td>
                <td style="border: 1px solid #ddd; font-size:12px; text-align: center;">{{$item->vehicle?->number}}</td>
                <td style="border: 1px solid #ddd; font-size:12px; text-align: center;">{{$item->transaction_date}}</td>
                @if(isset($item?->parking->in_time) && isset($item?->parking->out_time))
                <td style="border: 1px solid #ddd; font-size:12px; text-align: center;">
                    @php
                        $in = Carbon::parse($item->parking->in_time);
                        $out = Carbon::parse($item->parking->out_time);

                        $diffInMinutes = $in->diffInMinutes($out);
                        $diffInHours = $in->diffInHours($out);
                        $diffInDays = $in->diffInDays($out);

                        if ($diffInMinutes < 60) {
                            $duration = $diffInMinutes . ' m';
                        } elseif ($diffInHours < 24) {
                            $duration = $diffInHours . ' h';
                        } else {
                            $duration = $diffInDays . ' d';
                        }
                    @endphp
                    {{$duration}}
                </td>
                @else
                    <td style="border: 1px solid #ddd; font-size:12px; text-align: center;">N/A</td>
                @endif
                <td style="border: 1px solid #ddd; font-size:12px; text-align: center;">{{'৳ '.$item->total_payable}}</td>
                <td style="border: 1px solid #ddd; font-size:12px; text-align: center;">{{'৳ '.$item->total_paid}}</td>
                <td style="border: 1px solid #ddd; font-size:12px; text-align: center;">
                    <div style="display: flex; flex-direction: column; align-items: end">
                        @if($item->membership_discount)
                        <div>{{'Membership: ৳ '.$item->membership_discount}}</div>
                        @endif
                        @if($item->discount_amount)
                        <div>{{'Other ৳ '.$item->discount_amount}}</div>
                        @endif
                    </div>
                </td>
                <td style="border: 1px solid #ddd; font-size:12px; text-align: center;">{{'৳ '.$item->total_due}}</td>
                <td style="border: 1px solid #ddd; font-size:12px; text-align: center;">{{$item->payment_type}}</td>
                @if($item->status != 'success')
                    <td style="border: 1px solid #ddd; font-size:12px; text-align: center; background: orange; color: white;">{{$item->status}}</td>
                @else
                    <td style="border: 1px solid #ddd; font-size:12px; text-align: center;">{{$item->status}}</td>
                @endif
                <td style="border: 1px solid #ddd; font-size:12px; text-align: center;">{{$item->method}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>
