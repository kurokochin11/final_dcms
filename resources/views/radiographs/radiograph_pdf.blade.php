<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
      
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; margin: 20px; color: #333; line-height: 1.4; }
        
        /* Header & Logo Section */
.header-top { 
    width: 100%; 
    border-bottom: 2px solid #2c3e50; 
    padding-bottom: 10px; 
    margin-bottom: 15px; 
}

.tooth-logo {
    float: left;
    width: 60px; /* Normal logo size */
    height: 60px;
    background-image: url("{{ public_path('tooth.png') }}");
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
}

.hospital-name { 
    font-size: 22px; 
    font-weight: bold; 
    color: #004a99; 
    text-transform: uppercase; 
    text-align: center;
}

.clear {
    clear: both;
}
        
        /* Patient Info Grid (Matches the Image Layout) */
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; }
        .info-table td { padding: 8px 5px; vertical-align: top; border-right: 1px solid #eee; }
        .info-table td:last-child { border-right: none; }
        
        .label { font-weight: bold; display: block; font-size: 10px; color: #666; text-transform: uppercase; }
        .value { font-size: 13px; font-weight: bold; color: #000; }

        /* Report Title */
        .report-title { text-align: center; margin: 20px 0; }
        .report-title h2 { margin: 0; font-size: 16px; text-decoration: underline; text-transform: uppercase; }

        /* Findings/Content */
        .content-section { margin-bottom: 20px; padding: 0 10px; }
        .findings-text { font-size: 12px; white-space: pre-line; }

        /* Image Display */
        .image-box { text-align: center; margin: 20px 0; background: #f9f9f9; padding: 10px; border: 1px solid #ddd; }
        .image-box img { max-width: 80%; height: auto; border: 2px solid #000; }

        /* Signature Section */
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
        .footer { clear: both; margin-top: 40px; font-size: 9px; color: #777; border-top: 1px solid #eee; padding-top: 5px; }
    </style>
</head>
<body>

    <div class="header-top">
         <div class="tooth-logo"></div>
        <table width="100%">
            <tr>
                <td width="60%">
                    <div class="hospital-name">Radiograph form</div>
                    <!-- <small>Professional Imaging & Diagnostic Center</small> -->
                </td>
                <td width="40%" style="text-align: right; font-size: 10px;">
                    <!-- Email: support@imagingcenter.com<br>
                    Contact: +123 456 7890 -->
                </td>
            </tr>
        </table>
    </div>

    <table class="info-table">
        <tr>
            <td width="35%">
                <span class="label">Patient Name</span>
                <span class="value">{{ $radiograph->patient->first_name }} {{ $radiograph->patient->last_name }}</span>
                <span style="font-size: 10px; color: #666;">Age: {{ $radiograph->patient->age }} | Sex: {{ $radiograph->patient->sex }}</span>
            </td>
            <td width="25%">
                <span class="label">Patient ID</span>
                <span class="value">#{{ str_pad($radiograph->patient->id, 5, '0', STR_PAD_LEFT) }}</span>
                <span class="label" style="margin-top:5px;">Ref. By</span>
                <span class="value" style="font-size: 11px;">{{ $radiograph->patient->referred_by ?? 'Self' }}</span>
            </td>
            <td width="40%" style="text-align: right;">
                <span class="label">Registered On:</span>
                <span class="value" style="font-size: 11px;">{{ \Carbon\Carbon::parse($radiograph->patient->date_registered)->format('h:i A d M, Y') }}</span>
                <br>
                <span class="label">Reported On:</span>
                <span class="value" style="font-size: 11px;">{{ now()->format('h:i A d M, Y') }}</span>
            </td>
        </tr>
    </table>

    <div class="report-title">
        <h2>{{ strtoupper($radiograph->type) }} REPORT</h2>
    </div>

    <div class="content-section">
        <span class="label" style="margin-bottom: 5px; font-size: 12px;">Clinical Findings:</span>
        <div class="findings-text">
            {{ $radiograph->findings ?? 'No specific abnormalities detected in the visualized fields.' }}
        </div>
    </div>

    @if($radiograph->image_path && Storage::disk('public')->exists($radiograph->image_path))
        <div class="image-box">
            <img src="{{ public_path('storage/'.$radiograph->image_path) }}">
            <p style="font-size: 9px; color: #555;">Fig 1: {{ $radiograph->type }} - Date Taken: {{ \Carbon\Carbon::parse($radiograph->date_taken)->format('m/d/Y') }}</p>
        </div>
    @endif

    

    <div class="sig-box right">
        <div class="sig-line">
            @if(isset($physician))
                Dr. {{ $physician }}
            @else
                &nbsp; @endif
        </div>
        <span class="sig-label">Physician Signature Over Printed Name</span>
    </div>
    
    

    <div class="footer">
        *** End of Report ***<br>
    </div>

</body>
</html>