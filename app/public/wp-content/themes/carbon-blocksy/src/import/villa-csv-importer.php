<?php
/**
 * Villa CSV Importer
 * 
 * Import villa properties from CSV file into WordPress
 * Usage: Run from WordPress admin or via WP-CLI
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class VillaCsvImporter {
    
    private $csv_file;
    private $imported_count = 0;
    private $errors = [];
    
    public function __construct($csv_file = null) {
        $this->csv_file = $csv_file ?: get_stylesheet_directory() . '/miDocs/SITE DATA/Properties-Updated.csv';
    }
    
    /**
     * Main import function
     */
    public function import() {
        if (!file_exists($this->csv_file)) {
            $this->errors[] = "CSV file not found: " . $this->csv_file;
            return false;
        }
        
        $csv_data = $this->read_csv();
        if (empty($csv_data)) {
            $this->errors[] = "No data found in CSV file";
            return false;
        }
        
        foreach ($csv_data as $row_index => $row) {
            try {
                $this->import_villa($row, $row_index + 2); // +2 for header and 0-indexing
            } catch (Exception $e) {
                $this->errors[] = "Row " . ($row_index + 2) . ": " . $e->getMessage();
            }
        }
        
        return true;
    }
    
    /**
     * Read and parse CSV file
     */
    private function read_csv() {
        $data = [];
        $handle = fopen($this->csv_file, 'r');
        
        if ($handle === false) {
            return $data;
        }
        
        // Read header row
        $headers = fgetcsv($handle);
        if (!$headers) {
            fclose($handle);
            return $data;
        }
        
        // Read data rows
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) === count($headers)) {
                $data[] = array_combine($headers, $row);
            }
        }
        
        fclose($handle);
        return $data;
    }
    
    /**
     * Import a single villa from CSV row
     */
    private function import_villa($row, $row_number) {
        // Skip empty rows
        if (empty($row['title']) || empty($row['villa_name'])) {
            return;
        }
        
        // Check if villa already exists
        $existing = get_posts([
            'post_type' => 'villas',
            'meta_query' => [
                [
                    'key' => '_villa_name',
                    'value' => $row['villa_name'],
                    'compare' => '='
                ]
            ],
            'post_status' => 'any',
            'numberposts' => 1
        ]);
        
        if (!empty($existing)) {
            throw new Exception("Villa '{$row['villa_name']}' already exists");
        }
        
        // Create the post
        $post_data = [
            'post_title' => $row['title'],
            'post_content' => $row['description'],
            'post_type' => 'villas',
            'post_status' => $row['status'] === 'active' ? 'publish' : 'draft',
            'meta_input' => $this->prepare_meta_fields($row)
        ];
        
        $post_id = wp_insert_post($post_data);
        
        if (is_wp_error($post_id)) {
            throw new Exception("Failed to create post: " . $post_id->get_error_message());
        }
        
        // Set taxonomies
        $this->set_villa_taxonomies($post_id, $row);
        
        // Set featured image
        $this->set_featured_image($post_id, $row['featured_image']);
        
        $this->imported_count++;
        
        return $post_id;
    }
    
    /**
     * Prepare meta fields for villa
     */
    private function prepare_meta_fields($row) {
        return [
            '_villa_name' => $row['villa_name'],
            '_unit_number' => $row['unit_number'],
            '_address' => $row['address'],
            '_city' => $row['city'],
            '_state' => $row['state'],
            '_zip_code' => $row['zip_code'],
            '_latitude' => floatval($row['latitude']),
            '_longitude' => floatval($row['longitude']),
            '_bedrooms' => intval($row['bedrooms']),
            '_bathrooms' => floatval($row['bathrooms']),
            '_max_guests' => intval($row['max_guests']),
            '_nightly_rate' => floatval($row['nightly_rate']),
            '_booking_url' => $row['booking_url'],
            '_ical_url' => $row['ical_url'],
            '_has_direct_booking' => $row['has_direct_booking'] === '1',
            '_is_featured' => $row['is_featured'] === '1'
        ];
    }
    
    /**
     * Set villa taxonomies
     */
    private function set_villa_taxonomies($post_id, $row) {
        // Set property types
        if (!empty($row['property_types'])) {
            $property_types = array_map('trim', explode(',', $row['property_types']));
            $this->set_taxonomy_terms($post_id, 'property-types', $property_types);
        }
        
        // Set amenities
        if (!empty($row['amenities'])) {
            $amenities = array_map('trim', explode(',', $row['amenities']));
            $this->set_taxonomy_terms($post_id, 'amenities', $amenities);
        }
    }
    
    /**
     * Set taxonomy terms, creating them if they don't exist
     */
    private function set_taxonomy_terms($post_id, $taxonomy, $terms) {
        $term_ids = [];
        
        foreach ($terms as $term_name) {
            if (empty($term_name)) continue;
            
            $term = get_term_by('name', $term_name, $taxonomy);
            
            if (!$term) {
                // Create the term if it doesn't exist
                $result = wp_insert_term($term_name, $taxonomy);
                if (!is_wp_error($result)) {
                    $term_ids[] = $result['term_id'];
                }
            } else {
                $term_ids[] = $term->term_id;
            }
        }
        
        if (!empty($term_ids)) {
            wp_set_post_terms($post_id, $term_ids, $taxonomy);
        }
    }
    
    /**
     * Set featured image from filename
     */
    private function set_featured_image($post_id, $image_filename) {
        if (empty($image_filename)) {
            return;
        }
        
        $image_path = get_stylesheet_directory() . '/miDocs/SITE DATA/Images/Properties-Featured/' . $image_filename;
        
        if (!file_exists($image_path)) {
            return;
        }
        
        // Check if attachment already exists
        $existing_attachment = get_posts([
            'post_type' => 'attachment',
            'meta_query' => [
                [
                    'key' => '_wp_attached_file',
                    'value' => $image_filename,
                    'compare' => 'LIKE'
                ]
            ],
            'numberposts' => 1
        ]);
        
        if (!empty($existing_attachment)) {
            set_post_thumbnail($post_id, $existing_attachment[0]->ID);
            return;
        }
        
        // Upload the image
        $upload_dir = wp_upload_dir();
        $new_filename = wp_unique_filename($upload_dir['path'], $image_filename);
        $new_file_path = $upload_dir['path'] . '/' . $new_filename;
        
        if (copy($image_path, $new_file_path)) {
            $file_type = wp_check_filetype($new_filename, null);
            
            $attachment = [
                'post_mime_type' => $file_type['type'],
                'post_title' => sanitize_file_name($new_filename),
                'post_content' => '',
                'post_status' => 'inherit'
            ];
            
            $attachment_id = wp_insert_attachment($attachment, $new_file_path, $post_id);
            
            if (!is_wp_error($attachment_id)) {
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                $attachment_data = wp_generate_attachment_metadata($attachment_id, $new_file_path);
                wp_update_attachment_metadata($attachment_id, $attachment_data);
                set_post_thumbnail($post_id, $attachment_id);
            }
        }
    }
    
    /**
     * Get import results
     */
    public function get_results() {
        return [
            'imported_count' => $this->imported_count,
            'errors' => $this->errors
        ];
    }
}

