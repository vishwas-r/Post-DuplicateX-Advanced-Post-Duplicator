jQuery(document).ready(function($) {
    // Highlight active settings on load
    $('.pdx-checkbox-item input[type="checkbox"]:checked').each(function() {
        $(this).closest('.pdx-checkbox-item').addClass('active');
    });
    
    // Toggle active class on checkbox change
    $('.pdx-checkbox-item input[type="checkbox"]').on('change', function() {
        if ($(this).is(':checked')) {
            $(this).closest('.pdx-checkbox-item').addClass('active');
        } else {
            $(this).closest('.pdx-checkbox-item').removeClass('active');
        }
    });
    
    // Add visual feedback when hovering over settings cards
    $('.pdx-admin-card, .pdx-admin-sidebar-widget').hover(
        function() {
            $(this).css('box-shadow', '0 4px 12px rgba(0, 0, 0, 0.1)');
        },
        function() {
            $(this).css('box-shadow', '0 2px 8px rgba(0, 0, 0, 0.05)');
        }
    );
    
    // Add smooth transition when form fields are focused
    $('.pdx-text, .pdx-select').focus(function() {
        $(this).css('border-color', '#3a7bd5');
        $(this).css('box-shadow', '0 0 0 1px rgba(58, 123, 213, 0.25)');
    }).blur(function() {
        $(this).css('border-color', '#ddd');
        $(this).css('box-shadow', 'none');
    });
    
    // Form validation before submit
    $('#post-duplicatex-settings-form').on('submit', function(e) {
        // Check if at least one role is selected
        if ($('input[name^="pdx_allowed_roles"]:checked').length === 0) {
            e.preventDefault();
            alert('Please select at least one user role.');
            return false;
        }
        
        // Check if at least one post type is selected
        if ($('input[name^="pdx_post_types"]:checked').length === 0) {
            e.preventDefault();
            alert('Please select at least one post type.');
            return false;
        }
        
        // Check if at least one link location is selected
        if ($('input[name^="pdx_link_location"]:checked').length === 0) {
            e.preventDefault();
            alert('Please select at least one link location.');
            return false;
        }
        
        return true;
    });
    
    // Show success message after saving settings
    if (window.location.search.indexOf('settings-updated=true') !== -1) {
        $('<div class="pdx-notice-success"><p>Settings saved successfully!</p></div>')
            .insertAfter('.pdx-header')
            .delay(3000)
            .fadeOut();
    }
    
    // Initialize tooltips for help icons
    $('.pdx-help-icon').each(function() {
        $(this).tooltip({
            content: $(this).attr('title'),
            position: { my: 'left+15 center', at: 'right center' },
            tooltipClass: 'pdx-tooltip',
            show: { effect: 'fadeIn', duration: 200 },
            hide: { effect: 'fadeOut', duration: 200 }
        });
    });
});