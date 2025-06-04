<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Motorela Permit</title>
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
        .permit-number {
            font-size: 14px;
            margin-bottom: 30px;
        }
        .content {
            margin-bottom: 30px;
            line-height: 1.6;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" alt="Logo" class="logo">
        <div class="title">MOTORELA OPERATOR PERMIT</div>
        <div class="subtitle">Local Government Unit of Maramag</div>
        <div class="permit-number">Permit No: {{ $permitNumber }}</div>
    </div>

    <div class="content">
        <!-- Operator Information -->
        <div class="section">
            <div class="section-title">Operator Information</div>
            <table>
                <tr>
                    <th>Name:</th>
                    <td>{{ $operator->full_name }}</td>
                    <th>TODA:</th>
                    <td>{{ $operator->toda->name }}</td>
                </tr>
                <tr>
                    <th>Address:</th>
                    <td colspan="3">{{ $operator->address }}</td>
                </tr>
                <tr>
                    <th>Contact:</th>
                    <td>{{ $operator->contact_no }}</td>
                    <th>Email:</th>
                    <td>{{ $operator->email }}</td>
                </tr>
            </table>
        </div>

        <!-- Vehicle Information -->
        <div class="section">
            <div class="section-title">Vehicle Information</div>
            @if($operator->motorcycles->isNotEmpty())
                <?php $motorcycle = $operator->motorcycles->first(); ?>
                <table>
                    <tr>
                        <th>MTOP No:</th>
                        <td>{{ $motorcycle->mtop_no }}</td>
                        <th>Plate No:</th>
                        <td>{{ $motorcycle->plate_no }}</td>
                    </tr>
                    <tr>
                        <th>Motor No:</th>
                        <td>{{ $motorcycle->motor_no }}</td>
                        <th>Chassis No:</th>
                        <td>{{ $motorcycle->chassis_no }}</td>
                    </tr>
                    <tr>
                        <th>Make/Model:</th>
                        <td>{{ $motorcycle->make }}</td>
                        <th>Year:</th>
                        <td>{{ $motorcycle->year_model }}</td>
                    </tr>
                </table>
            @endif
        </div>

        <!-- Authorized Drivers -->
        <div class="section">
            <div class="section-title">Authorized Drivers</div>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>License No</th>
                        <th>License Expiry</th>
                        <th>Contact</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($operator->drivers as $driver)
                        <tr>
                            <td>{{ $driver->full_name }}</td>
                            <td>{{ $driver->drivers_license_no }}</td>
                            <td>{{ $driver->license_expiry_date->format('M d, Y') }}</td>
                            <td>{{ $driver->contact_no }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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