<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Invoice — Healthy Meal</title>
</head>

<body style="margin:0;padding:0;background:#f4f6f8;font-family:Arial,Helvetica,sans-serif;color:#333;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="padding:30px 0;background:#f4f6f8;">
        <tr>
            <td align="center">
                <table role="presentation" width="640" cellpadding="0" cellspacing="0"
                    style="background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.06);">

                    <!-- Header -->
                    <tr>
                        <td style="background:linear-gradient(90deg,#2f855a,#48bb78);padding:20px 24px;color:#fff;">
                            <h1 style="margin:0;font-size:20px;font-weight:700;">Healthy Meal</h1>
                            <p style="margin:6px 0 0;font-size:13px;opacity:0.95;">Invoice for your subscription</p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:24px;">
                            <p style="margin:0 0 14px;font-size:16px;">Hi
                                <strong>{{ $subscriber->user->name }}</strong>,
                            </p>

                            <p style="margin:0 0 18px;font-size:15px;color:#555;line-height:1.6;">
                                Thank you for choosing <strong>Healthy Meal</strong>. Please find your invoice details
                                below. Complete the payment to activate your meal deliveries.
                            </p>

                            <table role="presentation" width="100%" cellpadding="10" cellspacing="0"
                                style="border-collapse:collapse;margin:18px 0;">
                                <tr style="background:#f7faf7;border-top:1px solid #eef2f6;">
                                    <td
                                        style="font-size:14px;color:#333;border:1px solid #eef2f6;border-right:none;width:50%;">
                                        <strong>Invoice Number</strong>
                                    </td>
                                    <td style="font-size:14px;color:#333;border:1px solid #eef2f6;">
                                        #{{ $invoiceNumber ?? $subscriber->id }}</td>
                                </tr>
                                <tr>
                                    <td style="font-size:14px;color:#333;border:1px solid #eef2f6;border-right:none;">
                                        <strong>Plan</strong>
                                    </td>
                                    <td style="font-size:14px;color:#333;border:1px solid #eef2f6;">
                                        {{ $subscriber->plan->planCategory->name }}</td>
                                </tr>
                                <tr style="background:#f7faf7;">
                                    <td style="font-size:14px;color:#333;border:1px solid #eef2f6;border-right:none;">
                                        <strong>Amount Due</strong>
                                    </td>
                                    <td style="font-size:14px;color:#333;border:1px solid #eef2f6;">
                                        {{ $subscriber->total }} BHD</td>
                                </tr>
                            </table>

                            <p style="margin:0 0 18px;font-size:15px;color:#555;">
                                To complete your subscription and confirm deliveries, please contact us.
                            </p>
                            <p style="margin:0 0 22px;">
                                <a href="https://wa.me/{{ env('WHATSAPP_NUMBER') }}" target="_blank"
                                    style="display:inline-block;background:#2f855a;color:#ffffff;text-decoration:none;padding:12px 20px;border-radius:6px;font-weight:700;font-size:15px;">
                                    Contact Us Now
                                </a>
                            </p>

                            <p style="margin:0 0 14px;font-size:14px;color:#666;line-height:1.6;">
                                After payment: <strong>your plan will be activated</strong>, our chefs will begin
                                prepping, and you will receive a confirmation email titled <em>"Your Healthy Meal
                                    journey starts now!"</em>
                            </p>

                            <hr style="border:none;border-top:1px solid #eef2f6;margin:18px 0;" />

                            <p style="margin:0;font-size:13px;color:#777;line-height:1.5;">
                                Need help? Reply to this email or visit our <a href="{{ url('/help') }}"
                                    style="color:#2f855a;text-decoration:none;">Help Center</a>.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td
                            style="background:#fbfdfe;padding:16px 20px;border-top:1px solid #eef2f6;font-size:12px;color:#9aa4ad;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="vertical-align:top;">
                                        <strong>Healthy Meal</strong><br />
                                        {{ env('OFFICE_ADDRESS') }}
                                    </td>
                                    <td align="right" style="vertical-align:top;">
                                        © {{ date('Y') }} Healthy Meal
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>

</html>
