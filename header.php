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
        .popup { display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5); z-index: 1000;}
        .popup-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 999; }
        .queue-list { margin-top: 20px; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; }
        .queue-list table { width: 100%; border-collapse: collapse; }
        .queue-list th, .queue-list td { padding: 10px; text-align: center; border-bottom: 1px solid #ddd; }
        .queue-list th { background: #007bff; color: white; }
        .clock-container { text-align: center; margin-top: 20px; font-size: 1.0em; font-weight: bold; }
        .header { display: flex; justify-content: space-between; align-items: center; background: #007bff; color: white; padding: 15px 20px; }
        .menu { display: flex; gap: 20px; }
        .menu a { color: white; text-decoration: none; font-weight: bold; transition: 0.3s; }
        .menu a:hover { text-decoration: underline; }
        .menu-toggle { display: none; cursor: pointer; font-size: 24px; }
        .clock-container { text-align: center; margin-top: 10px; font-size: 2.0em; font-weight: bold; }
        @media (max-width: 768px) {
            .menu { display: none; flex-direction: column; position: absolute; top: 60px; right: 20px; background: #007bff; padding: 10px; border-radius: 5px; }
            .menu.active { display: flex; }
            .menu-toggle { display: block; }
        }
    </style>
    <script>

        function updateClock() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
            document.getElementById('clock').innerText = now.toLocaleDateString('en-US', options);
        }
        setInterval(updateClock, 1000);
        window.onload = updateClock;

        function showPopup(target, url) {
            document.getElementById(target).classList.add('active');
            document.getElementById('popupOverlay').classList.add('active');
            fetchForm(target + 'Content', url);
        }
        function closePopup(target) {
            document.getElementById(target).classList.remove('active');
            document.getElementById('popupOverlay').classList.remove('active');
            location.reload(); // Refresh the page when closing the popup
        }
        function fetchForm(target, url) {
            fetch(url)
                .then(response => response.text())
                .then(data => {
                    document.getElementById(target).innerHTML = data;
                });
        }
        function submitForm(event, formId, popupId) {
            event.preventDefault();
            let form = document.getElementById(formId);
            let formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data); // Display response message
                closePopup(popupId); // Close the popup
            });
        }
    </script>
</head>
<body>
    <div class="header">
        <h2>MDC Queue System</h2>
            <div class="clock-container">
                <span id="clock"></span>
            </div>
        <span class="menu-toggle" onclick="toggleMenu()">☰</span>
        <nav id="menu" class="menu">
            <a href="public_view.php">Display</a>
            <a href="admin.php">Admin</a>
            <a href="logout.php">Logout</a>
        </nav>
    </div>
