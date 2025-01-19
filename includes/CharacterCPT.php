<?php

namespace VassRickMorty\Includes;

/**
 * Class CharacterCPT
 *
 * This class registers the custom post type for Characters.
 */
class CharacterCPT {
    protected static string $post_type = RICK_MORTY_PREFIX . 'character';
    
    /**
     * Registers the custom post type for Characters.
     * 
     * @return void
     */
    public static function register() : void
    {
        $labels = [
            'name'                  => __('Rick And Morty Characters', RICK_MORTY_TEXT_DOMAIN),
            'singular_name'         => __('Character', RICK_MORTY_TEXT_DOMAIN),
            'menu_name'             => __('Rick And Morty Characters', RICK_MORTY_TEXT_DOMAIN),
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
            'rewrite'            => ['slug' => self::$post_type],
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => ['title', 'custom-fields'],
            'show_in_rest'       => true, 
        ];

        register_post_type(self::$post_type, $args);

        add_action('delete_post', [self::class, 'update_post_count_on_delete']);
    }

    /**
     * Updates total posts count cache when a post is deleted.
     *
     * This method is triggered when a post is deleted and updates the post count
     * for the custom post type 'character'.
     *
     * @param int $post_id The ID of the post being deleted.
     * 
     * @return void
     */
    public static function update_post_count_on_delete( int $post_id ) : void
    {
        
        if (get_post_type($post_id) === self::$post_type) {
            
            $cache_count_name = self::$post_type . '_count';
            $item_count = get_transient($cache_count_name);
            if ($item_count !== false) {
                $new_count = $item_count - 1;
                $new_count = $new_count < 0 ? 0 : $new_count;
                set_transient($cache_count_name, $new_count);
            } 
        }
    }
}

