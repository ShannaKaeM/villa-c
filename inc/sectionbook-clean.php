<?php
/**
 * SectionBook - Clean Hero Management System
 * Simplified admin interface for hero component variants
 */

// Add admin menu
add_action('admin_menu', 'sectionbook_clean_admin_menu');

function sectionbook_clean_admin_menu() {
    add_submenu_page(
        'design-system',
        'SectionBook',
        'SectionBook',
        'manage_options',
        'sectionbook-clean',
        'sectionbook_clean_admin_page'
    );
}

// Handle form submission
add_action('admin_init', 'sectionbook_clean_handle_save');

function sectionbook_clean_handle_save() {
    if (isset($_POST['sectionbook_clean_nonce']) && wp_verify_nonce($_POST['sectionbook_clean_nonce'], 'sectionbook_clean_save_hero')) {
        $settings = array(
            'variant' => sanitize_text_field($_POST['variant'] ?? 'page'),
            'title_text' => sanitize_text_field($_POST['title_text'] ?? ''),
            'title_size' => sanitize_text_field($_POST['title_size'] ?? '48px'),
            'title_color' => sanitize_hex_color($_POST['title_color'] ?? cb_get_color('base-white')),
            'description_text' => sanitize_textarea_field($_POST['description_text'] ?? ''),
            'description_size' => sanitize_text_field($_POST['description_size'] ?? '18px'),
            'description_color' => sanitize_hex_color($_POST['description_color'] ?? cb_get_color('base-white')),
            'background_image' => esc_url_raw($_POST['background_image'] ?? ''),
            'background_color' => sanitize_hex_color($_POST['background_color'] ?? cb_get_color('primary-dark')),
            'overlay_color' => sanitize_hex_color($_POST['overlay_color'] ?? cb_get_color('base-black')),
            'overlay_opacity' => floatval($_POST['overlay_opacity'] ?? 0.4),
            'height' => sanitize_text_field($_POST['height'] ?? '70vh'),
            'content_max_width' => sanitize_text_field($_POST['content_max_width'] ?? '600px'),
            'border_radius' => sanitize_text_field($_POST['border_radius'] ?? '8px')
        );
        
        update_option('sectionbook_clean_hero_settings', $settings);
        add_action('admin_notices', function() {
            echo '<div class="notice notice-success is-dismissible"><p>Hero settings saved successfully!</p></div>';
        });
    }
}

// Get hero settings with defaults
function sectionbook_clean_get_hero_settings() {
    $defaults = array(
        'variant' => 'page',
        'title_text' => 'Welcome to Villa Community',
        'title_size' => '48px',
        'title_color' => cb_get_color('base-white'),
        'description_text' => 'Discover our collection of stunning villas in the most beautiful destinations. Perfect for your next getaway.',
        'description_size' => '18px',
        'description_color' => cb_get_color('base-white'),
        'background_image' => '',
        'background_color' => cb_get_color('primary-dark'),
        'overlay_color' => cb_get_color('base-black'),
        'overlay_opacity' => 0.4,
        'height' => '70vh',
        'content_max_width' => '600px',
        'border_radius' => '8px'
    );
    
    $saved_settings = get_option('sectionbook_clean_hero_settings', array());
    return wp_parse_args($saved_settings, $defaults);
}

