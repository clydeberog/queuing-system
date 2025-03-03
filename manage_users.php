<?php
require 'db.php';
?>
<?php include 'header.php'; ?>

<h2>Manage Users</h2>
<button onclick="showPopup('userPopup', 'add_user.php')">Add New User</button>

<!-- Popup Modal -->
<div id="userPopup" class="popup">
    <div id="userPopupContent"></div>
    <button onclick="closePopup('userPopup')">Close</button>
</div>
<div id="popupOverlay" class="popup-overlay" onclick="closePopup('userPopup')"></div>

<!-- List of users will be displayed here -->

<?php include 'footer.php'; ?>
