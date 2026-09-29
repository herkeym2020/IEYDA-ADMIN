<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>IEYDA Acknowledgement</title>
  <style>
    body { font-family: Arial, sans-serif; background:#f7f7f9; color:#222; }
    .box { max-width:600px; margin:20px auto; background:#fff; padding:24px; border-radius:12px; border:1px solid #eee; }
    .btn { display:inline-block; background:#5b3df0; color:#fff; text-decoration:none; padding:10px 16px; border-radius:8px; }
  </style>
 </head>
<body>
  <div class="box">
    <h2>Thank you{{ isset($name) ? ', '.e($name) : '' }}!</h2>
    <p>
      We’ve received your submission and appreciate your interest in becoming a financial member.
      Our team will reach out to you shortly with next steps.
    </p>
    <p>
      If you have any questions, simply reply to this email.
    </p>
    <p>
      Warm regards,<br />IEYDA Team
    </p>
  </div>
</body>
</html>
