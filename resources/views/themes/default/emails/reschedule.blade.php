<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation Rescheduled - {{config('app.name')}}</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; background-color: #f4f4f4; padding: 20px;">

    <table style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <tr>
            <td>
                <h2 style="text-align: center; color: #2c3e50;">Your Consultation Has Been Rescheduled!</h2>
                <p style="font-size: 16px; line-height: 1.6; color: #555;">Dear [Client's Name],</p>
                <p style="font-size: 16px; line-height: 1.6; color: #555;">We hope this message finds you well. This is to confirm that your consultation with our esteemed astrologer at {{config('app.name')}} has been successfully rescheduled.</p>

                <h3 style="color: #2980b9;">New Appointment Details:</h3>

                <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
                    <tr>
                        <th>Name</th>
                        <td>[Astrologer's Name]</td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td>[Date]</td>
                    </tr>
                    <tr>
                        <th>Time</th>
                        <td>[Time]</td>
                    </tr>
                    <tr>
                        <th>Duration</th>
                        <td>[Duration]</td>
                    </tr>
                    <tr>
                        <th>Mode</th>
                        <td>[Call, Chat or Video]</td>
                    </tr>
                </table>

                <p style="font-size: 16px; line-height: 1.6; color: #555;">We appreciate your understanding and flexibility in making this change. We look forward to providing you with insightful guidance during your session.</p>

                <p style="font-size: 16px; line-height: 1.6; color: #555;">If you have any further questions or need additional assistance, please do not hesitate to reach out.</p>

                <p style="font-size: 16px; line-height: 1.6; color: #555;">Thank you for choosing {{config('app.name')}}. We look forward to serving you!</p>
                <p>Best regards,</p>
                <br>
                <strong>Team {{config('app.name')}}</strong>
                <br>
                <a href="{{url('/')}}">{{url('/')}}</a>

                <p style="font-size: 12px; color: #888; text-align: center;">
                    Please make sure to mark your calendar!
                </p>

                <hr style="border: 1px solid #ddd; margin: 20px 0;">
                <p style="font-size: 12px; color: #888; text-align: center;">
                    Please do not reply to this email. For any queries, use our official contact channels.
                </p>
            </td>
        </tr>
    </table>

</body>
</html>
