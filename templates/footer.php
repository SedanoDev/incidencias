<?php
// Check login state to close divs
$is_logged_in = isset($_SESSION['user_id']);
?>

<?php if ($is_logged_in): ?>
    </div> <!-- End main-content -->

<script>
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.user-profile')) {
            const dropdown = document.getElementById('userDropdown');
            if (dropdown && dropdown.classList.contains('show')) dropdown.classList.remove('show');
        }

        if (!e.target.closest('.navbar-icon')) {
            const notifDropdown = document.getElementById('notificationDropdown');
            if (notifDropdown && notifDropdown.classList.contains('show')) notifDropdown.classList.remove('show');
        }
    });
</script>
<?php endif; ?>

</body>
</html>
