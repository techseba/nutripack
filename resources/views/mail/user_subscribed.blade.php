<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Complete your subscription to start your Healthy Meal journey 🥗</title>
</head>
<body style="margin:0;padding:0;background:#f4f6f8;font-family:Arial,Helvetica,sans-serif;color:#333;">
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="padding:30px 0;background:#f4f6f8;">
    <tr>
      <td align="center">
        <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.06);">

          <!-- Header -->
          <tr>
            <td style="background:linear-gradient(90deg,#2f855a,#48bb78);padding:20px 24px;color:#fff;">
              <h1 style="margin:0;font-size:20px;font-weight:700;">Healthy Meal</h1>
              <p style="margin:6px 0 0;font-size:13px;opacity:0.95;">Complete your subscription to start your Healthy Meal journey</p>
            </td>
          </tr>

          <!-- Body -->
          <tr>
            <td style="padding:26px 28px;">
              <p style="margin:0 0 16px;font-size:16px;">Hi <strong>{{ $user->name }}</strong>,</p>

              <p style="margin:0 0 14px;font-size:15px;line-height:1.6;color:#555;">
                Thanks for choosing <strong>Healthy Meal</strong>! We’ve received your subscription request for the <strong>{{ $planName }}</strong>.
              </p>

              <p style="margin:0 0 18px;font-size:15px;line-height:1.6;color:#555;">
                Your meal plan is currently in <b>"Pending" status.</b> To activate your subscription and start receiving your fresh meals, please complete your payment.
              </p>

              <p style="margin:0 0 18px;font-size:15px;line-height:1.6;color:#555;">
                <b>Healthy Meal will contact you very soon</b> to discuss your preferences and finalize the details. However, if you have any questions right now or want to speed up the process, feel free to reach out to us directly!
              </p>

              <p style="margin:0 0 18px;font-size:15px;line-height:1.6;color:#555;">
                You can contact our support team using the buttons below:
              </p>

              <p style="margin:0 0 22px;">
                <a href="https://wa.me/{{ env('WHATSAPP_NUMBER') }}" target="_blank" style="display:inline-block;background:#2f855a;color:#ffffff;text-decoration:none;padding:12px 20px;border-radius:6px;font-weight:700;font-size:15px;">
                  Contact Us Now
                </a>
              </p>

              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:12px 0 20px;">
                <tr>
                  <td style="font-size:14px;color:#444;padding:10px;background:#f7faf7;border:1px solid #eef6ee;border-radius:6px;">
                    <strong style="color:#2f855a;display:block;margin-bottom:6px;">Once you pay</strong>
                    <ul style="margin:0;padding-left:18px;color:#555;line-height:1.6;">
                      <li>Your meal plan will be officially activated.</li>
                      <li>Our chefs will start prepping your fresh ingredients.</li>
                      <li>You’ll receive a confirmation email titled "Your Healthy Meal journey starts now!"</li>
                    </ul>
                  </td>
                </tr>
              </table>

              <p style="margin:0 0 18px;font-size:15px;color:#555;">
                If you're having any trouble with the payment or have questions about the plan, just reply to this email. We're here to help.
              </p>

              <p style="margin:0 0 6px;font-size:15px;color:#555;">To your health,</p>
              <p style="margin:0;font-size:15px;color:#2f855a;font-weight:700;">The Healthy Meal Team</p>

              <hr style="border:none;border-top:1px solid #eef2f6;margin:20px 0;" />

              <p style="margin:0;font-size:13px;color:#888;line-height:1.5;">
                Need help? Reply to this email or visit our <a href="{{ url('/help') }}" style="color:#2f855a;text-decoration:none;">Help Center</a>.
              </p>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="background:#fbfdfe;padding:16px 24px;border-top:1px solid #eef2f6;font-size:12px;color:#9aa4ad;">
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td style="vertical-align:top;">
                    <strong>Healthy Meal</strong><br />
                    {{ env('OFFICE_ADDRESS') }}
                  </td>
                  <td align="right" style="vertical-align:top;">
                    © {{ date('Y') }} Healthy Meal<br />
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
