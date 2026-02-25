<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Treatment Plan #{{ $plan->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; margin: 20px; color: #333; line-height: 1.4; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { margin: 0; text-transform: uppercase; letter-spacing: 1px; }
        
        /* Information Grid */
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .info-table td { padding: 6px 4px; border: 1px solid #ccc; }
        .label { font-weight: bold; color: #555; display: block; font-size: 9px; text-transform: uppercase; }
        .value { font-size: 11px; font-weight: bold; }

        .section-header { 
            background: #000; color: #fff; padding: 6px; 
            font-weight: bold; text-transform: uppercase; margin-top: 15px; 
            letter-spacing: 1px;
        }

        /* Content Blocks */
        .content-box { 
            width: 100%; 
            border: 1px solid #ccc; 
            border-top: none;
            padding: 10px; 
            min-height: 40px;
            background-color: #fcfcfc;
        }
        
        .clear { clear: both; }

        /* Phases Table Styling */
        .phases-table { width: 100%; border-collapse: collapse; }
        .phases-table th { background: #eee; text-align: left; padding: 8px; border: 1px solid #ccc; text-transform: uppercase; font-size: 10px; }
        .phases-table td { padding: 8px; border: 1px solid #ccc; vertical-align: top; }

        /* Footer/Signatures */
        .signature-section { margin-top: 40px; width: 100%; }
        .sig-box { width: 45%; float: left; text-align: center; }
        .sig-box.right { float: right; }
        .sig-line { border-top: 1px solid #000; margin-top: 30px; padding-top: 5px; font-weight: bold; text-transform: uppercase; }
        .sig-label { font-size: 9px; color: #666; }
    </style>
</head>
<body>

<div class="header">
    <h2>Treatment Plan</h2>
    <p>Issued on: {{ \Carbon\Carbon::parse($plan->consent_date)->format('F d, Y') }}</p>
</div>

<div class="section-header">Basic Information</div>
<table class="info-table">
    <tr>
        <td width="25%"><span class="label">First Name</span><span class="value">{{ $plan->patient->first_name }}</span></td>
        <td width="25%"><span class="label">Last Name</span><span class="value">{{ $plan->patient->last_name }}</span></td>
        <td width="25%"><span class="label">Date of Birth</span><span class="value">{{ \Carbon\Carbon::parse($plan->patient->date_of_birth)->format('m/d/Y') }}</span></td>
        <td width="25%"><span class="label">Patient Identifier</span><span class="value">#{{ $plan->patient->id }}</span></td>
    </tr>
    <tr>
        <td><span class="label">Gender</span><span class="value">{{ $plan->patient->sex ?? 'N/A' }}</span></td>
        <td><span class="label">Email</span><span class="value">{{ $plan->patient->email }}</span></td>
        <td><span class="label">Contact Number</span><span class="value">{{ $plan->patient->mobile_number }}</span></td>
    </tr>
    <tr>
        <td colspan="2"><span class="label">Address</span><span class="value">{{ $plan->patient->address }}</span></td>
        <td><span class="label">City</span><span class="value">{{ $plan->patient->city }}</span></td>
        <td><span class="label">Zip Code</span><span class="value">{{ $plan->patient->zip_code }}</span></td>
    </tr>
</table>

<div class="section-header">Patient Concern</div>
<div class="content-box">{{ $plan->patient_concern ?? 'None reported.' }}</div>

<div class="section-header">Treatment Phases & Procedures</div>
<table class="phases-table">
    <thead>
        <tr>
            <th width="20%">Phase</th>
            <th width="20%">Target Date</th>
            <th width="60%">Procedures & Details</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($plan->phases as $phaseName => $phase)
            <tr>
                <td><strong>{{ ucfirst($phaseName) }}</strong></td>
                <td>{{ $phase['date'] ?? '-' }}</td>
                <td>{{ $phase['procedures'] ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
   

<div class="section-header">Risks, Benefits & Alternatives</div>
<div class="content-box">
    <strong>Risks & Benefits:</strong> {{ $plan->risks_and_benefits ?? '-' }}<br><br>
    <strong>Alternatives:</strong> {{ $plan->alternatives ?? '-' }}
</div>

<div class="section-header">Financial Summary</div>
<div class="content-box">
    <table width="100%" style="border:none;">
        <tr>
            <td style="border:none;"><span class="label">Estimated Costs</span><span class="value">{{ $plan->estimated_costs ?? '-' }}</span></td>
            <td style="border:none;"><span class="label">Payment Options</span><span class="value">{{ $plan->payment_options ?? '-' }}</span></td>
        </tr>
    </table>
</div>

<div class="signature-section">
    <p style="font-size: 10px; margin-bottom: 20px;">
        <em>I understand the proposed treatment plan, its benefits, risks, and alternatives, and I hereby give my consent for the procedures to be performed.</em>
    </p>
    <div class="sig-box">
        <div class="sig-line">{{ $plan->patient->first_name }} {{ $plan->patient->last_name }}</div>
        <div class="sig-label">Patient Signature</div>
    </div>
    <div class="sig-box right">
        <div class="sig-line">Dr. {{ $plan->dentist_name ?? '________________' }}</div>
        <div class="sig-label">Clinician Signature / Designation</div>
    </div>
    <div class="clear"></div>
</div>

</body>
</html>