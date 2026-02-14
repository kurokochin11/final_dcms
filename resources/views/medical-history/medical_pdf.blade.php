<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Medical History</title>

<style>
body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 12px;
    margin: 30px;
}

.header {
    text-align: center;
    margin-bottom: 20px;
}

.header h2 {
    margin: 0;
}

.patient-info {
    margin-bottom: 15px;
}

hr {
    margin: 15px 0;
}

.session-title {
    background: #f2f2f2;
    padding: 6px;
    font-weight: bold;
    margin-top: 15px;
}

.section-title {
    font-weight: bold;
    margin-top: 10px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 10px;
}

table, th, td {
    border: 1px solid #000;
}

th, td {
    padding: 6px;
    vertical-align: top;
}

.footer {
    margin-top: 30px;
    font-size: 10px;
    text-align: right;
}
</style>
</head>

<body>

<!-- HEADER -->
<div class="header">
    <h2>Patient Medical History Record</h2>
    <p>Recorded on: {{ now()->format('F j, Y') }}</p>
</div>

<!-- PATIENT INFO -->
<div class="patient-info">
    <strong>Patient Name:</strong>
    {{ $patient->first_name }} {{ $patient->last_name }} <br>

    <strong>Email:</strong>
    {{ $patient->email ?? 'N/A' }}
</div>

<hr>

<!-- SESSIONS -->
@foreach($patient->medicalSessions as $session)

<div class="session-title">
    Medical Date:
    {{ $session->created_at->format('F j, Y') }}
</div>

@php
$groupedAnswers = $session->responses->groupBy(
    fn($ans) => $ans->question->question_set ?? 'Uncategorized'
);
@endphp

@foreach($groupedAnswers as $set => $answers)

<div class="section-title">
    Section {{ $set }}
</div>

<table>
<thead>
<tr>
    <th width="70%">Question</th>
    <th>Answer</th>
</tr>
</thead>
<tbody>

@foreach($answers as $answer)
<tr>
    <td>{{ $answer->question->question_text }}</td>
    <td>{{ $answer->answer_value ?? '-' }}</td>
</tr>
@endforeach

</tbody>
</table>

@endforeach

@endforeach
<br>
 <br>
      <br>
      
<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: 50px;">

    <div style="text-align: left;">
        
       <b><span style="font-weight: bold; text-transform: capitalize; font-size: 16px; display: block; padding-bottom: 5px;">
            {{ $patient->first_name }} {{ $patient->last_name }}
        </b></span><br>
        <small>Patient Signature Over Printed Name</small>
    </div>

    <div style="text-align: right;">
      
        <b><span style="font-weight: bold; text-transform: capitalize; font-size: 16px; display: block; padding-bottom: 5px">
            Dr. {{ $physician }}
       </b></span><br>
        <small>Physician Signature Over Printed Name</small>
<br>
 <br>
   <br>
        <small style="font-style: italic;">Confidential Medical Document</small>
    </div>

</div>

</body>
</html>
