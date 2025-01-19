<?php

namespace VassRickMorty\Admin;

/**
 * Class ImportSettingPage
 *
 * This class handles the import settings page for the Rick and Morty plugin.
 */
class ImportSettingPage {
    /**
     * ImportSettingPage constructor.
     * Adds the import settings page to the admin menu.
     */
    public function __construct()
    {
        add_action('admin_menu', [$this, 'add_import_settings_page'], 12);
    }

    /**
     * Adds the import settings page to the admin menu.
     * 
     * @return void
     */
    public function add_import_settings_page() : void
    {
        add_submenu_page(
            RICK_MORTY_PREFIX . 'character-import', 
            __('Rick and Morty Import Settings', RICK_MORTY_TEXT_DOMAIN),
            __('Import Settings', RICK_MORTY_TEXT_DOMAIN),
            'manage_options',
            RICK_MORTY_PREFIX . 'import-settings',
            [$this, 'render_page']
        );
    }

    /**
     * Renders the import settings page.
     * 
     * @return void
     */
    public function render_page() : void
    {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Rick and Morty API Settings', RICK_MORTY_TEXT_DOMAIN); ?></h1>
            <?php settings_errors(); ?>
            <form method="post" action="options.php">
                <?php
                settings_fields(RICK_MORTY_TEXT_DOMAIN.'options_group');
                do_settings_sections(RICK_MORTY_PREFIX . 'import-settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    
    /**
     * Registers the import options.
     * 
     * @return void
     */
    public function register_settings() : void
    {
        add_action('admin_init', function() {
            register_setting(RICK_MORTY_TEXT_DOMAIN.'options_group', RICK_MORTY_PREFIX . 'characters_api', [
                'type' => 'string',
                'sanitize_callback' => [$this, 'validate_api_url'],
                'default' => 'https://rickandmortyapi.com/api/character/'
            ]);

            register_setting(RICK_MORTY_TEXT_DOMAIN.'options_group', RICK_MORTY_PREFIX . 'count_cache_expires', [
                'type' => 'integer',
                'sanitize_callback' => [$this, 'validate_cache_expires'],   
                'default' => 30 * DAY_IN_SECONDS
            ]);

            add_settings_section(
                RICK_MORTY_PREFIX . 'api_settings',
                __('API Settings', RICK_MORTY_TEXT_DOMAIN),
                function() { echo '<p>' . esc_html__('Configure Rick and Morty API settings.', RICK_MORTY_TEXT_DOMAIN) . '</p>'; },
                RICK_MORTY_PREFIX . 'import-settings'
            );

            add_settings_field(
                RICK_MORTY_PREFIX . 'characters_api_field',
                __('Characters API Endpoint URL', RICK_MORTY_TEXT_DOMAIN), 
                function() {
                    $url = get_option(RICK_MORTY_PREFIX . 'characters_api', 'https://rickandmortyapi.com/api/character/');
                    echo '<input type="url" id="' . RICK_MORTY_PREFIX . 'characters_api" name="' . RICK_MORTY_PREFIX . 'characters_api" value="' . esc_attr($url) . '" />';
                },
                RICK_MORTY_PREFIX . 'import-settings',
                RICK_MORTY_PREFIX . 'api_settings'
            );

            add_settings_field(
                RICK_MORTY_PREFIX . 'count_cache_expires_field',
                __('Cache Expiry Time (seconds). Default 30 days', RICK_MORTY_TEXT_DOMAIN),
                function() {
                    $expires = get_option(RICK_MORTY_PREFIX . 'count_cache_expires', 30 * DAY_IN_SECONDS);
                    echo '<input type="number" id="' . RICK_MORTY_PREFIX . 'count_cache_expires" name="' . RICK_MORTY_PREFIX . 'count_cache_expires" value="' . esc_attr($expires) . '" min="60" />';
                }, 
                RICK_MORTY_PREFIX . 'import-settings', 
                RICK_MORTY_PREFIX . 'api_settings' 
            );
        });
    }

    
    /**
     * Sanitizes callback for the api url field.
     * 
     */
    public function validate_api_url($input)
    {
        if (empty($input)) {
            add_settings_error(
                RICK_MORTY_PREFIX . 'characters_api',
                'characters_api_empty',
                'The Characters API URL cannot be empty.', 
                'error'
            );

    
            return get_option(RICK_MORTY_PREFIX . 'characters_api');
        }

        return esc_url_raw($input);
    }

    /**
     * Sanitizes callback for the cache expiration field.
     * 
     */
    public function validate_cache_expires($input)
    {
        if (empty($input) || !is_numeric($input) || (int)$input <= 0) {
            add_settings_error(
                RICK_MORTY_PREFIX . 'count_cache_expires',
                'count_cache_expires_empty',
                'The cache expiry time cannot be empty and must be a positive integer.',
                'error'
            );
            return get_option(RICK_MORTY_PREFIX . 'count_cache_expires');
        }
        
        return absint($input);
    }
}
