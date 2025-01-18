<?php

namespace VassRickMorty\Includes;

class CharacterImporter extends ImporterBase {
    protected static string $post_type = 'character';
    
    //TODO: crear página para definir option
    public function __construct() {
        $api_url = get_option('rick_morty_characters_endpoint', 'https://rickandmortyapi.com/api/character/');
        parent::__construct($api_url);
    }

    protected function process_item($character)
    {
        if ($this->items_exists($character['id'])) {
            error_log('Character already imported: ' . $character['name']);
            return;
        }

        $post_id = wp_insert_post([
            'post_title'    => $character['name'],
            'post_status'   => 'publish',
            'post_type'     => self::$post_type,
            'meta_input'    => [
                'id'        => $character['id'],
                'status'    => $character['status'],
                'species'   => $character['species'],
                'gender'    => $character['gender'],
                'image'     => $character['image'],
                'origin'    => $character['origin']['name'],
                'location'  => $character['location']['name']
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
