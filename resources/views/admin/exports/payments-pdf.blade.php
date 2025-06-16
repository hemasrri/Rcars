<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }

        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
        }
    </style>
</head>
<body>

    <h1>Payments Report</h1>

    <table>
        <thead>
            <tr>
                <th>Payment ID</th>
                <th>Application ID</th>
                <th>Semester</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Payment Date</th>
                <th>Payment Method</th>
                <th>Transaction ID</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payments as $payment)
                <tr>
                    <td>{{ $payment->payment_id }}</td>
                    <td>{{ $payment->application->application_id }}</td> <!-- Assuming payment has a relationship with Application -->
                    <td>{{ $payment->semester->semester_name ?? 'N/A' }}</td> <!-- Assuming payment has a relationship with Semester -->
                    <td>{{ number_format($payment->amount, 2) }}</td>
                    <td>{{ ucfirst($payment->payment_status) }}</td>
                    <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                    <td>{{ ucfirst($payment->payment_method) }}</td>
                    <td>{{ $payment->transaction_id }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on {{ \Carbon\Carbon::now()->format('Y-m-d H:i:s') }}</p>
    </div>

</body>
</html>
