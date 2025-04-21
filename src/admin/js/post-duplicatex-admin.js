
jQuery(document).ready(function($) {
    // Admin settings page functionality
    $('.pdx-admin-card input[type="checkbox"]').on('change', function() {
        const parentField = $(this).closest('tr');
        if ($(this).is(':checked')) {
            parentField.addClass('active');
        } else {
            parentField.removeClass('active');
        }
    });
});