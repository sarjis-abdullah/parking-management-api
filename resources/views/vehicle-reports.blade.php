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
    <thead>
    <tr>
        <th style="border: 1px solid #ddd; font-size:12px; text-align: center; background-color: #f2f2f2;">SL No.</th>
        <th style="border: 1px solid #ddd; font-size:12px; text-align: center; background-color: #f2f2f2;">Date</th>
        <th style="border: 1px solid #ddd; font-size:12px; text-align: center; background-color: #f2f2f2;">Vehicle entries count</th>
    </tr>
    </thead>
    <tbody>
        @foreach ($items as $item)
            <tr>
                <td style="border: 1px solid #ddd; font-size:12px; text-align: center;">
                    {{$loop->index + 1}}
                </td>
                <td style="border: 1px solid #ddd; font-size:12px; text-align: center;">{{$item->entry_date}}</td>
                <td style="border: 1px solid #ddd; font-size:12px; text-align: center;">{{$item->vehicle_entries}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>
