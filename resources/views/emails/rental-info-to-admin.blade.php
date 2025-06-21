<!DOCTYPE html>
<html lang="en" style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 0; margin: 0;">
<head>
    <meta charset="UTF-8">
    <title>New Rental Notification</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f4;">
    <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center" style="padding: 30px 0;">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    
                    <!-- Header -->
                    <tr style="background-color: #043dc3; color: #ffffff;">
                        <td style="padding: 20px; text-align: center;">
                            <h2 style="margin: 0;">New Car Rental Notification</h2>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 30px;">
                            <p>Dear Admin,</p>

                            <p>A new rental request has been submitted on <strong>RentyRide</strong>. Below are the details:</p>

                            <h4 style="margin-top: 25px;">Customer Information:</h4>
                            <ul style="padding-left: 20px;">
                                <li><strong>Name:</strong> {{ $rental->user->name }}</li>
                                <li><strong>Email:</strong> {{ $rental->user->email }}</li>
                                @if($rental->user->profile)
                                    <li><strong>Mobile:</strong> {{ $rental->user->profile->mobile }}</li>
                                    <li><strong>Address:</strong> {{ $rental->user->profile->address }}</li>
                                    <li><strong>District:</strong> {{ $rental->user->profile->district }}</li>
                                    <li><strong>Division:</strong> {{ $rental->user->profile->division }}</li>
                                @endif
                            </ul>

                            <h4 style="margin-top: 25px;">Rental Details:</h4>
                            <ul style="padding-left: 20px;">
                                <li><strong>Car:</strong> {{ $rental->car->brand }} - {{ $rental->car->model }}</li>
                                <li><strong>Daily Rent:</strong> {{ $rental->car->daily_rent_price }} BDT</li>
                                <li><strong>Start Date:</strong> {{ $rental->start_date }}</li>
                                <li><strong>End Date:</strong> {{ $rental->end_date }}</li>
                                <li><strong>Total Cost:</strong> {{ $rental->total_cost }} BDT</li>
                                <li><strong>Status:</strong> {{ $rental->status }}</li>
                            </ul>

                            <p>Please log in to the admin dashboard to review and approve the request.</p>

                            <p>Thank you.<br><strong>RentyRide System</strong></p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr style="background-color: #e2e8f0; text-align: center;">
                        <td style="padding: 20px; font-size: 14px; color: #475569;">
                            If you have any questions, contact us at 
                            <a href="mailto:support@rentyride.com" style="color: #0f172a;">support@rentyride.com</a> 
                            or call <strong>+880 1234-567890</strong>
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
