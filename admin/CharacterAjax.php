<?php

namespace VassRickMorty\Admin;

use VassRickMorty\Includes\ImporterBase;

class CharacterAjax {

    public function __construct(private ImporterBase $importer)
    {}

    public function init() {
        add_action('wp_ajax_import_characters', [$this, 'handle_import_request']);
    }
 
    public function handle_import_request()
    {        
        if (
            !check_ajax_referer('import_characters_nonce') ||
            !current_user_can('manage_options')
        ) {
            wp_send_json_error(['message' => __('Unauthorized request.', RICK_MORTY_TEXT_DOMAIN)]);
        }        

        $result = $this->importer->import_data();
        
        if ($result['success']) {
            wp_send_json_success(['message' => __('Characters imported successfully.', RICK_MORTY_TEXT_DOMAIN)]);
        } else {
            wp_send_json_error(['message' => $result['message']]);
        }
    }
}
