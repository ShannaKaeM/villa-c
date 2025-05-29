<?php
/**
 * Article Settings Field Group
 *
 * @package CarbonBlocks
 */

if (! defined('ABSPATH')) {
    exit;
}

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make('post_meta', 'Article Settings')
    ->where('post_type', '=', 'articles')
    ->add_tab('Portal Settings', array(
        Field::make('checkbox', 'portal_announcement', 'Show in Portal')
            ->set_help_text('Display this article as an announcement in the owner portal'),
        Field::make('select', 'announcement_priority', 'Priority Level')
            ->set_options(array(
                'low' => 'Low',
                'normal' => 'Normal',
                'high' => 'High',
                'urgent' => 'Urgent',
            ))
            ->set_default_value('normal')
            ->set_conditional_logic(array(
                array(
                    'field' => 'portal_announcement',
                    'value' => true,
                )
            )),
        Field::make('date', 'announcement_expires', 'Expiration Date')
            ->set_help_text('Optional: When this announcement should stop showing in the portal')
            ->set_conditional_logic(array(
                array(
                    'field' => 'portal_announcement',
                    'value' => true,
                )
            )),
    ))
    ->add_tab('Article Details', array(
        Field::make('select', 'article_category', 'Article Category')
            ->set_options(array(
                'general' => 'General News',
                'maintenance' => 'Maintenance Updates',
                'events' => 'Community Events',
                'rules' => 'Rules & Regulations',
                'financial' => 'Financial Updates',
                'board' => 'Board Communications',
            ))
            ->set_default_value('general'),
        Field::make('multiselect', 'target_roles', 'Target Audience')
            ->set_options(array(
                'villa_owner' => 'Villa Owners',
                'bod_member' => 'Board Members',
                'committee_member' => 'Committee Members',
                'staff_member' => 'Staff',
                'dvo_member' => 'DVO Members',
            ))
            ->set_help_text('Leave empty to show to all portal users'),
        Field::make('textarea', 'article_summary', 'Article Summary')
            ->set_help_text('Brief summary for portal display (optional - will use excerpt if empty)')
            ->set_rows(3),
    ));
