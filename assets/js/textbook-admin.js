jQuery(document).ready(function($) {
    'use strict';
    
    // Typography preview elements
    const $preview = $('#typography-preview');
    const $form = $('#textbook-form');
    const $status = $('#textbook-status');
    
    // Font size mappings for preview
    const fontSizes = {
        'small': '0.875rem',
        'medium': '1rem',
        'large': '1.25rem',
        'x-large': '1.5rem',
        'xx-large': '2.5rem',
        'huge': '3rem'
    };
    
    // Font family mappings
    const fontFamilies = {
        'montserrat': "'Montserrat', sans-serif",
        'inter': 'Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
        'system-font': '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif'
    };
    
    // Update preview when controls change
    function updatePreview() {
        const formData = new FormData($form[0]);
        
        // Update font families
        const primaryFont = fontFamilies[formData.get('primary_font')] || fontFamilies['montserrat'];
        const bodyFont = fontFamilies[formData.get('body_font')] || fontFamilies['montserrat'];
        
        // Update heading elements
        $preview.find('.preview-h1, .preview-h2, .preview-h3, .preview-h4, .preview-h5').css({
            'font-family': primaryFont,
            'font-weight': formData.get('heading_weight'),
            'line-height': formData.get('heading_line_height')
        });
        
        // Update specific heading sizes
        $preview.find('.preview-h1').css('font-size', fontSizes[formData.get('h1_size')]);
        $preview.find('.preview-h2').css('font-size', fontSizes[formData.get('h2_size')]);
        $preview.find('.preview-h3').css('font-size', fontSizes[formData.get('h3_size')]);
        $preview.find('.preview-h4').css('font-size', fontSizes[formData.get('h4_size')]);
        $preview.find('.preview-h5').css('font-size', fontSizes[formData.get('h5_size')]);
        
        // Update body elements
        $preview.find('.preview-body, .preview-link').css({
            'font-family': bodyFont,
            'font-size': fontSizes[formData.get('body_size')],
            'font-weight': formData.get('body_weight'),
            'line-height': formData.get('body_line_height')
        });
        
        // Update button (uses heading font but body weight)
        $preview.find('.preview-button').css({
            'font-family': primaryFont,
            'font-size': fontSizes[formData.get('body_size')],
            'font-weight': formData.get('heading_weight')
        });
    }
    
    // Bind change events to all form controls
    $form.find('select').on('change', updatePreview);
    
    // Initialize preview
    updatePreview();
    
    // Save typography settings
    $form.on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('action', 'textbook_save_typography');
        formData.append('nonce', textbook_ajax.nonce);
        
        // Show loading state
        $('.textbook-container').addClass('textbook-loading');
        $status.hide();
        
        $.ajax({
            url: textbook_ajax.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('.textbook-container').removeClass('textbook-loading');
                
                if (response.success) {
                    showStatus('Typography settings saved successfully!', 'success');
                    
                    // Update CSS custom properties for immediate effect
                    updateCSSProperties();
                } else {
                    showStatus('Error saving typography settings: ' + (response.data || 'Unknown error'), 'error');
                }
            },
            error: function(xhr, status, error) {
                $('.textbook-container').removeClass('textbook-loading');
                showStatus('AJAX error: ' + error, 'error');
            }
        });
    });
    
    // Reset to defaults
    $('#reset-typography').on('click', function() {
        if (confirm('Reset all typography settings to defaults? This cannot be undone.')) {
            // Set default values
            $('#primary_font').val('montserrat');
            $('#body_font').val('montserrat');
            $('#h1_size').val('xx-large');
            $('#h2_size').val('x-large');
            $('#h3_size').val('large');
            $('#h4_size').val('medium');
            $('#h5_size').val('small');
            $('#body_size').val('medium');
            $('#heading_weight').val('600');
            $('#body_weight').val('400');
            $('#heading_line_height').val('1.2');
            $('#body_line_height').val('1.6');
            
            updatePreview();
            showStatus('Typography reset to defaults. Click "Save Typography Settings" to apply.', 'success');
        }
    });
    
    // Export settings
    $('#export-typography').on('click', function() {
        const formData = new FormData($form[0]);
        const settings = {};
        
        for (let [key, value] of formData.entries()) {
            if (key !== 'textbook_nonce' && key !== '_wp_http_referer') {
                settings[key] = value;
            }
        }
        
        const exportData = {
            version: '1.0.0',
            exported: new Date().toISOString(),
            typography: settings
        };
        
        // Create download
        const blob = new Blob([JSON.stringify(exportData, null, 2)], {type: 'application/json'});
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'villa-community-typography-' + new Date().toISOString().split('T')[0] + '.json';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        
        showStatus('Typography settings exported successfully!', 'success');
    });
    
    // Update CSS custom properties for immediate preview
    function updateCSSProperties() {
        const formData = new FormData($form[0]);
        const root = document.documentElement;
        
        // Update CSS custom properties
        root.style.setProperty('--theme-font-primary', fontFamilies[formData.get('primary_font')]);
        root.style.setProperty('--theme-font-body', fontFamilies[formData.get('body_font')]);
        root.style.setProperty('--theme-h1-size', fontSizes[formData.get('h1_size')]);
        root.style.setProperty('--theme-h2-size', fontSizes[formData.get('h2_size')]);
        root.style.setProperty('--theme-h3-size', fontSizes[formData.get('h3_size')]);
        root.style.setProperty('--theme-h4-size', fontSizes[formData.get('h4_size')]);
        root.style.setProperty('--theme-h5-size', fontSizes[formData.get('h5_size')]);
        root.style.setProperty('--theme-body-size', fontSizes[formData.get('body_size')]);
        root.style.setProperty('--theme-heading-weight', formData.get('heading_weight'));
        root.style.setProperty('--theme-body-weight', formData.get('body_weight'));
        root.style.setProperty('--theme-heading-line-height', formData.get('heading_line_height'));
        root.style.setProperty('--theme-body-line-height', formData.get('body_line_height'));
    }
    
    // Show status message
    function showStatus(message, type) {
        $status.removeClass('notice-success notice-error')
               .addClass('notice-' + type)
               .html('<p>' + message + '</p>')
               .show();
        
        // Auto-hide after 5 seconds
        setTimeout(function() {
            $status.fadeOut();
        }, 5000);
    }
    
    // Import functionality (if needed later)
    function importTypography(data) {
        if (data.typography) {
            Object.keys(data.typography).forEach(function(key) {
                const $field = $('#' + key);
                if ($field.length) {
                    $field.val(data.typography[key]);
                }
            });
            updatePreview();
            showStatus('Typography settings imported successfully!', 'success');
        }
    }
    
    // Keyboard shortcuts
    $(document).on('keydown', function(e) {
        // Ctrl/Cmd + S to save
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            $form.submit();
        }
        
        // Ctrl/Cmd + R to reset (with confirmation)
        if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
            e.preventDefault();
            $('#reset-typography').click();
        }
    });
    
    // Live preview updates with debouncing
    let previewTimeout;
    $form.find('select').on('change', function() {
        clearTimeout(previewTimeout);
        previewTimeout = setTimeout(updatePreview, 100);
    });
});
