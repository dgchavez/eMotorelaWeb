<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Franchise Cancellation Certificate</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header img {
            max-width: 100px;
            margin: 0 20px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            margin: 20px 0;
        }
        .subtitle {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .content {
            margin: 20px 0;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
        }
        .signature-line {
            width: 200px;
            border-top: 1px solid black;
            margin: 10px auto;
        }
        table {
            width: 100%;
            margin: 20px 0;
        }
        th {
            text-align: left;
            padding: 5px;
            width: 150px;
        }
        td {
            padding: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" alt="Logo" class="logo">
        <div class="title">MUNICIPAL ADMINISTRATOR OFFICE</div>
        <div class="subtitle">BUSINESS PERMIT AND LICENSING DIVISION</div>
        <div>MOTORELA FRANCHISE SECTION</div>
    </div>

    <div style="text-align: right; margin-bottom: 20px;">
        Date: {{ $operator->franchiseCancellation->cancellation_date->format('F d, Y') }}
    </div>

    <div class="title" style="text-align: center;">
        CERTIFICATION FOR CANCELLATION OF FRANCHISE
    </div>

    <div class="content">
        <p>This is to certify that franchise issued to <strong>{{ $operator->full_name }}</strong> of 
        <strong>{{ $operator->address }}</strong> to operate motorized tricycle with 
        Case No. <strong>{{ $operator->case_number }}</strong> Make <strong>{{ $operator->motorcycles->first()->make }}</strong>, 
        Motor No. <strong>{{ $operator->motorcycles->first()->motor_no }}</strong> 
        Chassis No. <strong>{{ $operator->motorcycles->first()->chassis_no }}</strong> 
        with Plate No. <strong>{{ $operator->motorcycles->first()->plate_no }}</strong> 
        within the municipality is hereby cancelled effective to date, 
        <strong>{{ $operator->franchiseCancellation->cancellation_date->format('F d, Y') }}</strong>.</p>

        <p>This Certification is issued upon request for legal purposes.</p>

        <table style="margin-top: 30px;">
            <tr>
                <th>Date</th>
                <td>: {{ $operator->franchiseCancellation->cancellation_date->format('F d, Y') }}</td>
            </tr>
            <tr>
                <th>Paid under O.R. No.</th>
                <td>: {{ $operator->franchiseCancellation->or_number }}</td>
            </tr>
            <tr>
                <th>Amount</th>
                <td>: Php {{ number_format($operator->franchiseCancellation->amount, 2) }}</td>
            </tr>
            <tr>
                <th>Issued</th>
                <td>: {{ $operator->franchiseCancellation->created_at->format('F d, Y') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <div style="margin-bottom: 40px;">
            <div class="signature-line"></div>
            <div>LICENSING OFFICER III</div>
        </div>
    </div>
</body>
</html> 