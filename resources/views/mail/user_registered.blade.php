<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Welcome to Healthy Meal</title>
</head>
<body style="margin:0;padding:0;background-color:#f4f6f8;font-family:Arial,Helvetica,sans-serif;color:#333;">
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f8;padding:30px 0;">
    <tr>
      <td align="center">
        <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 6px rgba(0,0,0,0.08);">

          <!-- Header -->
          <tr>
            <td style="background:linear-gradient(90deg,#2f855a,#48bb78);padding:24px 28px;color:#ffffff;">
              <h1 style="margin:0;font-size:20px;font-weight:700;letter-spacing:0.2px;">Healthy Meal</h1>
              <p style="margin:6px 0 0;font-size:13px;opacity:0.95;">Welcome to a healthier way of eating</p>
            </td>
          </tr>

          <!-- Body -->
          <tr>
            <td style="padding:28px;">
              <p style="margin:0 0 16px;font-size:16px;">Hello <strong>{{ $user->name }}</strong>,</p>

              <p style="margin:0 0 18px;font-size:15px;line-height:1.6;color:#555;">
                Thank you for registering with <strong>Healthy Meal</strong>. We’re excited to have you on board. Below are a few things to help you get started and make the most of your account.
              </p>

              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:18px 0;">
                <tr>
                  <td style="vertical-align:top;padding-right:12px;width:48%;">
                    <div style="background:#f7faf7;border:1px solid #e6f4ea;padding:12px;border-radius:6px;">
                      <strong style="display:block;margin-bottom:6px;color:#2f855a;">Personalized Plans</strong>
                      <span style="font-size:13px;color:#666;">Get meal plans tailored to your goals.</span>
                    </div>
                  </td>
                  <td style="vertical-align:top;padding-left:12px;width:52%;">
                    <div style="background:#fff7f0;border:1px solid #fde6d6;padding:12px;border-radius:6px;">
                      <strong style="display:block;margin-bottom:6px;color:#dd6b20;">Nutrition Tracking</strong>
                      <span style="font-size:13px;color:#666;">Track calories, macros, and progress easily.</span>
                    </div>
                  </td>
                </tr>
              </table>

              <p style="margin:0 0 22px;font-size:15px;color:#555;">If you’re ready, click the button below to complete your profile and view recommended meals.</p>

              <p style="margin:0 0 26px;">
                <a href="{{ url('/settings/profile') }}" style="display:inline-block;background:#2f855a;color:#ffffff;text-decoration:none;padding:12px 20px;border-radius:6px;font-weight:600;font-size:15px;">
                  Complete Your Profile
                </a>
              </p>

              <hr style="border:none;border-top:1px solid #eef2f6;margin:18px 0;" />

              <p style="margin:0;font-size:13px;color:#777;line-height:1.5;">
                Need help? Reply to this email or visit our <a href="{{ url('/help') }}" style="color:#2f855a;text-decoration:none;">Help Center</a>.
              </p>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="background:#fbfdfe;padding:18px 28px;border-top:1px solid #eef2f6;">
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td style="font-size:12px;color:#9aa4ad;">
                    <strong>Healthy Meal</strong><br />
                    {{ env('OFFICE_ADDRESS') }}
                  </td>
                  <td align="right" style="font-size:12px;color:#9aa4ad;">
                    <div>© {{ date('Y') }} Healthy Meal</div>
                    <div style="margin-top:6px;"><a href="{{ url('/plan') }}" style="color:#9aa4ad;text-decoration:none;">Subscribe</a></div>
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
