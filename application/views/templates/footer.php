    </div> <!-- content-area -->
</div> <!-- main-content -->

<script>
    // Toggle Sidebar for Mobile
    $('#toggleSidebar').click(function() {
        $('#sidebar').toggleClass('active');
    });
    
    // Close sidebar when clicking outside on mobile
    $(document).click(function(event) {
        if (!$(event.target).closest('#sidebar').length && !$(event.target).closest('.toggle-sidebar').length) {
            if ($('#sidebar').hasClass('active')) {
                $('#sidebar').removeClass('active');
            }
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>