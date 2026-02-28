<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation to join {{ $colocation->name }}</title>
</head>

<body style="margin: 0; padding: 0; background-color: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed;">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); border: 1px solid #e2e8f0;">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="padding: 40px 40px 20px 40px; background: linear-gradient(135deg, #137fec 0%, #0b5fb7 100%);">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 800; letter-spacing: -0.025em; line-height: 1.2;">EasyColoc</h1>
                        </td>
                    </tr>
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px;">
                            <h2 style="margin: 0 0 16px 0; color: #0f172a; font-size: 24px; font-weight: 700; line-height: 1.25;">🏠 You've been invited!</h2>
                            <p style="margin: 0 0 24px 0; color: #475569; font-size: 16px; line-height: 1.6;">
                                Hi there! Your roommate has invited you to join their colocation, <strong>"{{ $colocation->name }}"</strong>, on EasyColoc.
                            </p>

                            <div style="background-color: #f1f5f9; border-radius: 12px; padding: 32px; text-align: center; border: 2px dashed #cbd5e1;">
                                <p style="margin: 0 0 12px 0; color: #64748b; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Your Invitation Token</p>
                                <div style="margin: 0; color: #137fec; font-family: 'Courier New', Courier, monospace; font-size: 36px; font-weight: 800; letter-spacing: 4px;">{{ $colocation->invite_token }}</div>
                            </div>

                            <p style="margin: 24px 0 0 0; color: #475569; font-size: 14px; line-height: 1.5; text-align: center;">
                                Simply copy the code above and paste it into the <strong>"Join House"</strong> section of your dashboard.
                            </p>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td style="padding: 0 40px 40px 40px; text-align: center;">
                            <hr style="border: 0; border-top: 1px solid #e2e8f0; margin-bottom: 24px;">
                            <p style="margin: 0; color: #94a3b8; font-size: 12px; line-height: 1.5;">
                                This invitation was sent by EasyColoc on behalf of your roommate.<br>
                                If you weren't expecting this, you can safely ignore this email.
                            </p>
                        </td>
                    </tr>
                </table>
                <table border="0" cellpadding="0" cellspacing="0" width="600">
                    <tr>
                        <td style="padding: 24px; text-align: center;">
                            <p style="margin: 0; color: #64748b; font-size: 12px;">&copy; 2026 EasyColoc. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>