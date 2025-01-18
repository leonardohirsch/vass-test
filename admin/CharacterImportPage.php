<?php

namespace VassRickMorty\Admin;

use VassRickMorty\Includes\ImporterBase;

class CharacterImportPage {

    public function __construct(private ImporterBase $importer)
    {
        add_action('admin_menu', [$this, 'add_import_page']);
        add_action('wp_ajax_import_characters', [$this, 'handle_import_request']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    public function add_import_page()
    {
        add_menu_page(
            __('Rick and Morty Import', RICK_MORTY_TEXT_DOMAIN),
            __('Rick and Morty Import', RICK_MORTY_TEXT_DOMAIN),
            'manage_options',
            RICK_MORTY_PREFIX.'chracter-import',
            [$this, 'render_import_page'],
            'dashicons-admin-generic'
        );
    }

    public function render_import_page()
    {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <button id="import-characters" class="button button-primary">
                <?php esc_html_e('Import Characters', RICK_MORTY_TEXT_DOMAIN); ?>
            </button>
            <div id="loading-message" style="display: none;">
                <?php esc_html_e('Loading...', RICK_MORTY_TEXT_DOMAIN); ?>
            </div>
            <div id="import-messages"></div>
        </div>
        <?php
    }


    public function handle_import_request()
    {
        if (!check_ajax_referer('import_characters_nonce')) {
            wp_send_json_error(['message' => __('Unauthorized request.', RICK_MORTY_TEXT_DOMAIN)]);
        }        

        $result = $this->importer->import_data();
        if ($result['success']) {
            wp_send_json_success(['message' => __('Characters imported successfully.', RICK_MORTY_TEXT_DOMAIN)]);
        } else {
            wp_send_json_error(['message' => $result['message']]);
        }
    }

    public function enqueue_scripts($hook)
    {
        if ('toplevel_page_rick-morty-import' !== $hook) {
            return;
        }
        wp_enqueue_script(RICK_MORTY_PREFIX.'import-js', plugin_dir_url(__FILE__) . 'js/import.js', ['jquery'], null, true);
        wp_localize_script(RICK_MORTY_PREFIX.'import-js', 'rickMortyAjax', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('import_characters_nonce')
        ]);
    }
}
