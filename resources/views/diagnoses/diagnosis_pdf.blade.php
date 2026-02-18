<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Diagnosis Report - {{ $diagnosis->patient->last_name ?? 'Report' }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 13px; line-height: 1.6; color: #333; margin: 0; padding: 0; }
        .header { background-color: #007BFF; color: #fff; padding: 20px; text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; text-transform: uppercase; letter-spacing: 1px; }
        
        .section-title { background: #f0f0f0; padding: 5px 10px; font-weight: bold; border-left: 4px solid #007BFF; margin-bottom: 10px; text-transform: uppercase; font-size: 12px; }
        .section { margin-bottom: 25px; padding: 0 20px; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; table-layout: fixed; }
        td { padding: 10px; border: 1px solid #ddd; vertical-align: top; word-wrap: break-word; }
        .label { font-weight: bold; width: 30%; background-color: #fafafa; color: #555; }
        .value { width: 70%; }

        .footer { margin-top: 60px; text-align: center; }
        .signature-line { width: 250px; border-bottom: 1px solid #333; margin: 0 auto 5px auto; }
        .physician-name { font-weight: bold; font-size: 14px; margin: 0; }
        .physician-label { font-size: 11px; color: #777; margin: 0; }
        
        @page { margin: 0; } /* Ensures header spans full width if needed, or adjust to 0.5in */
    </style>
</head>
<body>
    <div class="header">
        <h1>Diagnosis Report</h1>
    </div>

    <div class="section">
        <div class="section-title">Patient Information</div>
        <table>
            <tr>
                <td class="label">Patient ID</td>
                <td class="value">{{ $diagnosis->patient->id ?? '—' }}</td>
            </tr>
            <tr>
                <td class="label">Full Name</td>
                <td class="value">
                    {{ $diagnosis->patient->first_name ?? '' }} 
                    {{ $diagnosis->patient->middle_name ?? '' }} 
                    {{ $diagnosis->patient->last_name ?? '' }}
                </td>
            </tr>
            <tr>
                <td class="label">Gender</td>
                {{-- Fixed: Changed 'gender' to 'sex' based on your PatientController --}}
                <td class="value">{{ $diagnosis->patient->sex ?? '—' }}</td>
            </tr>
            <tr>
                <td class="label">Date of Birth</td>
                <td class="value">
                    {{-- Fixed: Changed 'birth_date' to 'date_of_birth' --}}
                    @if($diagnosis->patient && $diagnosis->patient->date_of_birth)
                        {{ \Carbon\Carbon::parse($diagnosis->patient->date_of_birth)->format('F d, Y') }}
                    @else
                        —
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">Age</td>
                <td class="value">
                    {{-- Fixed: Changed 'birth_date' to 'date_of_birth' --}}
                    @if($diagnosis->patient && $diagnosis->patient->date_of_birth)
                        {{ \Carbon\Carbon::parse($diagnosis->patient->date_of_birth)->age }} years old
                    @else
                        —
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">Contact Number</td>
                {{-- Fixed: Changed 'contact_number' to 'mobile_number' --}}
                <td class="value">{{ $diagnosis->patient->mobile_number ?? '—' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Clinical Findings</div>
        <table>
            <tr>
                <td class="label">Date of Diagnosis</td>
                <td class="value">
                    {{ $diagnosis->diagnosis_date ? \Carbon\Carbon::parse($diagnosis->diagnosis_date)->format('F d, Y') : '—' }}
                </td>
            </tr>
            <tr>
                <td class="label">Dental Caries</td>
                <td class="value">{{ $diagnosis->dental_caries ?? 'None noted' }}</td>
            </tr>
            <tr>
                <td class="label">Periodontal Disease</td>
                <td class="value">{{ $diagnosis->periodontal_disease ?? 'None noted' }}</td>
            </tr>
            <tr>
                <td class="label">Pulpal/Periapical</td>
                <td class="value">{{ $diagnosis->pulpal_periapical ?? 'None noted' }}</td>
            </tr>
            <tr>
                <td class="label">Occlusal Diagnosis</td>
                <td class="value">{{ $diagnosis->occlusal_diagnosis ?? 'None noted' }}</td>
            </tr>
            <tr>
                <td class="label">Other Oral Conditions</td>
                <td class="value">{{ $diagnosis->other_oral_conditions ?? 'None noted' }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <div class="signature-line"></div>
        <p class="physician-name">{{ $physician ?? '____________________' }}</p>
        <p class="physician-label">Attending Physician / Dentist</p>
        <p style="font-size: 10px; color: #999; margin-top: 20px;">
            Report generated via System on {{ now()->format('m/d/Y h:i A') }}
        </p>
    </div>
</body>
</html>