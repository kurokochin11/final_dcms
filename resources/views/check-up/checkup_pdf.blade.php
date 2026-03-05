<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; margin: 20px; color: #333; }
    .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
    .header h2 { margin: 0; text-transform: uppercase; }
    
    /* Patient Info Grid */
    .patient-info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    .patient-info-table td { padding: 4px 0; border: none; }
    .label { font-weight: bold; text-decoration: underline; }

    .section-header { 
        background: #000; color: #fff; padding: 5px; 
        font-weight: bold; text-transform: uppercase; margin-top: 15px; 
    }

    /* Medical Questions Grid */
    .questions-container { width: 100%; margin-top: 10px; }
    .question-row { width: 100%; clear: both; margin-bottom: 5px; border-bottom: 1px dotted #ccc; padding: 5px 0; }
    .question-text { width: 70%; float: left; }
    .answer-box { width: 28%; float: right; text-align: right; }
    
    /* Checkbox Styling */
    .checkbox {
        display: inline-block;
        width: 12px;
        height: 12px;
        border: 1px solid #000;
        text-align: center;
        line-height: 12px;
        font-weight: bold;
        font-size: 12px;
        margin-right: 2px;
    }
    
    .clear { clear: both; }

    /* Footer/Signatures */
    .signature-section { margin-top: 50px; width: 100%; }
    .sig-box { width: 45%; float: left; text-align: center; }
    .sig-box.right { float: right; }
    .sig-line { border-top: 1px solid #000; margin-top: 40px; padding-top: 5px; font-weight: bold; }
</style>
</head>
<body>

<div class="header">
    <h2>Patient Check-Up Record Form</h2>
    <p>Recorded on: {{ now()->format('F j, Y') }}</p>
</div>

<div class="section-header">Identification</div>
<table class="patient-info-table">
    <tr>
        <td width="50%"><span class="label">Name:</span> {{ $patient->first_name }} {{ $patient->last_name }}</td>
        <td width="30%"><span class="label">Birthdate:</span> {{ \Carbon\Carbon::parse($patient->date_of_birth)->format('m/d/Y') }}</td>
        <td width="20%"><span class="label">Sex:</span> {{ $patient->sex }}</td>
    </tr>
    <tr>
        <td colspan="2"><span class="label">Address:</span> {{ $patient->address }}, {{ $patient->city }} {{ $patient->zip_code }}</td>
        <td><span class="label">Age:</span> {{ $patient->age }}</td>
    </tr>
    <tr>
        <td colspan="3"><span class="label">Email:</span> {{ $patient->email }} | <span class="label">Mobile:</span> {{ $patient->mobile_number }}</td>
    </tr>
</table>

@foreach($patient->checkupSessions as $session)
    <div class="section-header">Check-Up Date: {{ $session->created_at->format('F j, Y') }}</div>
    
    <div class="questions-container">
        @foreach($session->checkupResults as $result)
            <div class="question-row">
                <div class="question-text">
                    {{ $loop->iteration }}. {{ $result->question->question_text }}
                </div>
                <div class="answer-box">
                    @if($result->answer_value == 'Yes')
                        <span class="checkbox">&#10004;</span> Yes &nbsp; <span class="checkbox"></span> No
                    @elseif($result->answer_value == 'No')
                        <span class="checkbox"></span> Yes &nbsp; <span class="checkbox">&#10004;</span> No
                    @elseif(!empty($result->answer_value))
                        <span style="font-weight:bold; border-bottom: 1px solid #333; padding: 0 5px;">
                            {{ $result->answer_value }}
                        </span>
                    @else
                        <span style="color: #ccc;">N/A</span>
                    @endif
                </div>
                <div class="clear"></div>
            </div>
        @endforeach
    </div>
@endforeach

<div class="signature-section">
    <div class="sig-box">
        <div class="sig-line">{{ $patient->first_name }} {{ $patient->last_name }}</div>
        <small>Patient Signature Over Printed Name</small>
    </div>
    <div class="sig-box right">
        <div class="sig-line">Dr. {{ $physician ?? '________________' }}</div>
        <small>Physician Signature Over Printed Name</small>
    </div>
    <div class="clear"></div>
</div>

</body>
</html>