<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Franchise Certificate</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            width: 100px;
            height: auto;
            margin-bottom: 10px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 18px;
            margin-bottom: 20px;
        }
        .certificate-number {
            font-size: 14px;
            margin-bottom: 30px;
        }
        .content {
            margin-bottom: 30px;
            line-height: 1.6;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
        }
        .signature-line {
            width: 200px;
            border-top: 1px solid #000;
            margin: 10px auto;
        }
        .qr-code {
            position: absolute;
            bottom: 20px;
            right: 20px;
            width: 100px;
            height: 100px;
        }
        .validity {
            margin-top: 20px;
            font-size: 14px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" alt="Logo" class="logo">
        <div class="title">FRANCHISE CERTIFICATE</div>
        <div class="subtitle">Local Government Unit of Maramag</div>
        <div class="certificate-number">Certificate No: {{ $certificateNumber }}</div>
    </div>

    <div class="content">
        <p>This is to certify that:</p>
        
        <h2 style="text-align: center; margin: 20px 0;">{{ $operator->full_name }}</h2>
        
        <p>with residence at {{ $operator->address }}, is hereby granted a franchise to operate a Motorela
        under the {{ $operator->toda->name }} (TODA) in the Municipality of Maramag, subject to existing rules
        and regulations.</p>

        <div style="margin: 30px 0;">
            <strong>Vehicle Details:</strong><br>
            @if($operator->motorcycles->isNotEmpty())
                <?php $motorcycle = $operator->motorcycles->first(); ?>
                MTOP No: {{ $motorcycle->mtop_no }}<br>
                Motor No: {{ $motorcycle->motor_no }}<br>
                Plate No: {{ $motorcycle->plate_no }}<br>
                Make/Model: {{ $motorcycle->make }} ({{ $motorcycle->year_model }})
            @endif
        </div>

        <div class="validity">
            <p>Valid from: {{ $issueDate->format('F d, Y') }}<br>
            Valid until: {{ $validUntil->format('F d, Y') }}</p>
        </div>
    </div>

    <div class="footer">
        <div style="margin-bottom: 40px;">
            <div class="signature-line"></div>
            <div>Municipal Mayor</div>
            <div>Municipality of Maramag</div>
        </div>

        <div>
            <div class="signature-line"></div>
            <div>TODA President</div>
            <div>{{ $operator->toda->name }}</div>
        </div>
    </div>

    @if($operator->qr_code_path)
        <img src="{{ Storage::disk('public')->path($operator->qr_code_path) }}" alt="QR Code" class="qr-code">
    @endif
</body>
</html> 