// Admin page for running the importer
if (is_admin()) {
    add_action('admin_menu', function() {
        add_management_page(
            'Import Villas',
            'Import Villas CSV',
            'manage_options',
            'villa-csv-import',
            'villa_csv_import_page'
        );
    });
}

function villa_csv_import_page() {
    if (isset($_POST['import_csv']) && wp_verify_nonce($_POST['_wpnonce'], 'villa_csv_import')) {
        $importer = new VillaCsvImporter();
        $importer->import();
        $results = $importer->get_results();
        
        echo '<div class="notice notice-success"><p>';
        echo 'Import completed! Imported ' . $results['imported_count'] . ' villas.';
        echo '</p></div>';
        
        if (!empty($results['errors'])) {
            echo '<div class="notice notice-error"><p>Errors encountered:</p><ul>';
            foreach ($results['errors'] as $error) {
                echo '<li>' . esc_html($error) . '</li>';
            }
            echo '</ul></div>';
        }
    }
    
    ?>
    <div class="wrap">
        <h1>Import Villas from CSV</h1>
        <p>This will import villa properties from the CSV file located at:</p>
        <code><?php echo get_stylesheet_directory() . '/miDocs/SITE DATA/Properties-Updated.csv'; ?></code>
        
        <form method="post" action="">
            <?php wp_nonce_field('villa_csv_import'); ?>
            <p>
                <input type="submit" name="import_csv" class="button button-primary" value="Import Villas" 
                       onclick="return confirm('Are you sure you want to import villas? This will create new posts.');">
            </p>
        </form>
    </div>
    <?php
}

// WP-CLI command
if (defined('WP_CLI') && WP_CLI) {
    WP_CLI::add_command('import villas', function($args, $assoc_args) {
        $csv_file = isset($assoc_args['file']) ? $assoc_args['file'] : null;
        $importer = new VillaCsvImporter($csv_file);
        $result = $importer->import();
        
        if ($result) {
            WP_CLI::success("Imported {$importer->imported_count} villas successfully.");
        } else {
            WP_CLI::error("Import failed: " . implode(', ', $importer->errors));
        }
    });
}
