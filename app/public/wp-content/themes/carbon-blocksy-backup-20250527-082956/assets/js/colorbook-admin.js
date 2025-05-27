/**
 * ColorBook Admin JavaScript
 * Handles color editing, OKLCH conversion, and admin interactions
 */

(function($) {
    'use strict';

    let currentEditingColor = null;
    let allColors = [];

    // Initialize when document is ready
    $(document).ready(function() {
        initColorbook();
    });

    function initColorbook() {
        // Collect all colors from the page
        collectColors();
        
        // Bind event handlers
        bindEventHandlers();
        
        // Show success message if colors were just saved
        if (window.location.search.includes('colors-saved')) {
            showMessage('Colors saved successfully!', 'success');
        }
    }

    function collectColors() {
        allColors = [];
        $('.color-card').each(function() {
            const colorData = $(this).data('color');
            if (colorData) {
                allColors.push(colorData);
            }
        });
    }

    function bindEventHandlers() {
        // Edit color button
        $(document).on('click', '.edit-color', function(e) {
            e.preventDefault();
            const colorCard = $(this).closest('.color-card');
            const colorData = colorCard.data('color');
            openColorEditor(colorData, colorCard);
        });

        // Color swatch click (alternative way to edit)
        $(document).on('click', '.color-swatch', function() {
            const colorCard = $(this).closest('.color-card');
            const colorData = colorCard.data('color');
            openColorEditor(colorData, colorCard);
        });

        // Save colors button
        $('#save-colors').on('click', function() {
            saveColors();
        });

        // Export colors button
        $('#export-colors').on('click', function() {
            exportColors();
        });

        // Import colors button
        $('#import-colors').on('click', function() {
            $('#import-file').click();
        });

        // Import file change
        $('#import-file').on('change', function() {
            importColors(this.files[0]);
        });

        // Modal controls
        $('#apply-color').on('click', applyColorChanges);
        $('#cancel-edit').on('click', closeColorEditor);
        
        // Close modal on background click
        $(document).on('click', '.color-editor-modal', function(e) {
            if (e.target === this) {
                closeColorEditor();
            }
        });

        // OKLCH sliders
        $('#l-slider, #c-slider, #h-slider').on('input', updateColorFromSliders);

        // Escape key to close modal
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && $('#color-editor-modal').is(':visible')) {
                closeColorEditor();
            }
        });
    }

    function openColorEditor(colorData, colorCard) {
        currentEditingColor = {
            data: colorData,
            card: colorCard
        };

        // Set modal title
        $('#editing-color-name').text(colorData.name);

        // Set initial values
        const oklch = colorData.oklch;
        $('#l-slider').val(oklch[0]);
        $('#c-slider').val(oklch[1]);
        $('#h-slider').val(oklch[2]);

        // Update displays
        updateColorPreview();
        updateSliderValues();

        // Show modal
        $('#color-editor-modal').show();
    }

    function closeColorEditor() {
        $('#color-editor-modal').hide();
        currentEditingColor = null;
    }

    function updateColorFromSliders() {
        if (!currentEditingColor) return;

        const l = parseFloat($('#l-slider').val());
        const c = parseFloat($('#c-slider').val());
        const h = parseFloat($('#h-slider').val());

        // Update the current editing color data
        currentEditingColor.data.oklch = [l, c, h];
        currentEditingColor.data.hex = oklchToHex(l, c, h);

        updateColorPreview();
        updateSliderValues();
    }

    function updateColorPreview() {
        if (!currentEditingColor) return;

        const hex = currentEditingColor.data.hex;
        const oklch = currentEditingColor.data.oklch;

        $('#color-preview').css('background-color', hex);
        $('#hex-value').text(hex);
        $('#oklch-value').text(`oklch(${oklch[0]}% ${oklch[1].toFixed(3)} ${oklch[2]})`);
    }

    function updateSliderValues() {
        const l = $('#l-slider').val();
        const c = $('#c-slider').val();
        const h = $('#h-slider').val();

        $('#l-value').text(Math.round(l));
        $('#c-value').text(parseFloat(c).toFixed(3));
        $('#h-value').text(Math.round(h));
    }

    function applyColorChanges() {
        if (!currentEditingColor) return;

        const colorCard = currentEditingColor.card;
        const colorData = currentEditingColor.data;

        // Update the color card
        colorCard.find('.color-swatch').css('background-color', colorData.hex);
        colorCard.find('.color-info code').text(colorData.hex);
        colorCard.data('color', colorData);

        // Update the colors array
        const index = allColors.findIndex(c => c.slug === colorData.slug);
        if (index !== -1) {
            allColors[index] = colorData;
        }

        closeColorEditor();
        showMessage('Color updated! Remember to save your changes.', 'success');
    }

    function saveColors() {
        const button = $('#save-colors');
        const originalText = button.text();
        
        button.text('Saving...').prop('disabled', true);

        // Collect current colors from the page
        collectColors();

        const data = {
            action: 'colorbook_save_colors',
            nonce: colorbook.nonce,
            colors: JSON.stringify(allColors),
            sync_blocksy: $('#sync-blocksy').is(':checked') ? '1' : ''
        };

        $.post(colorbook.ajaxurl, data)
            .done(function(response) {
                if (response.success) {
                    showMessage('Colors saved successfully!', 'success');
                } else {
                    showMessage('Error saving colors: ' + (response.data || 'Unknown error'), 'error');
                }
            })
            .fail(function() {
                showMessage('Error saving colors. Please try again.', 'error');
            })
            .always(function() {
                button.text(originalText).prop('disabled', false);
            });
    }

    function exportColors() {
        const data = {
            action: 'colorbook_export_colors',
            nonce: colorbook.nonce
        };

        $.post(colorbook.ajaxurl, data)
            .done(function(response) {
                if (response.success) {
                    const exportData = response.data;
                    const blob = new Blob([JSON.stringify(exportData, null, 2)], {
                        type: 'application/json'
                    });
                    
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'colorbook-export.json';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    URL.revokeObjectURL(url);
                    
                    showMessage('Colors exported successfully!', 'success');
                } else {
                    showMessage('Error exporting colors: ' + (response.data || 'Unknown error'), 'error');
                }
            })
            .fail(function() {
                showMessage('Error exporting colors. Please try again.', 'error');
            });
    }

    function importColors(file) {
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            try {
                const importData = JSON.parse(e.target.result);
                
                if (importData.mods && importData.mods.colorPalette) {
                    // Process Blocksy export format
                    processBlocksyImport(importData.mods.colorPalette);
                } else if (Array.isArray(importData)) {
                    // Process direct color array
                    processDirectImport(importData);
                } else {
                    throw new Error('Invalid file format');
                }
                
                showMessage('Colors imported successfully! Remember to save your changes.', 'success');
            } catch (error) {
                showMessage('Error importing colors: Invalid file format', 'error');
            }
        };
        
        reader.readAsText(file);
    }

    function processBlocksyImport(colorPalette) {
        // Map Blocksy colors back to our color system
        const colorMapping = [
            'primary-light', 'primary', 'primary-dark',
            'secondary-light', 'secondary', 'secondary-dark',
            'neutral-light', 'neutral', 'neutral-dark',
            'base-white', 'base-lightest', 'base-light', 'base', 'base-dark', 'base-darkest', 'base-black'
        ];

        colorMapping.forEach((slug, index) => {
            const paletteKey = 'color' + (index + 1);
            if (colorPalette[paletteKey] && colorPalette[paletteKey].color) {
                updateColorBySlug(slug, colorPalette[paletteKey].color);
            }
        });
    }

    function processDirectImport(colors) {
        colors.forEach(color => {
            if (color.slug && color.hex) {
                updateColorBySlug(color.slug, color.hex);
            }
        });
    }

    function updateColorBySlug(slug, hex) {
        const colorCard = $(`.color-card`).filter(function() {
            return $(this).data('color').slug === slug;
        });

        if (colorCard.length) {
            const colorData = colorCard.data('color');
            colorData.hex = hex;
            colorData.oklch = hexToOklch(hex); // You'll need to implement this

            colorCard.find('.color-swatch').css('background-color', hex);
            colorCard.find('.color-info code').text(hex);
            colorCard.data('color', colorData);

            // Update allColors array
            const index = allColors.findIndex(c => c.slug === slug);
            if (index !== -1) {
                allColors[index] = colorData;
            }
        }
    }

    function showMessage(message, type) {
        // Remove existing messages
        $('.colorbook-message').remove();

        const messageDiv = $(`<div class="colorbook-message ${type}">${message}</div>`);
        $('.colorbook-container').prepend(messageDiv);

        // Auto-hide after 5 seconds
        setTimeout(() => {
            messageDiv.fadeOut(() => messageDiv.remove());
        }, 5000);
    }

    // Color conversion functions
    function oklchToHex(l, c, h) {
        // Simplified conversion - in production, use a proper color library
        // This is a basic approximation
        const hRad = h * Math.PI / 180;
        const a = c * Math.cos(hRad);
        const b = c * Math.sin(hRad);
        
        // Convert to RGB (simplified)
        const r = Math.round(Math.max(0, Math.min(255, l * 2.55 + a * 100)));
        const g = Math.round(Math.max(0, Math.min(255, l * 2.55 - a * 50 + b * 50)));
        const blue = Math.round(Math.max(0, Math.min(255, l * 2.55 - b * 100)));
        
        return '#' + [r, g, blue].map(x => x.toString(16).padStart(2, '0')).join('');
    }

    function hexToOklch(hex) {
        // Simplified conversion - in production, use a proper color library
        // This is a basic approximation
        const r = parseInt(hex.slice(1, 3), 16);
        const g = parseInt(hex.slice(3, 5), 16);
        const blue = parseInt(hex.slice(5, 7), 16);
        
        const l = (r + g + blue) / 3 / 255 * 100;
        const c = Math.sqrt(Math.pow(r - g, 2) + Math.pow(g - blue, 2)) / 255 * 0.4;
        const h = Math.atan2(blue - g, r - g) * 180 / Math.PI;
        
        return [Math.round(l), Math.round(c * 1000) / 1000, Math.round(h < 0 ? h + 360 : h)];
    }

})(jQuery);
