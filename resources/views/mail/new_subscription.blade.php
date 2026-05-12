<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>New Subscriber Notification</title>
</head>
<body style="margin:0;padding:0;background:#f4f6f8;font-family:Arial,Helvetica,sans-serif;color:#222;">
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="padding:28px 0;background:#f4f6f8;">
    <tr>
      <td align="center">
        <table role="presentation" width="640" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.06);">

          <!-- Header -->
          <tr>
            <td style="background:#0f766e;padding:18px 22px;color:#fff;">
              <h2 style="margin:0;font-size:18px;font-weight:700;">New Subscriber Alert</h2>
            </td>
          </tr>

          <!-- Body -->
          <tr>
            <td style="padding:20px 22px;">
              <p style="margin:0 0 12px;font-size:15px;">Hi <strong>Admin</strong>,</p>

              <p style="margin:0 0 14px;font-size:15px;color:#444;">
                A new subscriber has just signed up. Please find the details below and reach out promptly.
              </p>

              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:12px 0 18px;border-collapse:collapse;">
                <tr>
                  <td style="padding:10px;border:1px solid #eef2f6;border-radius:6px;background:#fbfffe;">
                    <strong style="display:block;color:#0f766e;margin-bottom:8px;">Subscriber Details</strong>
                    <div style="font-size:14px;color:#333;line-height:1.6;">
                      <div><strong>Name:</strong> {{ $subscriber->user->name }}</div>
                      <div><strong>Email:</strong> <a href="mailto:{{ $subscriber->user->email }}" style="color:#0f766e;text-decoration:none;">{{ $subscriber->user->email }}</a></div>
                      <div><strong>Phone:</strong> <a href="tel:{{ $subscriber->user->phone }}" style="color:#0f766e;text-decoration:none;">{{ $subscriber->user->phone }}</a></div>
                      <div><strong>Plan:</strong> {{ $planName }}</div>
                      <div><strong>Subscribed At:</strong> {{ $subscribedAt ?? '-' }}</div>
                      <div><strong>Payment Status:</strong> {{ $paymentStatus ?? '-' }}</div>
                    </div>
                  </td>
                </tr>
              </table>

              <p style="margin:0 0 16px;font-size:15px;color:#444;">
                Quick actions — contact the subscriber immediately or view their subscription in the admin panel.
              </p>

              <p style="margin:0 0 22px;">
                <a href="tel:{{ $subscriber->phone }}" style="display:inline-block;background:#0f766e;color:#ffffff;text-decoration:none;padding:10px 14px;border-radius:6px;font-weight:700;margin-right:8px;">Call Now</a>
                <a href="mailto:{{ $subscriber->email }}?subject=Regarding%20your%20Healthy%20Meal%20subscription" style="display:inline-block;background:#10b981;color:#ffffff;text-decoration:none;padding:10px 14px;border-radius:6px;font-weight:700;margin-right:8px;">Email</a>
                <a href="{{ $adminDashboardUrl }}" style="display:inline-block;background:#374151;color:#ffffff;text-decoration:none;padding:10px 14px;border-radius:6px;font-weight:700;">View in Admin</a>
              </p>

              <hr style="border:none;border-top:1px solid #eef2f6;margin:18px 0;" />

              <p style="margin:0;font-size:13px;color:#777;line-height:1.5;">
                Note: Payment status shown above reflects the latest update. If payment is pending, consider following up before scheduling deliveries.
              </p>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="background:#fbfdfe;padding:14px 18px;border-top:1px solid #eef2f6;font-size:12px;color:#9aa4ad;">
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td style="vertical-align:top;">
                    <strong>Healthy Meal Admin</strong><br />
                    Notifications & Operations
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
