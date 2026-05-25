<?php
defined('ABSPATH') || exit;

add_action('init', 'fg_antraege_register_post_type');

function fg_antraege_register_post_type() {
    register_post_type('fg_antrag', [
        'labels'       => [
            'name'               => 'Anträge',
            'singular_name'      => 'Antrag',
            'add_new_item'       => 'Neuen Antrag hinzufügen',
            'edit_item'          => 'Antrag bearbeiten',
            'not_found'          => 'Keine Anträge gefunden',
            'menu_name'          => 'Anträge',
        ],
        'public'       => true,
        'has_archive'  => false,
        'show_in_menu' => true,
        'menu_icon'    => 'dashicons-clipboard',
        'supports'     => ['title', 'editor'],
        'rewrite'      => ['slug' => 'antraege'],
    ]);
}
