<?php
/**
 * TextBook Helper Functions - Semantic Typography System
 * Provides easy access to semantic text elements and size utilities
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get semantic text element configuration
function textbook_get_semantic($element_key) {
    $semantic_elements = textbook_get_current_semantic_settings();
    return $semantic_elements[$element_key] ?? null;
}

// Generate semantic text class
function textbook_semantic_class($element_key, $size_override = null) {
    $element = textbook_get_semantic($element_key);
    if (!$element) {
        return '';
    }
    
    $classes = ["semantic-{$element_key}"];
    
    // Add size override if provided
    if ($size_override) {
        $classes[] = "text-{$size_override}";
    }
    
    return implode(' ', $classes);
}

// Get semantic element tag
function textbook_semantic_tag($element_key) {
    $element = textbook_get_semantic($element_key);
    return $element['tag'] ?? 'span';
}

// Render semantic text element
function textbook_render_semantic($element_key, $content, $size_override = null, $additional_classes = '') {
    $tag = textbook_semantic_tag($element_key);
    $class = textbook_semantic_class($element_key, $size_override);
    
    if ($additional_classes) {
        $class .= ' ' . $additional_classes;
    }
    
    return "<{$tag} class=\"{$class}\">{$content}</{$tag}>";
}

// Get all available text sizes
function textbook_get_sizes() {
    return ['xs', 'sm', 'md', 'lg', 'xl', 'xxl', 'xxxl'];
}

// Get all available font weights
function textbook_get_weights() {
    return [300, 400, 500, 600, 700, 800, 900];
}

// Get all available text transforms
function textbook_get_transforms() {
    return ['none', 'uppercase', 'lowercase', 'capitalize'];
}

// Generate size dropdown for admin
function textbook_size_dropdown($name, $current_value = 'md', $include_empty = false) {
    $sizes = textbook_get_sizes();
    $html = "<select name=\"{$name}\">";
    
    if ($include_empty) {
        $html .= "<option value=\"\">Default</option>";
    }
    
    foreach ($sizes as $size) {
        $selected = selected($current_value, $size, false);
        $html .= "<option value=\"{$size}\" {$selected}>" . strtoupper($size) . "</option>";
    }
    
    $html .= "</select>";
    return $html;
}

// Generate weight dropdown for admin
function textbook_weight_dropdown($name, $current_value = 400, $include_empty = false) {
    $weights = textbook_get_weights();
    $html = "<select name=\"{$name}\">";
    
    if ($include_empty) {
        $html .= "<option value=\"\">Default</option>";
    }
    
    foreach ($weights as $weight) {
        $selected = selected($current_value, $weight, false);
        $html .= "<option value=\"{$weight}\" {$selected}>{$weight}</option>";
    }
    
    $html .= "</select>";
    return $html;
}

// Generate transform dropdown for admin
function textbook_transform_dropdown($name, $current_value = 'none', $include_empty = false) {
    $transforms = textbook_get_transforms();
    $html = "<select name=\"{$name}\">";
    
    if ($include_empty) {
        $html .= "<option value=\"\">Default</option>";
    }
    
    foreach ($transforms as $transform) {
        $selected = selected($current_value, $transform, false);
        $html .= "<option value=\"{$transform}\" {$selected}>" . ucfirst($transform) . "</option>";
    }
    
    $html .= "</select>";
    return $html;
}

// Get Twig context for semantic elements
function textbook_get_twig_context() {
    $semantic_elements = textbook_get_current_semantic_settings();
    $context = ['textbook' => []];
    
    foreach ($semantic_elements as $key => $element) {
        $context['textbook'][$key] = $element;
    }
    
    // Add utility functions
    $context['textbook']['sizes'] = textbook_get_sizes();
    $context['textbook']['weights'] = textbook_get_weights();
    $context['textbook']['transforms'] = textbook_get_transforms();
    
    return $context;
}

// Shorthand functions for common semantic elements
function tb_pretitle($content, $size = null) {
    return textbook_render_semantic('pretitle', $content, $size);
}

function tb_title($content, $size = null) {
    return textbook_render_semantic('title', $content, $size);
}

function tb_subtitle($content, $size = null) {
    return textbook_render_semantic('subtitle', $content, $size);
}

function tb_description($content, $size = null) {
    return textbook_render_semantic('description', $content, $size);
}

function tb_body($content, $size = null) {
    return textbook_render_semantic('body', $content, $size);
}

function tb_button($content, $size = null) {
    return textbook_render_semantic('button', $content, $size);
}

function tb_caption($content, $size = null) {
    return textbook_render_semantic('caption', $content, $size);
}

function tb_label($content, $size = null) {
    return textbook_render_semantic('label', $content, $size);
}

// Utility function to get text class for manual usage
function tb_text_class($size, $weight = null, $color = null, $transform = null) {
    $classes = ["text-{$size}"];
    
    if ($weight) {
        $classes[] = "font-{$weight}";
    }
    
    if ($color) {
        $classes[] = "text-{$color}";
    }
    
    if ($transform) {
        $classes[] = "text-{$transform}";
    }
    
    return implode(' ', $classes);
}

// Check if semantic element exists
function textbook_has_semantic($element_key) {
    $semantic_elements = textbook_get_current_semantic_settings();
    return isset($semantic_elements[$element_key]);
}

// Get all semantic element keys
function textbook_get_semantic_keys() {
    $semantic_elements = textbook_get_current_semantic_settings();
    return array_keys($semantic_elements);
}
?>
