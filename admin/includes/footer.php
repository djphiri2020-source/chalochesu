    </main> <!-- end .main-content -->

    <script>
        // Using jQuery's no-conflict mode is a good practice
        (function($) {
            $(document).ready(function() {
                // Centralized event handler for the custom icon picker
                $('body').on('click', '.open-icon-picker', function() {
                    const inputId = $(this).data('inputId');
                    const previewId = $(this).data('previewId');
                    const pickerUrl = `icon_picker.php?inputId=${inputId}&previewId=${previewId}`;
                    const pickerWindow = window.open(pickerUrl, 'IconPicker', 'width=800,height=600,scrollbars=yes');
                    if (pickerWindow) {
                        pickerWindow.focus();
                    }
                });

                // Mobile menu toggle
                $('.mobile-menu-toggle-btn').on('click', function() {
                    $('.sidebar').toggleClass('active');
                });
            });
        })(jQuery);
    </script>

</body>
</html>