jQuery(document).ready(function($) {
    'use strict';
    
    // Update preview in real-time
    function updatePreview() {
        const settings = {
            card_padding_x: $('#card_padding_x').val(),
            card_padding_y: $('#card_padding_y').val(),
            card_radius: $('#card_radius').val(),
            card_shadow: $('#card_shadow').val(),
            card_spacing: $('#card_spacing').val(),
            button_full_width: $('#button_full_width').is(':checked'),
            button_padding_x: $('#button_padding_x').val(),
            button_padding_y: $('#button_padding_y').val(),
            button_radius: $('#button_radius').val(),
            tag_style1_radius: $('#tag_style1_radius').val(),
            tag_style1_padding_x: $('#tag_style1_padding_x').val(),
            tag_style1_padding_y: $('#tag_style1_padding_y').val(),
            tag_style2_radius: $('#tag_style2_radius').val(),
            tag_style2_padding_x: $('#tag_style2_padding_x').val(),
            tag_style2_padding_y: $('#tag_style2_padding_y').val(),
            tag_spacing: $('#tag_spacing').val()
        };
        
        // Update card styles
        const $card = $('#preview-card');
        const $cardContent = $('#preview-card-content');
        const $button = $('#preview-button');
        const $tagStyle1 = $('#preview-tag-style1');
        const $tagStyle2 = $('.uibook-preview-tag-style2');
        const $tags = $('#preview-tags');
        
        // Card styles
        $card.css({
            'border-radius': settings.card_radius + 'px',
            'box-shadow': getShadowValue(settings.card_shadow)
        });
        
        $cardContent.css({
            'padding': settings.card_padding_y + 'rem ' + settings.card_padding_x + 'rem',
            'gap': settings.card_spacing + 'rem'
        });
        
        // Button styles
        $button.css({
            'width': settings.button_full_width ? '100%' : 'auto',
            'padding': settings.button_padding_y + 'rem ' + settings.button_padding_x + 'rem',
            'border-radius': settings.button_radius + 'px'
        });
        
        // Tag Style 1 (amenity tags)
        $tagStyle1.css({
            'border-radius': settings.tag_style1_radius + 'px',
            'padding': settings.tag_style1_padding_y + 'rem ' + settings.tag_style1_padding_x + 'rem'
        });
        
        // Tag Style 2 (spec tags)
        $tagStyle2.css({
            'border-radius': settings.tag_style2_radius + 'px',
            'padding': settings.tag_style2_padding_y + 'rem ' + settings.tag_style2_padding_x + 'rem'
        });
        
        // Tag spacing
        $tags.css('gap', settings.tag_spacing + 'rem');
    }
    
    // Get shadow CSS value
    function getShadowValue(intensity) {
        const shadows = {
            'none': 'none',
            'small': '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)',
            'medium': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
            'large': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
            'xlarge': '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)'
        };
        return shadows[intensity] || shadows.medium;
    }
    
    // Update slider values in real-time
    function updateSliderValues() {
        $('input[type="range"]').each(function() {
            const $slider = $(this);
            const $valueDisplay = $slider.siblings('.uibook-value');
            const value = $slider.val();
            const unit = $slider.attr('name').includes('radius') ? 'px' : 'rem';
            
            $valueDisplay.text(value + unit);
        });
    }
    
    // Event listeners for real-time updates
    $('input[type="range"], select, input[type="checkbox"]').on('input change', function() {
        updateSliderValues();
        updatePreview();
    });
    
    // Form submission
    $('#uibook-form').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData();
        formData.append('action', 'uibook_save_settings');
        formData.append('nonce', uibook_ajax.nonce);
        
        // Collect all form data
        $(this).find('input, select').each(function() {
            const $field = $(this);
            const name = $field.attr('name');
            let value = $field.val();
            
            if ($field.attr('type') === 'checkbox') {
                value = $field.is(':checked') ? 'true' : 'false';
            }
            
            if (name && name !== 'uibook_nonce') {
                formData.append(name, value);
            }
        });
        
        // Show loading state
        const $submitBtn = $(this).find('button[type="submit"]');
        const originalText = $submitBtn.html();
        $submitBtn.html('<span class="dashicons dashicons-update-alt"></span> Saving...').prop('disabled', true);
        
        // AJAX request
        $.ajax({
            url: uibook_ajax.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showMessage('UiBook settings saved successfully!', 'success');
                    
                    // Update CSS variables in the document
                    if (response.data.css_variables) {
                        updateDocumentCSSVariables(response.data.css_variables);
                    }
                } else {
                    showMessage('Error saving settings: ' + (response.data || 'Unknown error'), 'error');
                }
            },
            error: function(xhr, status, error) {
                showMessage('AJAX Error: ' + error, 'error');
            },
            complete: function() {
                $submitBtn.html(originalText).prop('disabled', false);
            }
        });
    });
    
    // Reset to defaults
    $('#uibook-reset').on('click', function() {
        if (confirm('Are you sure you want to reset all UiBook settings to defaults? This cannot be undone.')) {
            const defaults = {
                card_padding_x: '2',
                card_padding_y: '1.5',
                card_radius: '12',
                card_shadow: 'medium',
                card_spacing: '0.75',
                button_full_width: true,
                button_padding_x: '1',
                button_padding_y: '0.5',
                button_radius: '6',
                button_style: 'primary',
                tag_style1_radius: '20',
                tag_style1_padding_x: '0.75',
                tag_style1_padding_y: '0.25',
                tag_style2_radius: '6',
                tag_style2_padding_x: '0.5',
                tag_style2_padding_y: '0.25',
                tag_spacing: '0.5',
                grid_gap: '1.5',
                content_spacing: '1',
                section_padding: '4'
            };
            
            // Apply defaults to form
            Object.keys(defaults).forEach(function(key) {
                const $field = $('[name="' + key + '"]');
                if ($field.attr('type') === 'checkbox') {
                    $field.prop('checked', defaults[key]);
                } else {
                    $field.val(defaults[key]);
                }
            });
            
            updateSliderValues();
            updatePreview();
            showMessage('Settings reset to defaults. Click Save to apply changes.', 'success');
        }
    });
    
    // Update CSS variables in document
    function updateDocumentCSSVariables(variables) {
        const style = document.documentElement.style;
        Object.keys(variables).forEach(function(property) {
            style.setProperty(property, variables[property]);
        });
    }
    
    // Show message
    function showMessage(message, type) {
        const $messageDiv = $('#uibook-message');
        $messageDiv
            .removeClass('notice-success notice-error')
            .addClass('notice-' + type)
            .html('<p>' + message + '</p>')
            .show();
        
        setTimeout(function() {
            $messageDiv.fadeOut();
        }, 4000);
    }
    
    // Keyboard shortcuts
    $(document).on('keydown', function(e) {
        // Ctrl+S or Cmd+S to save
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            $('#uibook-form').submit();
        }
        
        // Ctrl+R or Cmd+R to reset (with confirmation)
        if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
            e.preventDefault();
            $('#uibook-reset').click();
        }
    });
    
    // Initialize
    updateSliderValues();
    updatePreview();
    
    // Show keyboard shortcuts hint
    setTimeout(function() {
        showMessage('💡 Tip: Use Ctrl+S to save, Ctrl+R to reset', 'success');
    }, 1000);
});
