<?php
/**
 * Property Filter Widget
 * Integrates with Blocksy's sidebar system for property filtering
 */

class Villa_Property_Filter_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'villa_property_filter',
            __('Villa Property Filter', 'carbon-blocksy'),
            array(
                'description' => __('Filter properties by type, location, and features', 'carbon-blocksy'),
                'classname' => 'villa-property-filter-widget'
            )
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];

        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        $show_type_filter = !empty($instance['show_type_filter']) ? $instance['show_type_filter'] : true;
        $show_location_filter = !empty($instance['show_location_filter']) ? $instance['show_location_filter'] : true;
        $show_featured_filter = !empty($instance['show_featured_filter']) ? $instance['show_featured_filter'] : true;
        $show_price_filter = !empty($instance['show_price_filter']) ? $instance['show_price_filter'] : false;
        ?>

        <div class="villa-property-filter">
            <form id="villa-property-filter-form" class="villa-property-filter__form">
                
                <?php if ($show_type_filter): ?>
                <div class="villa-property-filter__group">
                    <label class="villa-property-filter__label" for="property-type-filter">
                        <?php _e('Property Type', 'carbon-blocksy'); ?>
                    </label>
                    <select id="property-type-filter" name="property_type" class="villa-property-filter__select">
                        <option value=""><?php _e('All Types', 'carbon-blocksy'); ?></option>
                        <option value="house">🏠 <?php _e('House', 'carbon-blocksy'); ?></option>
                        <option value="condo">🏢 <?php _e('Condo', 'carbon-blocksy'); ?></option>
                        <option value="cottage">🏡 <?php _e('Cottage', 'carbon-blocksy'); ?></option>
                        <option value="villa">🏖️ <?php _e('Villa', 'carbon-blocksy'); ?></option>
                        <option value="apartment">🏠 <?php _e('Apartment', 'carbon-blocksy'); ?></option>
                    </select>
                </div>
                <?php endif; ?>

                <?php if ($show_location_filter): ?>
                <div class="villa-property-filter__group">
                    <label class="villa-property-filter__label" for="location-filter">
                        <?php _e('Location', 'carbon-blocksy'); ?>
                    </label>
                    <select id="location-filter" name="location" class="villa-property-filter__select">
                        <option value=""><?php _e('All Locations', 'carbon-blocksy'); ?></option>
                        <option value="north-topsail-beach">🌊 <?php _e('North Topsail Beach', 'carbon-blocksy'); ?></option>
                        <option value="surf-city">📍 <?php _e('Surf City', 'carbon-blocksy'); ?></option>
                        <option value="topsail-beach">🏖️ <?php _e('Topsail Beach', 'carbon-blocksy'); ?></option>
                    </select>
                </div>
                <?php endif; ?>

                <?php if ($show_featured_filter): ?>
                <div class="villa-property-filter__group">
                    <label class="villa-property-filter__checkbox-label">
                        <input type="checkbox" id="featured-filter" name="featured" value="1" class="villa-property-filter__checkbox">
                        <span class="villa-property-filter__checkbox-text">⭐ <?php _e('Featured Only', 'carbon-blocksy'); ?></span>
                    </label>
                </div>
                <?php endif; ?>

                <?php if ($show_price_filter): ?>
                <div class="villa-property-filter__group">
                    <label class="villa-property-filter__label" for="price-range">
                        <?php _e('Price Range', 'carbon-blocksy'); ?>
                    </label>
                    <select id="price-range" name="price_range" class="villa-property-filter__select">
                        <option value=""><?php _e('Any Price', 'carbon-blocksy'); ?></option>
                        <option value="0-200">$0 - $200</option>
                        <option value="200-400">$200 - $400</option>
                        <option value="400-600">$400 - $600</option>
                        <option value="600+"><?php _e('$600+', 'carbon-blocksy'); ?></option>
                    </select>
                </div>
                <?php endif; ?>

                <div class="villa-property-filter__actions">
                    <button type="button" id="apply-filters" class="villa-property-filter__button villa-property-filter__button--primary">
                        <?php _e('Apply Filters', 'carbon-blocksy'); ?>
                    </button>
                    <button type="button" id="clear-filters" class="villa-property-filter__button villa-property-filter__button--secondary">
                        <?php _e('Clear All', 'carbon-blocksy'); ?>
                    </button>
                </div>
            </form>
        </div>

        <style>
        .villa-property-filter {
            /* Let Blocksy handle base styling */
        }

        .villa-property-filter__group {
            margin-bottom: 1rem;
        }

        .villa-property-filter__label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .villa-property-filter__select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid var(--theme-border-color, #ddd);
            border-radius: 4px;
            background: var(--theme-background-color, #fff);
            color: var(--theme-text-color, #333);
        }

        .villa-property-filter__checkbox-label {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .villa-property-filter__checkbox {
            margin-right: 0.5rem;
        }

        .villa-property-filter__actions {
            margin-top: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .villa-property-filter__button {
            padding: 0.75rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .villa-property-filter__button--primary {
            background: var(--theme-palette-color-1, #5a7f80);
            color: var(--theme-palette-color-8, #fff);
        }

        .villa-property-filter__button--primary:hover {
            background: var(--theme-palette-color-2, #425a5b);
        }

        .villa-property-filter__button--secondary {
            background: transparent;
            color: var(--theme-text-color, #666);
            border: 1px solid var(--theme-border-color, #ddd);
        }

        .villa-property-filter__button--secondary:hover {
            background: var(--theme-palette-color-10, #f5f5f5);
        }
        </style>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('villa-property-filter-form');
            const applyButton = document.getElementById('apply-filters');
            const clearButton = document.getElementById('clear-filters');

            if (applyButton) {
                applyButton.addEventListener('click', function() {
                    // Trigger custom event for property listing blocks to listen to
                    const filterData = new FormData(form);
                    const filters = {};
                    for (let [key, value] of filterData.entries()) {
                        if (value) filters[key] = value;
                    }
                    
                    document.dispatchEvent(new CustomEvent('villaPropertyFilter', {
                        detail: { filters: filters }
                    }));
                });
            }

            if (clearButton) {
                clearButton.addEventListener('click', function() {
                    form.reset();
                    document.dispatchEvent(new CustomEvent('villaPropertyFilter', {
                        detail: { filters: {} }
                    }));
                });
            }
        });
        </script>

        <?php
        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Filter Properties', 'carbon-blocksy');
        $show_type_filter = !empty($instance['show_type_filter']) ? $instance['show_type_filter'] : true;
        $show_location_filter = !empty($instance['show_location_filter']) ? $instance['show_location_filter'] : true;
        $show_featured_filter = !empty($instance['show_featured_filter']) ? $instance['show_featured_filter'] : true;
        $show_price_filter = !empty($instance['show_price_filter']) ? $instance['show_price_filter'] : false;
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:', 'carbon-blocksy'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_type_filter); ?> id="<?php echo esc_attr($this->get_field_id('show_type_filter')); ?>" name="<?php echo esc_attr($this->get_field_name('show_type_filter')); ?>" value="1">
            <label for="<?php echo esc_attr($this->get_field_id('show_type_filter')); ?>"><?php _e('Show Property Type Filter', 'carbon-blocksy'); ?></label>
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_location_filter); ?> id="<?php echo esc_attr($this->get_field_id('show_location_filter')); ?>" name="<?php echo esc_attr($this->get_field_name('show_location_filter')); ?>" value="1">
            <label for="<?php echo esc_attr($this->get_field_id('show_location_filter')); ?>"><?php _e('Show Location Filter', 'carbon-blocksy'); ?></label>
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_featured_filter); ?> id="<?php echo esc_attr($this->get_field_id('show_featured_filter')); ?>" name="<?php echo esc_attr($this->get_field_name('show_featured_filter')); ?>" value="1">
            <label for="<?php echo esc_attr($this->get_field_id('show_featured_filter')); ?>"><?php _e('Show Featured Filter', 'carbon-blocksy'); ?></label>
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_price_filter); ?> id="<?php echo esc_attr($this->get_field_id('show_price_filter')); ?>" name="<?php echo esc_attr($this->get_field_name('show_price_filter')); ?>" value="1">
            <label for="<?php echo esc_attr($this->get_field_id('show_price_filter')); ?>"><?php _e('Show Price Range Filter', 'carbon-blocksy'); ?></label>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['show_type_filter'] = !empty($new_instance['show_type_filter']) ? 1 : 0;
        $instance['show_location_filter'] = !empty($new_instance['show_location_filter']) ? 1 : 0;
        $instance['show_featured_filter'] = !empty($new_instance['show_featured_filter']) ? 1 : 0;
        $instance['show_price_filter'] = !empty($new_instance['show_price_filter']) ? 1 : 0;

        return $instance;
    }
}

// Register the widget
function villa_register_property_filter_widget() {
    register_widget('Villa_Property_Filter_Widget');
}
add_action('widgets_init', 'villa_register_property_filter_widget');
