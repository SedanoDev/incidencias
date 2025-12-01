<?php
// Check login state to close divs
$is_logged_in = isset($_SESSION['user_id']);
?>

<?php if ($is_logged_in): ?>
        </div> <!-- End content-area -->
    </div> <!-- End main-content -->
</div> <!-- End app-container -->

<script>
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.user-menu')) {
            const dropdown = document.getElementById('userDropdown');
            if (dropdown) dropdown.classList.remove('show');
        }

        if (!e.target.closest('.navbar-icon')) {
            const notifDropdown = document.getElementById('notificationDropdown');
            if (notifDropdown) notifDropdown.classList.remove('show');
        }
    });
</script>
<?php else: ?>
    </div> <!-- End container -->
<?php endif; ?>

</body>
</html>
