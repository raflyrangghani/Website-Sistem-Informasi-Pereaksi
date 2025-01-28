<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
        }
        .header {
            margin-bottom: 20px;
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
        }
        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #666;
        }
        .status-outofstock {
            color: #dc3545;
            font-weight: bold;
        }
        .status-understock {
            color: #ffc107;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Hi {{ $user->name }},</h2>
            <p>This is a reminder regarding the current stock status of laboratory reagents that require your attention.</p>
        </div>

        <table class="alert-table">
            <thead>
                <tr>
                    <th>Reagent Name</th>
                    <th>Current Stock</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($alertReagents as $reagent)
                <tr>
                    <td>{{ $reagent->ITEM }}</td>
                    <td>{{ $reagent->Stock }}</td>
                    <td class="status-{{ strtolower(str_replace(' ', '', $reagent->Status)) }}">
                        {{ $reagent->Status }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <p>Please take necessary action to replenish the stock of these reagents to maintain optimal laboratory operations.</p>

        <div class="footer">
            <p>This is an automated notification. If you have any questions, please contact the laboratory management.</p>
            <p>Best regards,<br>Laboratory Management System</p>
        </div>
    </div>
</body>
</html>