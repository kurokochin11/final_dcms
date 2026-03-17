<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Diagnoses result pdf</title>
    <style>
        .tooth-logo {
    float: left;
    width: 60px; /* Normal logo size */
    height: 60px;
    background-image: url("{{ public_path('tooth.png') }}");
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
}

.header-text {
    float: left;
    margin-left: 15px; /* Space between logo and the 'E' */
    text-align: left;
}

.clear {
    clear: both;
}
        /* Copied Styles from Personal Health Summary */
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; margin: 20px; color: #333; }
        
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { margin: 0; text-transform: uppercase; }
        
        .section-header { 
            background: #000; color: #fff; padding: 5px; 
            font-weight: bold; text-transform: uppercase; margin-top: 15px; 
            font-size: 11px;
        }

        /* Patient Info Grid Style */
        .patient-info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .patient-info-table td { padding: 6px 0; border: none; vertical-align: top; }
        .label { font-weight: bold; text-decoration: underline; }

        /* Clinical Findings Table Style */
        .findings-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .findings-table td { 
            padding: 8px 5px; 
            border-bottom: 1px dotted #ccc; 
            vertical-align: top; 
        }
        .findings-label { width: 30%; font-weight: bold; }
        .findings-value { width: 70%; }

        .clear { clear: both; }

        /* Footer/Signatures */
       /* Footer/Signatures */
    .signature-section { 
        margin-top: 60px; 
        width: 100%; 
    }
    .sig-box { 
        width: 45%; 
        float: left; 
        text-align: center; 
    }
    .sig-box.right { 
        float: right; 
    }
    .sig-line { 
        border-bottom: 1px solid #000; /* Changed from border-top to border-bottom */
        margin-bottom: 5px; 
        padding-bottom: 2px;
        font-weight: bold; 
        text-transform: uppercase; /* This forces ALL CAPS */
        min-height: 15px; /* Ensures the line shows even if the name is empty */
    }
    .sig-label {
        font-size: 9px;
        display: block;
    }
        
        @page { margin: 0.5in; }
    </style>
</head>
<body>

    <div class="header">
        <div class="tooth-logo"></div>
        <h2>Diagnosis Report</h2>
        <p>Generated on: {{ now()->format('F j, Y') }}</p>
    </div>

    <div class="section-header">Patient Information</div>
    <table class="patient-info-table">
        <tr>
            <td width="35%"><span class="label">Patient ID:</span> {{ $diagnosis->patient->id ?? '—' }}</td>
            <td width="65%"><span class="label">Full Name:</span> 
                {{ $diagnosis->patient->first_name ?? '' }} 
                {{ $diagnosis->patient->middle_name ?? '' }} 
                {{ $diagnosis->patient->last_name ?? '' }}
            </td>
        </tr>
        <tr>
            <td><span class="label">Sex:</span> {{ $diagnosis->patient->sex ?? '—' }}</td>
            <td><span class="label">Date of Birth:</span> 
                @if($diagnosis->patient && $diagnosis->patient->date_of_birth)
                    {{ \Carbon\Carbon::parse($diagnosis->patient->date_of_birth)->format('F d, Y') }}
                @else — @endif
            </td>
        </tr>
        <tr>
            <td><span class="label">Age:</span> 
                @if($diagnosis->patient && $diagnosis->patient->date_of_birth)
                    {{ \Carbon\Carbon::parse($diagnosis->patient->date_of_birth)->age }}
                @else — @endif
            </td>
            <td><span class="label">Contact Number:</span> {{ $diagnosis->patient->mobile_number ?? '—' }}</td>
        </tr>
    </table>

    <div class="section-header">Clinical Findings</div>
    <table class="findings-table">
        <tr>
            <td class="findings-label">Date of Diagnosis</td>
            <td class="findings-value">{{ $diagnosis->diagnosis_date ? \Carbon\Carbon::parse($diagnosis->diagnosis_date)->format('F d, Y') : '—' }}</td>
        </tr>
        <tr>
            <td class="findings-label">Dental Caries</td>
            <td class="findings-value">{{ $diagnosis->dental_caries ?? 'None noted' }}</td>
        </tr>
        <tr>
            <td class="findings-label">Periodontal Disease</td>
            <td class="findings-value">{{ $diagnosis->periodontal_disease ?? 'None noted' }}</td>
        </tr>
        <tr>
            <td class="findings-label">Pulpal/Periapical</td>
            <td class="findings-value">{{ $diagnosis->pulpal_periapical ?? 'None noted' }}</td>
        </tr>
        <tr>
            <td class="findings-label">Occlusal Diagnosis</td>
            <td class="findings-value">{{ $diagnosis->occlusal_diagnosis ?? 'None noted' }}</td>
        </tr>
        <tr>
            <td class="findings-label">Other Oral Conditions</td>
            <td class="findings-value">{{ $diagnosis->other_oral_conditions ?? 'None noted' }}</td>
        </tr>
    </table>

    <div class="signature-section">
        <div class="sig-box">
           
        </div>
       <div class="signature-section">
    <div class="sig-box">
        <div class="sig-line">
            {{ $patient->first_name }} {{ $patient->last_name }}
        </div>
        <span class="sig-label">Patient Signature Over Printed Name</span>
    </div>

    <div class="sig-box right">
        <div class="sig-line">
            @if(isset($physician))
                Dr. {{ $physician }}
            @else
                &nbsp; @endif
        </div>
        <span class="sig-label">Physician Signature Over Printed Name</span>
    </div>
    
    <div class="clear"></div>
</div>

</body>
</html>