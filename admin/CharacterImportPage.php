<?php

namespace VassRickMorty\Admin;

class CharacterImportPage {

    public function init() {
        add_action('admin_menu', [$this, 'add_import_page'], 11);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    public function add_import_page()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        add_menu_page(
            __('Rick and Morty API Import', RICK_MORTY_TEXT_DOMAIN),
            __('Rick and Morty API', RICK_MORTY_TEXT_DOMAIN),
            'manage_options',
            RICK_MORTY_PREFIX . 'character-import',
            [$this, 'render_page'],
            'dashicons-admin-generic'
        );

        add_submenu_page(
            RICK_MORTY_PREFIX . 'character-import',
            __('Rick and Morty API Import', RICK_MORTY_TEXT_DOMAIN),
            __('Import Characters', RICK_MORTY_TEXT_DOMAIN),
            'manage_options',
            RICK_MORTY_PREFIX . 'character-import',
            [$this, 'render_page'],
        );
    }

    public function render_page()
    {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <button id="import-characters" class="button button-primary">
                <?php esc_html_e('Import Characters', RICK_MORTY_TEXT_DOMAIN); ?>
            </button>
            <div id="import-loading-message" class="notice notice-info" style="display: none;">
                <p><?php esc_html_e('Loading...', RICK_MORTY_TEXT_DOMAIN); ?></p>
            </div>
            <div id="import-messages"></div>
        </div>
        <?php
    }

    public function enqueue_scripts($hook_suffix)
    {
        if ('toplevel_page_'.RICK_MORTY_PREFIX.'character-import' !== $hook_suffix) {
            return;
        }
        wp_enqueue_script(RICK_MORTY_PREFIX . 'import-js', plugin_dir_url(__FILE__) . 'js/import.js', ['jquery'], null, true);
        wp_localize_script(RICK_MORTY_PREFIX . 'import-js', 'rickMortyAjax', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('import_characters_nonce')
        ]);
    }
}
