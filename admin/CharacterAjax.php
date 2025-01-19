<?php

namespace VassRickMorty\Admin;

use VassRickMorty\Includes\ImporterBase;

/**
 * Class CharacterAjax
 *
 * Handles AJAX requests for importing characters from Rick and Morty API.
 */
class CharacterAjax {

    /**
     * CharacterAjax constructor.
     *
     * @param ImporterBase $importer
     */
    public function __construct(private ImporterBase $importer)
    {

    }

    /**
     * Initialize the AJAX actions.
     * 
     * @return void
     */
    public function init() : void
    {
        add_action('wp_ajax_import_characters', [$this, 'handle_import_request']);
    }
 
    /**
     * Handle the import request via AJAX.
     * 
     * @return void
     */
    public function handle_import_request() : void
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