// Admin page
function sectionbook_clean_admin_page() {
    wp_enqueue_media();
    $settings = sectionbook_clean_get_hero_settings();
    ?>
    <div class="wrap">
        <h1>SectionBook - Hero Management</h1>
        
        <div style="display: flex; gap: 30px; margin-top: 20px;">
            
            <!-- Settings Form -->
            <div style="flex: 1; max-width: 500px;">
                <form method="post" action="">
                    <?php wp_nonce_field('sectionbook_clean_save_hero', 'sectionbook_clean_nonce'); ?>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row">Hero Variant</th>
                            <td>
                                <select name="variant" id="variant">
                                    <option value="page" <?php selected($settings['variant'], 'page'); ?>>Page Hero</option>
                                    <option value="home" <?php selected($settings['variant'], 'home'); ?>>Home Hero</option>
                                </select>
                                <p class="description">Choose the hero variant (affects default sizing and styling)</p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">Title Text</th>
                            <td>
                                <input type="text" name="title_text" value="<?php echo esc_attr($settings['title_text']); ?>" class="regular-text" />
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">Title Size</th>
                            <td>
                                <select name="title_size">
                                    <option value="32px" <?php selected($settings['title_size'], '32px'); ?>>32px</option>
                                    <option value="36px" <?php selected($settings['title_size'], '36px'); ?>>36px</option>
                                    <option value="42px" <?php selected($settings['title_size'], '42px'); ?>>42px</option>
                                    <option value="48px" <?php selected($settings['title_size'], '48px'); ?>>48px</option>
                                    <option value="56px" <?php selected($settings['title_size'], '56px'); ?>>56px</option>
                                    <option value="64px" <?php selected($settings['title_size'], '64px'); ?>>64px</option>
                                    <option value="72px" <?php selected($settings['title_size'], '72px'); ?>>72px</option>
                                </select>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">Title Color</th>
                            <td>
                                <input type="color" name="title_color" value="<?php echo esc_attr($settings['title_color']); ?>" />
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">Description Text</th>
                            <td>
                                <textarea name="description_text" rows="3" class="large-text"><?php echo esc_textarea($settings['description_text']); ?></textarea>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">Description Size</th>
                            <td>
                                <select name="description_size">
                                    <option value="14px" <?php selected($settings['description_size'], '14px'); ?>>14px</option>
                                    <option value="16px" <?php selected($settings['description_size'], '16px'); ?>>16px</option>
                                    <option value="18px" <?php selected($settings['description_size'], '18px'); ?>>18px</option>
                                    <option value="20px" <?php selected($settings['description_size'], '20px'); ?>>20px</option>
                                    <option value="22px" <?php selected($settings['description_size'], '22px'); ?>>22px</option>
                                    <option value="24px" <?php selected($settings['description_size'], '24px'); ?>>24px</option>
                                </select>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">Description Color</th>
                            <td>
                                <input type="color" name="description_color" value="<?php echo esc_attr($settings['description_color']); ?>" />
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">Background Image</th>
                            <td>
                                <input type="url" name="background_image" id="background_image" value="<?php echo esc_attr($settings['background_image']); ?>" class="regular-text" />
                                <button type="button" class="button" onclick="selectMedia('background_image')">Select Image</button>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">Background Color</th>
                            <td>
                                <input type="color" name="background_color" value="<?php echo esc_attr($settings['background_color']); ?>" />
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">Overlay Color</th>
                            <td>
                                <input type="color" name="overlay_color" value="<?php echo esc_attr($settings['overlay_color']); ?>" />
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">Overlay Opacity</th>
                            <td>
                                <input type="range" name="overlay_opacity" min="0" max="1" step="0.1" value="<?php echo esc_attr($settings['overlay_opacity']); ?>" />
                                <span id="opacity-value"><?php echo esc_html($settings['overlay_opacity']); ?></span>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">Height</th>
                            <td>
                                <select name="height">
                                    <option value="50vh" <?php selected($settings['height'], '50vh'); ?>>50vh</option>
                                    <option value="60vh" <?php selected($settings['height'], '60vh'); ?>>60vh</option>
                                    <option value="70vh" <?php selected($settings['height'], '70vh'); ?>>70vh</option>
                                    <option value="80vh" <?php selected($settings['height'], '80vh'); ?>>80vh</option>
                                    <option value="90vh" <?php selected($settings['height'], '90vh'); ?>>90vh</option>
                                    <option value="100vh" <?php selected($settings['height'], '100vh'); ?>>100vh</option>
                                </select>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">Content Max Width</th>
                            <td>
                                <select name="content_max_width">
                                    <option value="400px" <?php selected($settings['content_max_width'], '400px'); ?>>400px</option>
                                    <option value="500px" <?php selected($settings['content_max_width'], '500px'); ?>>500px</option>
                                    <option value="600px" <?php selected($settings['content_max_width'], '600px'); ?>>600px</option>
                                    <option value="700px" <?php selected($settings['content_max_width'], '700px'); ?>>700px</option>
                                    <option value="800px" <?php selected($settings['content_max_width'], '800px'); ?>>800px</option>
                                </select>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">Border Radius</th>
                            <td>
                                <select name="border_radius">
                                    <option value="0" <?php selected($settings['border_radius'], '0'); ?>>None</option>
                                    <option value="8px" <?php selected($settings['border_radius'], '8px'); ?>>8px</option>
                                    <option value="12px" <?php selected($settings['border_radius'], '12px'); ?>>12px</option>
                                    <option value="16px" <?php selected($settings['border_radius'], '16px'); ?>>16px</option>
                                    <option value="20px" <?php selected($settings['border_radius'], '20px'); ?>>20px</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                    
                    <?php submit_button('Save Hero Settings'); ?>
                </form>
            </div>
            
            <!-- Live Preview -->
            <div style="flex: 1;">
                <h3>Live Preview</h3>
                <div style="
                    width: 100%;
                    height: 300px;
                    background-color: <?php echo esc_attr($settings['background_color']); ?>;
                    <?php if ($settings['background_image']): ?>
                    background-image: url('<?php echo esc_url($settings['background_image']); ?>');
                    background-size: cover;
                    background-position: center;
                    <?php endif; ?>
                    position: relative;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border-radius: <?php echo esc_attr($settings['border_radius']); ?>;
                    overflow: hidden;
                ">
                    <?php if ($settings['overlay_color']): ?>
                    <div style="
                        position: absolute;
                        top: 0; left: 0; right: 0; bottom: 0;
                        background-color: <?php echo esc_attr($settings['overlay_color']); ?>;
                        opacity: <?php echo esc_attr($settings['overlay_opacity']); ?>;
                    "></div>
                    <?php endif; ?>
                    
                    <div style="
                        position: relative;
                        z-index: 1;
                        text-align: center;
                        max-width: <?php echo esc_attr($settings['content_max_width']); ?>;
                        padding: 20px;
                    ">
                        <h1 style="
                            font-size: <?php echo esc_attr($settings['title_size']); ?>;
                            color: <?php echo esc_attr($settings['title_color']); ?>;
                            margin: 0 0 10px 0;
                            font-weight: 700;
                            line-height: 1.2;
                        "><?php echo esc_html($settings['title_text']); ?></h1>
                        
                        <p style="
                            font-size: <?php echo esc_attr($settings['description_size']); ?>;
                            color: <?php echo esc_attr($settings['description_color']); ?>;
                            margin: 0;
                            line-height: 1.5;
                        "><?php echo esc_html($settings['description_text']); ?></p>
                    </div>
                </div>
                
                <p><strong>Variant:</strong> <?php echo esc_html(ucfirst($settings['variant'])); ?> Hero</p>
            </div>
        </div>
    </div>
    
    <script>
    function selectMedia(inputId) {
        var mediaUploader = wp.media({
            title: 'Select Background Image',
            button: { text: 'Use this image' },
            multiple: false
        });
        
        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            document.getElementById(inputId).value = attachment.url;
        });
        
        mediaUploader.open();
    }
    
    // Update opacity display
    document.querySelector('input[name="overlay_opacity"]').addEventListener('input', function() {
        document.getElementById('opacity-value').textContent = this.value;
    });
    </script>
    <?php
}

