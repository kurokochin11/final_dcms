<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
        }

        .container {
            border: 1px solid #ddd;
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #1f2937;
        }

        .row {
            margin-bottom: 10px;
            padding: 8px;
            border-bottom: 1px solid #eee;
        }

        .label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }

        .value {
            color: #111;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
            font-weight: bold;
            font-size: 16px;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>Treatment Record</h2>

    <div class="row">
        <span class="label">Patient:</span>
        <span class="value">
            {{ $treatment->patient->last_name }},
            {{ $treatment->patient->first_name }}
        </span>
    </div>

    <div class="row">
        <span class="label">Treatment Plan:</span>
        <span class="value">{{ $treatment->treatment_plan }}</span>
    </div>

    <div class="row">
        <span class="label">Tooth Number:</span>
        <span class="value">{{ $treatment->tooth_number }}</span>
    </div>

    <div class="row">
        <span class="label">Amount:</span>
        <span class="value">₱{{ $treatment->amount }}</span>
    </div>

    <div class="footer">
        Total: ₱{{ $treatment->amount }}
    </div>

</div>

</body>
</html>
