<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MDC Queue System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: Poppins, sans-serif; text-align: center; }
        .queue-container { display: flex; justify-content: center; gap: 20px; flex-wrap: wrap; }
        .queue-box { border: 1px solid #000; padding: 20px; width: 200px; }
        .admin-panel { margin-top: 20px; }
        .admin-options { display: flex; justify-content: center; gap: 20px; flex-wrap: wrap; list-style: none; padding: 0; }
        .admin-options a { display: flex; align-items: center; gap: 8px; background: #007bff; color: white; padding: 12px 20px; border-radius: 5px; text-decoration: none; font-weight: bold; transition: 0.3s; }
        .admin-options a:hover { background: #0056b3; }
        .admin-section { max-width: 600px; margin: auto; padding: 20px; border: 1px solid #000; wrap; list-style: none; }
        .footer { background: #007bff; color: white; padding: 15px; position: fixed; bottom: 0; width: 100%; text-align: center; font-weight: bold; }
    </style>
</head>
<body>
    <div class="footer">
        &copy; <?php echo date('Y'); ?> MDC Queue System. All rights reserved.
    </div>
</body>
</html>
