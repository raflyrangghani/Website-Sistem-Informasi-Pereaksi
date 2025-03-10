<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reagent Stock Alert</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .header {
            margin-bottom: 20px;
        }
        .header h2 {
            color: #2c3e50;
            margin: 0 0 10px;
        }
        .alert-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .alert-table th, .alert-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .alert-table th {
            background-color: #f5f5f5;
            color: #555;
        }
        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #666;
            text-align: center;
        }
        .status-out {
            color: #dc3545;
            font-weight: bold;
        }
        .status-under {
            color: #ffc107;
            font-weight: bold;
        }
        @media only screen and (max-width: 600px) {
            .container {
                padding: 10px;
            }
            .alert-table th, .alert-table td {
                padding: 8px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Reagent Stock Alert</h2>
            <p>Dear Team,</p>
            <p>This is a daily reminder regarding the current stock status of laboratory reagents that require attention. The following reagents are either out of stock or running low:</p>
        </div>

        @if($alertReagents->isEmpty())
            <p>No reagents are currently out of stock or under stock.</p>
        @else
            <table class="alert-table">
                <thead>
                    <tr>
                        <th>Reagent Name</th>
                        <th>Code</th>
                        <th>Current Stock</th>
                        <th>Unit</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alertReagents as $reagent)
                    <tr>
                        <td>{{ $reagent->nama_reagent }}</td>
                        <td>{{ $reagent->kode_reagent }}</td>
                        <td>{{ number_format($reagent->Stock, 2) }}</td>
                        <td>{{ $reagent->satuan }}</td>
                        <td class="status-{{ $reagent->Stock == 0 ? 'out' : 'under' }}">
                            {{ $reagent->status }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <p>Please take the necessary action to replenish these reagents to ensure smooth laboratory operations.</p>

        <div class="footer">
            <p>This is an automated notification from the Laboratory Management System.</p>
            <p>For questions, contact the laboratory management team.</p>
            <p>Best regards,<br>Laboratory Management System</p>
        </div>
    </div>
</body>
</html>