<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 10px; margin: 15px; color: #333; }
    .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 8px; margin-bottom: 15px; }
    .header h2 { margin: 0; text-transform: uppercase; font-size: 16px; }
    
    .section-header { 
        background: #000; color: #fff; padding: 4px 8px; 
        font-weight: bold; text-transform: uppercase; margin-top: 12px; 
    }

    /* Patient Info */
    .patient-info-table { width: 100%; border-collapse: collapse; margin-top: 5px; }
    .patient-info-table td { padding: 3px 0; }
    .label { font-weight: bold; text-decoration: underline; }

    /* Visual Diagrams */
    .diagram-container { width: 100%; margin-top: 10px; text-align: center; border: 1px solid #ccc; padding: 10px 0; }
    .diagram-item { width: 23%; display: inline-block; vertical-align: top; margin: 0 5px; }
    .diagram-item img { width: 100%; height: auto; max-height: 100px; border: 1px solid #ddd; }
    .diagram-caption { font-size: 8px; font-weight: bold; margin-top: 4px; border-top: 1px solid #000; }

    /* Layout Grids */
    .questions-container { width: 100%; margin-top: 5px; }
    .question-row { width: 100%; clear: both; border-bottom: 1px dotted #ccc; padding: 5px 0; }
    .question-text { width: 40%; float: left; font-weight: bold; }
    .answer-box { width: 58%; float: right; text-align: right; }
    
    .info-row { width: 100%; padding: 5px 0; border-bottom: 1px dotted #ccc; }
    .info-col { width: 33.3%; float: left; }
    
    .checkbox {
        display: inline-block; width: 10px; height: 10px;
        border: 1px solid #000; text-align: center; line-height: 10px;
        font-weight: bold; font-size: 10px; margin-right: 2px;
    }
    
    .clear { clear: both; }

    .signature-section { margin-top: 50px; width: 100%; }
    .sig-box { width: 45%; float: left; text-align: center; }
    .sig-box.right { float: right; }
    .sig-line { border-top: 1px solid #000; margin-top: 35px; padding-top: 3px; font-weight: bold; }
</style>
</head>
<body>

<div class="header">
    <h2>Intraoral Examination Report</h2>
    <p>Exam Date: {{ $exam->date ? \Carbon\Carbon::parse($exam->date)->format('F d, Y') : now()->format('F d, Y') }}</p>
</div>

<div class="section-header">Patient Identification</div>
<table class="patient-info-table">
    <tr>
        <td width="50%"><span class="label">Patient Name:</span> {{ $exam->patient->full_name ?? 'N/A' }}</td>
        <td width="25%"><span class="label">Age:</span> {{ $exam->patient->age ?? '-' }}</td>
        <td width="25%"><span class="label">Sex:</span> {{ $exam->patient->sex ?? '-' }}</td>
    </tr>
</table>

<div class="section-header">Visual Assessment (Diagrams)</div>
<div class="diagram-container">
    <div class="diagram-item">
        @if($exam->odontogram) <img src="{{ public_path('storage/'.$exam->odontogram) }}"> @else <div style="height:80px; border:1px dashed #ccc;"></div> @endif
        <div class="diagram-caption">Odontogram</div>
    </div>
    <div class="diagram-item">
        @if($exam->probing_depths) <img src="{{ public_path('storage/'.$exam->probing_depths) }}"> @else <div style="height:80px; border:1px dashed #ccc;"></div> @endif
        <div class="diagram-caption">Probing</div>
    </div>
    <div class="diagram-item">
        @if($exam->mobility) <img src="{{ public_path('storage/'.$exam->mobility) }}"> @else <div style="height:80px; border:1px dashed #ccc;"></div> @endif
        <div class="diagram-caption">Mobility</div>
    </div>
    <div class="diagram-item">
        @if($exam->furcation) <img src="{{ public_path('storage/'.$exam->furcation) }}"> @else <div style="height:80px; border:1px dashed #ccc;"></div> @endif
        <div class="diagram-caption">Furcation</div>
    </div>
    <div class="clear"></div>
</div>

<div class="section-header">Soft Tissues & Gingiva</div>
<div class="questions-container">
    <div class="question-row">
        <div class="question-text">Soft Tissue Status: {{ $exam->soft_tissues_status ?? 'Normal' }}</div>
        <div class="answer-box"><span class="label">Notes:</span> {{ $exam->soft_tissues ?? 'No specific findings recorded.' }}</div>
        <div class="clear"></div>
    </div>
</div>
<div class="info-row">
    <div class="info-col"><span class="label">Gingiva Color:</span> {{ $exam->gingiva_color ?? '-' }}</div>
    <div class="info-col"><span class="label">Texture:</span> {{ $exam->gingiva_texture ?? '-' }}</div>
    <div class="info-col"><span class="label">Bleeding:</span> {{ $exam->bleeding ?? '-' }}</div>
    <div class="clear"></div>
</div>
<div class="info-row">
    <div class="info-col"><span class="label">Bleeding Area:</span> {{ $exam->bleeding_area ?? 'None' }}</div>
    <div class="info-col"><span class="label">Recession:</span> {{ $exam->recession ?? '-' }}</div>
    <div class="info-col"><span class="label">Recession Area:</span> {{ $exam->recession_area ?? 'None' }}</div>
    <div class="clear"></div>
</div>

<div class="section-header">Teeth & Occlusion</div>
<div class="questions-container">
    <div class="question-row">
        <div class="clear"></div>
    </div>
    <div class="question-row">
        <div class="question-text">Molar Classification:</div>
        <div class="answer-box">
            Right: <strong>{{ $exam->occlusion_class ?? '-' }}</strong> &nbsp; 
            Left: <strong>{{ $exam->occlusion_other ?? '-' }}</strong>
        </div>
        <div class="clear"></div>
    </div>
    <div class="question-row">
        <div class="question-text">General Teeth Condition:</div>
        <div class="answer-box">{{ $exam->teeth_condition ?? 'Healthy' }}</div>
        <div class="clear"></div>
    </div>
    <div class="question-row">
        <div class="question-text">Measurements / Contacts:</div>
        <div class="answer-box">
            Overbite: {{ $exam->overbite ?? '0' }}% | 
            Overjet: {{ $exam->overjet ?? '0' }}mm | 
            Premature Contacts: {{ $exam->premature_contacts ?? 'None' }}
        </div>
        <div class="clear"></div>
    </div>
</div>

<div class="section-header">Oral Hygiene</div>
<div class="info-row" style="border-bottom: none;">
    <div class="info-col"><span class="label">Hygiene Status:</span> {{ $exam->hygiene_status ?? '-' }}</div>
    <div class="info-col"><span class="label">Plaque Index:</span> {{ $exam->plaque_index ?? '-' }}</div>
    <div class="info-col"><span class="label">Calculus:</span> {{ $exam->calculus ?? '-' }}</div>
    <div class="clear"></div>
</div>

<div class="signature-section">
    <div class="sig-box">
        <div class="sig-line">{{ $exam->patient->full_name ?? 'Patient Name' }}</div>
        <small>Patient Signature Over Printed Name</small>
    </div>
    <div class="sig-box right">
        <div class="sig-line">Dr. {{ $physician }}</div>
        <small>Examining Dentist Signature</small>
    </div>
    <div class="clear"></div>
</div>

</body>
</html>