// Render hero function for frontend
function sectionbook_clean_render_page_hero($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $settings = sectionbook_clean_get_hero_settings();
    
    // Check for page-specific overrides
    $page_title = get_post_meta($post_id, '_page_hero_title', true);
    $page_description = get_post_meta($post_id, '_page_hero_description', true);
    
    // Prepare data for Twig component
    $hero_data = array(
        'variant' => $settings['variant'],
        'title_text' => $page_title ?: $settings['title_text'],
        'title_size' => $settings['title_size'],
        'title_color' => $settings['title_color'],
        'description_text' => $page_description ?: $settings['description_text'],
        'description_size' => $settings['description_size'],
        'description_color' => $settings['description_color'],
        'background_image' => $settings['background_image'],
        'background_color' => $settings['background_color'],
        'overlay_color' => $settings['overlay_color'],
        'overlay_opacity' => $settings['overlay_opacity'],
        'height' => $settings['height'],
        'content_max_width' => $settings['content_max_width'],
        'border_radius' => $settings['border_radius']
    );
    
    // Render using Timber/Twig
    if (class_exists('Timber')) {
        echo Timber::compile('components/sections/hero.twig', $hero_data);
    } else {
        // Fallback if Timber is not available
        echo '<div class="hero-error">Timber/Twig not available for hero rendering</div>';
    }
}
