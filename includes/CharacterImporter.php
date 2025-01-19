<?php

namespace VassRickMorty\Includes;

class CharacterImporter extends ImporterBase {
    protected static string $post_type = RICK_MORTY_PREFIX . 'character';
    
    //TODO: crear página para definir option
    public function __construct()
    {
        $api_url = get_option(RICK_MORTY_PREFIX . 'characters_api', 'https://rickandmortyapi.com/api/character/');
        parent::__construct($api_url);
    }

    protected function process_item($character)
    {
        if ($this->items_exists(static::$post_type, $character['id'])) {
            error_log('Character already imported: ' . $character['name']);
            return;
        }

        $post_id = wp_insert_post([
            'post_title'    => sanitize_text_field($character['name']),
            'post_status'   => 'publish',
            'post_type'     => static::$post_type,
            'meta_input'    => [
                'id'        => sanitize_text_field($character['id']),
                'status'    => sanitize_text_field($character['status']),
                'species'   => sanitize_text_field($character['species']),
                'gender'    => sanitize_text_field($character['gender']),
                'image'     => sanitize_text_field($character['image']),
            ]
        ]);

        if (!is_wp_error($post_id)) {
            if (isset($character['species'])) {
                wp_set_object_terms($post_id, $character['species'], 'species', false);
            }            
        } else {
            error_log('Failed to create character: ' . $character['name']);
        }
    }
}
