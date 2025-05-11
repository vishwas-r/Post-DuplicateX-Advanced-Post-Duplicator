jQuery(document).ready(function($) {
    // Highlight active settings on load
    $('.postdx-checkbox-item input[type="checkbox"]:checked').each(function() {
        $(this).closest('.postdx-checkbox-item').addClass('active');
    });
    
    // Toggle active class on checkbox change
    $('.postdx-checkbox-item input[type="checkbox"]').on('change', function() {
        if ($(this).is(':checked')) {
            $(this).closest('.postdx-checkbox-item').addClass('active');
        } else {
            $(this).closest('.postdx-checkbox-item').removeClass('active');
        }
    });
    
    // Add visual feedback when hovering over settings cards
    $('.postdx-admin-card, .postdx-admin-sidebar-widget').hover(
        function() {
            $(this).css('box-shadow', '0 4px 12px rgba(0, 0, 0, 0.1)');
        },
        function() {
            $(this).css('box-shadow', '0 2px 8px rgba(0, 0, 0, 0.05)');
        }
    );
    
    // Add smooth transition when form fields are focused
    $('.postdx-text, .postdx-select').focus(function() {
        $(this).css('border-color', '#3a7bd5');
        $(this).css('box-shadow', '0 0 0 1px rgba(58, 123, 213, 0.25)');
    }).blur(function() {
        $(this).css('border-color', '#ddd');
        $(this).css('box-shadow', 'none');
    });
    
    // Form validation before submit
    $('#post-duplicatex-settings-form').on('submit', function(e) {
        // Check if at least one role is selected
        if ($('input[name^="postdx_allowed_roles"]:checked').length === 0) {
            e.preventDefault();
            alert('Please select at least one user role.');
            return false;
        }
        
        // Check if at least one post type is selected
        if ($('input[name^="postdx_post_types"]:checked').length === 0) {
            e.preventDefault();
            alert('Please select at least one post type.');
            return false;
        }
        
        // Check if at least one link location is selected
        if ($('input[name^="postdx_link_location"]:checked').length === 0) {
            e.preventDefault();
            alert('Please select at least one link location.');
            return false;
        }
        
        return true;
    });
    
    // Show success message after saving settings
    if (window.location.search.indexOf('settings-updated=true') !== -1) {
        $('<div class="postdx-notice-success"><p>Settings saved successfully!</p></div>')
            .insertAfter('.postdx-header')
            .delay(3000)
            .fadeOut();
    }
    
    // Initialize tooltips for help icons
    $('.postdx-help-icon').each(function() {
        $(this).tooltip({
            content: $(this).attr('title'),
            position: { my: 'left+15 center', at: 'right center' },
            tooltipClass: 'postdx-tooltip',
            show: { effect: 'fadeIn', duration: 200 },
            hide: { effect: 'fadeOut', duration: 200 }
        });
    });
});