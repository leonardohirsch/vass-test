<?php

namespace VassRickMorty\Admin;

class ImportSettingPage {
    public function __construct()
    {
        add_action('admin_menu', [$this, 'add_import_settings_page']);
    }

    public function add_import_settings_page()
    {
        add_options_page(
            __('Rick and Morty Import Settings', RICK_MORTY_TEXT_DOMAIN),
            __('Import Settings', RICK_MORTY_TEXT_DOMAIN),
            'manage_options',
            RICK_MORTY_PREFIX.'import-settings',
            [$this, 'render_page']
        );
    }

    public function render_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Rick and Morty API Settings', RICK_MORTY_TEXT_DOMAIN); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields(RICK_MORTY_TEXT_DOMAIN.'options_group');
                do_settings_sections(RICK_MORTY_PREFIX.'import-settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function register_settings() {
        add_action('admin_init', function() {
            register_setting(RICK_MORTY_TEXT_DOMAIN.'options_group', 'rick_morty_api_url', [
                'type' => 'string',
                'sanitize_callback' => 'esc_url_raw',
                'default' => 'https://rickandmortyapi.com/api/'
            ]);

            register_setting(RICK_MORTY_TEXT_DOMAIN.'options_group', RICK_MORTY_PREFIX . 'count_cache_expires', [
                'type' => 'integer',
                'sanitize_callback' => 'absint',
                'default' => 30 * DAY_IN_SECONDS
            ]);

            add_settings_section(
                RICK_MORTY_PREFIX.'api_settings',
                __('API Settings', RICK_MORTY_TEXT_DOMAIN),
                function() { echo '<p>' . esc_html__('Configure Rick and Morty API settings.', RICK_MORTY_TEXT_DOMAIN) . '</p>'; },
                RICK_MORTY_PREFIX.'import-settings'
            );

            add_settings_field(
                RICK_MORTY_PREFIX.'characters_api_field',
                __('Characters API Endpoint URL', RICK_MORTY_TEXT_DOMAIN), 
                function() {
                    $url = get_option(RICK_MORTY_PREFIX.'characters_api', 'https://rickandmortyapi.com/api/character/');
                    echo '<input type="url" id="' . RICK_MORTY_PREFIX . 'characters_api" name="' . RICK_MORTY_PREFIX . 'characters_api" value="' . esc_attr($url) . '" />';
                },
                RICK_MORTY_PREFIX.'import-settings',
                RICK_MORTY_PREFIX.'api_settings'
            );

            add_settings_field(
                RICK_MORTY_PREFIX . 'count_cache_expires_field',
                __('Cache Expiry Time (seconds). Default 30 days', RICK_MORTY_TEXT_DOMAIN),
                function() {
                    $expires = get_option(RICK_MORTY_PREFIX . 'count_cache_expires', 30 * DAY_IN_SECONDS);
                    echo '<input type="number" id="' . RICK_MORTY_PREFIX . 'count_cache_expires" name="' . RICK_MORTY_PREFIX . 'count_cache_expires" value="' . esc_attr($expires) . '" min="60" />';
                }, 
                RICK_MORTY_PREFIX.'import-settings', 
                RICK_MORTY_PREFIX.'api_settings' 
            );
        });
    }
}
