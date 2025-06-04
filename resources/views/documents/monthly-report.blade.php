<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Monthly Report - {{ $month }}</title>
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
        .stats-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .stat-box {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
            width: 23%;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .stat-label {
            font-size: 14px;
            color: #666;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" alt="Logo" class="logo">
        <div class="title">Monthly Applications Report</div>
        <div class="subtitle">Local Government Unit of Maramag</div>
        <div class="subtitle">{{ $month }}</div>
    </div>

    <div class="content">
        <!-- Statistics Summary -->
        <div class="section">
            <div class="section-title">Summary Statistics</div>
            <div class="stats-container">
                <div class="stat-box">
                    <div class="stat-number">{{ $totalApplications }}</div>
                    <div class="stat-label">Total Applications</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number">{{ $statistics['approved'] }}</div>
                    <div class="stat-label">Approved</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number">{{ $statistics['rejected'] }}</div>
                    <div class="stat-label">Rejected</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number">{{ $statistics['pending'] }}</div>
                    <div class="stat-label">Pending</div>
                </div>
            </div>
        </div>

        <!-- Approved Applications -->
        @if($applications->has(App\Models\Application::STATUS_APPROVED))
        <div class="section">
            <div class="section-title">Approved Applications</div>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Operator</th>
                        <th>TODA</th>
                        <th>Tracking Code</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications[App\Models\Application::STATUS_APPROVED] as $application)
                        <tr>
                            <td>{{ $application->application_date->format('M d, Y') }}</td>
                            <td>{{ $application->operator->full_name }}</td>
                            <td>{{ $application->operator->toda->name }}</td>
                            <td>{{ $application->tracking_code }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Rejected Applications -->
        @if($applications->has(App\Models\Application::STATUS_REJECTED))
        <div class="section">
            <div class="section-title">Rejected Applications</div>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Operator</th>
                        <th>TODA</th>
                        <th>Reason</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications[App\Models\Application::STATUS_REJECTED] as $application)
                        <tr>
                            <td>{{ $application->application_date->format('M d, Y') }}</td>
                            <td>{{ $application->operator->full_name }}</td>
                            <td>{{ $application->operator->toda->name }}</td>
                            <td>{{ $application->notes }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Pending Applications -->
        @if($applications->has(App\Models\Application::STATUS_PENDING))
        <div class="section">
            <div class="section-title">Pending Applications</div>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Operator</th>
                        <th>TODA</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications[App\Models\Application::STATUS_PENDING] as $application)
                        <tr>
                            <td>{{ $application->application_date->format('M d, Y') }}</td>
                            <td>{{ $application->operator->full_name }}</td>
                            <td>{{ $application->operator->toda->name }}</td>
                            <td>{{ ucfirst($application->status) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    <div class="footer">
        <p>Generated on {{ now()->format('F d, Y h:i A') }}</p>
        <p>This is a system-generated report.</p>
    </div>
</body>
</html> 