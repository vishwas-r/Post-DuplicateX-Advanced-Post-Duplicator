(function(wp) {
    const { registerPlugin } = wp.plugins;
    const { PluginPostStatusInfo } = wp.editPost;
    const { Button } = wp.components;
    const { select } = wp.data;
    const { createElement } = wp.element;
    
    function DuplicatePostButton() {
        const postType = select('core/editor').getCurrentPostType();
        
        // Check if post type is enabled for duplication
        if (!pdxBlockEditor.postTypes.includes(postType)) {
            return null;
        }
        
        const postId = select('core/editor').getCurrentPostId();
        
        const handleClick = () => {
            // First, save any unsaved content
            const isSaving = select('core/editor').isSavingPost();
            
            if (isSaving) {
                alert(wp.i18n.__('Please wait until saving is complete before duplicating.', 'post-duplicatex'));
                return;
            }
            
            // Create the duplicate URL with proper nonce
            const duplicateUrl = `${pdxBlockEditor.ajaxURL}?action=pdx_duplicate_post&post=${postId}&_wpnonce=${pdxBlockEditor.nonce}`;
            
            // Redirect to the duplicate URL
            window.location.href = duplicateUrl;
        };
        
        return createElement(
            PluginPostStatusInfo,
            {},
            createElement(
                Button,
                {
                    isSecondary: true,
                    onClick: handleClick,
                    className: 'pdx-duplicate-button'
                },
                pdxBlockEditor.linkTitle
            )
        );
    }
    
    registerPlugin('post-duplicatex', {
        render: DuplicatePostButton,
        icon: 'admin-page'
    });
})(window.wp);