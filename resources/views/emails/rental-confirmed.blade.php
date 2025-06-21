<!DOCTYPE html>
<html lang="en" style="margin: 0; padding: 0;">
<head>
    <meta charset="UTF-8">
    <title>Rental Confirmation - RentyRide</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f7;
        }
        .container {
            max-width: 650px;
            margin: auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
        }
        .header {
            background-color: #0694d5;
            color: white;
            text-align: center;
            padding: 20px;
        }
        .header h1 {
            margin: 0;
        }
        .content {
            padding: 30px;
            color: #333;
        }
        .content h2 {
            color: #0f172a;
        }
        .rental-details {
            margin-top: 20px;
        }
        .rental-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .rental-details th, .rental-details td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .footer {
            background-color: #e5f1fd;
            text-align: center;
            padding: 15px;
            font-size: 14px;
            color: #555;
        }
        .footer a {
            color: #0ea5e9;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Header -->
    <div class="header">
        <h1>RentyRide</h1>
        <p>Your Trusted Car Rental Partner</p>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h2>Rental Confirmation</h2>
        <p>Dear {{ $rental->user->name }},</p>

        <p>Thank you for booking your car with <strong>RentyRide</strong>! We're thrilled to serve you.</p>

        <p><strong>Your rental is under review.</strong> Our admin team will verify the request and notify you shortly.</p>

        <!-- Rental Details -->
        <div class="rental-details">
            <h3>Rental Details:</h3>
            <table>
                <tr>
                    <th>Customer Name</th>
                    <td>{{ $rental->user->name }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $rental->user->email }}</td>
                </tr>
                @if($rental->user->profile)
                <tr>
                    <th>Mobile</th>
                    <td>{{ $rental->user->profile->mobile }}</td>
                </tr>
                @endif
                <tr>
                    <th>Car Details</th>
                    <td> <strong>{{ $rental->car->name }}</strong> ({{ $rental->car->brand }} - {{ $rental->car->model }})</td>
                </tr> 
                <tr>
                    <th>Start Date</th>
                    <td>{{ \Carbon\Carbon::parse($rental->start_date)->format('F j, Y') }}</td>
                </tr>
                <tr>
                    <th>End Date</th>
                    <td>{{ \Carbon\Carbon::parse($rental->end_date)->format('F j, Y') }}</td>
                </tr>
                <tr>
                    <th>Daily Rent</th>
                    <td>{{ $rental->car->daily_rent_price }} BDT/Day</td>
                </tr>
               
                <tr>
                    <th>Total Cost</th>
                    <td><strong>{{ $rental->total_cost }} BDT</strong></td>
                </tr>
            </table>
        </div>

        <p>If you have any questions or need support, feel free to contact us.</p>

        <p>We hope you enjoy your ride!</p>

        <p>Warm regards,<br>
        <strong>RentyRide Team</strong></p>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Need help? Email us at <a href="mailto:support@rentyride.com">support@rentyride.com</a> or call +880 1966-560299</p>
        <p>&copy; {{ date('Y') }} RentyRide. All rights reserved.</p>
    </div>
</div>

</body>
</html>
