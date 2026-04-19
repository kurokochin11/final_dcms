<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Billing Report</title>
    <style>
        /* A4 Page Setup */
        @page {
            size: A4;
            margin: 1.5cm;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11pt;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        /* Invoice Header */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }

        .brand-name {
            font-size: 24pt;
            font-weight: bold;
            color: #4f46e5;
            margin: 0;
        }

        .report-title {
            text-align: right;
            font-size: 14pt;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Billing Info Section */
        .info-section {
            width: 100%;
            margin-bottom: 30px;
        }

        .info-label {
            color: #666;
            font-size: 9pt;
            text-transform: uppercase;
            font-weight: bold;
        }

        /* Main Table */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
        }

        table.data-table th {
            background-color: #f3f4f6;
            color: #374151;
            font-weight: bold;
            text-align: left;
            padding: 12px 10px;
            border-bottom: 2px solid #e5e7eb;
        }

        table.data-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #eeeeee;
            vertical-align: top;
        }

        /* Formatting Helpers */
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .text-red { color: #dc2626; }
        
        /* Footer */
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 9pt;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }

        .status-badge {
            font-size: 9pt;
            padding: 2px 8px;
            background: #eee;
            border-radius: 4px;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td>
                <h1 class="brand-name">Dr.Phua's Dental Clinic</h1>
                
            </td>
            <td class="report-title">
                Billing Statement
            </td>
        </tr>
    </table>

    <table class="info-section">
        <tr>
            <td width="50%">
                <div class="info-label">Statement Date</div>
                <div class="font-bold">{{ now()->format('F d, Y') }}</div>
            </td>
            <td width="50%" class="text-right">
                <div class="info-label">Status</div>
                <div class="font-bold">Official Record</div>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="25%">Patient</th>
                <th width="15%">Date</th>
                <th width="30%">Service Rendered</th>
                <th width="15%" class="text-right">Fee</th>
                <th width="15%" class="text-right">Balance</th>
            </tr>
        </thead>
        <tbody>
            @php $totalBalance = 0; @endphp
            @forelse($billings as $billing)
                @php $totalBalance += $billing->outstanding_balance; @endphp
                <tr>
                    <td>
                        <div class="font-bold">
                            {{ $billing->patient->last_name }}, {{ $billing->patient->first_name }}
                        </div>
                        <div style="font-size: 9pt; color: #777;">OR: {{ $billing->receipt_no ?? 'N/A' }}</div>
                    </td>
                    <td>{{ $billing->date->format('m/d/Y') }}</td>
                    <td>{{ $billing->service_rendered }}</td>
                    <td class="text-right">{{ number_format($billing->amount, 2) }}</td>
                    <td class="text-right font-bold {{ $billing->outstanding_balance > 0 ? 'text-red' : '' }}">
                        {{ number_format($billing->outstanding_balance, 2) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 40px;">No records found.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right font-bold" style="padding-top: 20px;">TOTAL OUTSTANDING BALANCE:</td>
                <td class="text-right font-bold text-red" style="padding-top: 20px; font-size: 14pt; border-bottom: 3px double #dc2626;">
                    {{ number_format($totalBalance, 2) }}
                </td>
            </tr>
        </tfoot>
    </table>

   
        

   

</body>
</html>