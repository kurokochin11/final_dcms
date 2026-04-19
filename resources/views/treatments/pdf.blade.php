<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Treatment Receipt</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.5; }
        .receipt-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; }
        
        /* Header Section */
        .header { margin-bottom: 20px; border-bottom: 2px solid #0056b3; padding-bottom: 10px; }
        .clinic-name { font-size: 24px; color: #0056b3; font-weight: bold; text-transform: uppercase; }
        .clinic-details { font-size: 12px; color: #666; }
        
        /* Patient & Info Section */
        .info-table { width: 100%; margin-bottom: 30px; font-size: 14px; }
        .info-table td { vertical-align: top; }
        
        /* Table Style */
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .items-table th { background: #f8f9fa; border-bottom: 2px solid #dee2e6; padding: 12px; text-align: left; font-size: 13px; }
        .items-table td { padding: 12px; border-bottom: 1px solid #eee; font-size: 13px; }
        
        /* Totals */
        .total-section { float: right; width: 40%; }
        .total-row { display: flex; justify-content: space-between; padding: 5px 0; font-size: 14px; }
        .grand-total { font-size: 18px; font-weight: bold; color: #0056b3; border-top: 2px solid #0056b3; margin-top: 10px; padding-top: 10px; }
        
        .footer { margin-top: 100px; text-align: center; font-size: 11px; color: #999; }
    </style>
</head>
<body>
    <div class="receipt-box">
        <div class="header">
            <div class="clinic-name">Dr.Phua's Dental Clinic</div>
            <div class="clinic-details">
              
            </div>
        </div>

        <table class="info-table">
            <tr>
                <td>
                    <strong>PATIENT:</strong><br>
                    {{ $treatments->first()->patient->last_name }}, {{ $treatments->first()->patient->first_name }}<br>
                    ID: #{{ $treatments->first()->patient->id }}
                </td>
                <td style="text-align: right;">
                    <strong>RECEIPT DATE:</strong> {{ date('M d, Y') }}<br>
                    <strong>STATUS:</strong> PAID
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Tooth #</th>
                    <th>Treatment Description</th>
                    <th style="text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($treatments as $record)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($record->date)->format('M d, Y') }}</td>
                        <td>{{ $record->tooth_number }}</td>
                        <td>{{ $record->treatment }}</td>
                        <td style="text-align: right;">{{ number_format($record->amount, 2) }}</td>
                    </tr>
                    @php $total += $record->amount; @endphp
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <div class="total-row" style="text-align: right;">
                <span>Subtotal: </span>
                <strong>{{ number_format($total, 2) }}</strong>
            </div>
            <div class="grand-total" style="text-align: right;">
                <span>Total Amount: </span>
                <strong>{{ number_format($total, 2) }}</strong>
            </div>
        </div>

        <div style="clear: both;"></div>

        
    </div>
</body>
</html>