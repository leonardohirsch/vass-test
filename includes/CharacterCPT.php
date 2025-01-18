<?php

namespace VassRickMorty\Includes;

/**
 * Class CharacterCPT
 *
 * This class registers the custom post type for Characters.
 */
class CharacterCPT {
    protected static string $post_type = RICK_MORTY_PREFIX.'character';
    
    public function __construct()
    {
        add_action('init', [$this, 'register_character_cpt']);
    }

    public function register_character_cpt()
    {
        $labels = [
            'name'                  => __('Characters', RICK_MORTY_TEXT_DOMAIN),
            'singular_name'         => __('Character', RICK_MORTY_TEXT_DOMAIN),
            'menu_name'             => __('Characters', RICK_MORTY_TEXT_DOMAIN),
            'name_admin_bar'        => __('Character', RICK_MORTY_TEXT_DOMAIN),
            'add_new'               => __('Add New', RICK_MORTY_TEXT_DOMAIN),
            'add_new_item'          => __('Add New Character', RICK_MORTY_TEXT_DOMAIN),
            'new_item'              => __('New Character', RICK_MORTY_TEXT_DOMAIN),
            'edit_item'             => __('Edit Character', RICK_MORTY_TEXT_DOMAIN),
            'view_item'             => __('View Character', RICK_MORTY_TEXT_DOMAIN),
            'all_items'             => __('All Characters', RICK_MORTY_TEXT_DOMAIN),
            'search_items'          => __('Search Characters', RICK_MORTY_TEXT_DOMAIN),
            'not_found'             => __('No characters found.', RICK_MORTY_TEXT_DOMAIN),
            'not_found_in_trash'    => __('No characters found in Trash.', RICK_MORTY_TEXT_DOMAIN),
        ];

        $args = [
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => ['slug' => static::$post_type],
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => ['title', 'thumbnail', 'custom-fields'],
            'show_in_rest'       => true, 
        ];

        register_post_type(static::$post_type, $args);
    }
}

