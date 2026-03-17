<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        /* Container to hold both logo and text */
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

        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; margin: 20px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { margin: 0; text-transform: uppercase; }
        
        /* Patient Info Grid - Copied from design */
        .patient-info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .patient-info-table td { padding: 4px 0; border: none; }
        .label { font-weight: bold; text-decoration: underline; }

        .section-header { 
            background: #000; color: #fff; padding: 5px; 
            font-weight: bold; text-transform: uppercase; margin-top: 15px; 
        }

        /* Medical Questions Grid */
        .questions-container { width: 100%; margin-top: 10px; }
        .question-row { width: 100%; clear: both; margin-bottom: 5px; border-bottom: 1px dotted #ccc; padding: 3px 0; }
        .question-text { width: 75%; float: left; }
        .answer-box { width: 22%; float: right; text-align: right; }
        
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

    </style>
</head>
<body>

<div class="header">
    <div class="tooth-logo"></div>
    <h2>Extraoral Examination Report</h2>
    <p>Recorded on: {{ \Carbon\Carbon::parse($exam->examination_date)->format('F j, Y') }}</p>
</div>

<div class="section-header">Identification</div>
<table class="patient-info-table">
    <tr>
        <td width="50%"><span class="label">Name:</span> {{ $exam->patient->first_name }} {{ $exam->patient->last_name }}</td>
        <td width="30%"><span class="label">Birthdate:</span> {{ \Carbon\Carbon::parse($exam->patient->date_of_birth)->format('m/d/Y') }}</td>
        <td width="20%"><span class="label">Sex:</span> {{ $exam->patient->sex }}</td>
    </tr>
    <tr>
        <td colspan="2"><span class="label">Address:</span> {{ $exam->patient->address }}, {{ $exam->patient->city }} {{ $exam->patient->zip_code }}</td>
        <td><span class="label">Age:</span> {{ $exam->patient->age }}</td>
    </tr>
    <tr>
        <td colspan="3"><span class="label">Email:</span> {{ $exam->patient->email }} | <span class="label">Mobile:</span> {{ $exam->patient->mobile_number }}</td>
    </tr>
</table>

<div class="section-header">Examination Results</div>
<div class="questions-container">
    <div class="question-row">
        <div class="question-text">1. Facial Symmetry</div>
        <div class="answer-box"><strong>{{ $exam->facial_symmetry }}</strong></div>
        <div class="clear"></div>
    </div>
    
    <div class="question-row">
        <div class="question-text">2. Lymph Nodes Status (Location: {{ $exam->lymph_nodes_location }})</div>
        <div class="answer-box"><strong>{{ $exam->lymph_nodes }}</strong></div>
        <div class="clear"></div>
    </div>

    <div class="question-row">
        <div class="question-text">3. TMJ Pain</div>
        <div class="answer-box">
            @if($exam->tmj_pain)
                <span class="checkbox">&#10004;</span> Yes &nbsp; <span class="checkbox"></span> No
            @else
                <span class="checkbox"></span> Yes &nbsp; <span class="checkbox">&#10004;</span> No
            @endif
        </div>
        <div class="clear"></div>
    </div>

    <div class="question-row">
        <div class="question-text">4. TMJ Clicking</div>
        <div class="answer-box">
            @if($exam->tmj_clicking)
                <span class="checkbox">&#10004;</span> Yes &nbsp; <span class="checkbox"></span> No
            @else
                <span class="checkbox"></span> Yes &nbsp; <span class="checkbox">&#10004;</span> No
            @endif
        </div>
        <div class="clear"></div>
    </div>

    <div class="question-row">
        <div class="question-text">5. Maximum Interincisal Opening (MIO)</div>
        <div class="answer-box"><strong>{{ $exam->mio }} mm</strong></div>
        <div class="clear"></div>
    </div>
</div>

@if($exam->notes)
<div class="section-header">Notes</div>
<div style="padding-top: 10px;">{{ $exam->notes }}</div>
@endif

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