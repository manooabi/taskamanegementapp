<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
</head>
<body>
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="padding: 20px; background-color: #f4f4f4;">
                <h1>Welcome, {{ $name }}!</h1>
                <p>The task "{{ $taskTitle }}" has been marked as completed.</p>
            </td>
        </tr>
        <tr>
            <td style="padding: 20px; text-align: center;">
                <p>If you have any questions or need assistance, feel free to contact us.</p>
                <p>Best regards,<br>Our Team</p>
            </td>
        </tr>
    </table>
</body>
</html>