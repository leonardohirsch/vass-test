<?php

namespace VassRickMorty\Includes;

/**
 * Class Setup
 *
 * This class handles the main setup for the Vass Rick and Morty plugin.
 */
class Setup {

    /**
     * Initialize the setup by registering custom post types and taxonomies.
     */
    public static function run()
    {
        add_action('plugins_loaded', [self::class, 'load_textdomain']);
        add_action('init', [self::class, 'register_custom_post_type']);
        add_action('init', [self::class, 'register_taxonomy']);
        add_action('admin_menu', [self::class, 'add_admin_menu']);
    }

    public static function add_admin_menu()
    {
        if (current_user_can('manage_options')) {
            new VassRickMorty\Admin\CharacterImportPage(
                new VassRickMorty\Includes\CharacterImporter()
            );            
        }        
    }

    public static function admin_settings_page()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have permissions to access this page.', RICK_MORTY_TEXT_DOMAIN));
        }

        if (isset($_GET['success'])) {
            if ($_GET['success'] === '1') {
                echo '<div class="notice notice-success is-dismissible"><p>' . __('Characters imported successfully.', RICK_MORTY_TEXT_DOMAIN) . '</p></div>';
            } elseif ($_GET['success'] === '0') {
                echo '<div class="notice notice-error is-dismissible"><p>' . __('Failed to import characters.', RICK_MORTY_TEXT_DOMAIN) . '</p></div>';
            }
        }

        echo '<div class="wrap">';
        echo '<h1>' . esc_html(get_admin_page_title()) . '</h1>';
        echo '<form method="post" action="' . esc_html(admin_url('admin-post.php')) . '">';
        wp_nonce_field('import_rick_morty_action', 'import_rick_morty_nonce');
        echo '<input type="hidden" name="action" value="import_rick_morty_characters">';
        echo '<input type="submit" class="button-primary" value="' . esc_attr__('Import Characters', RICK_MORTY_TEXT_DOMAIN) . '">';
        echo '</form>';
        echo '</div>';
    }


    public static function load_textdomain()
    {
        load_plugin_textdomain(RICK_MORTY_TEXT_DOMAIN, false, basename(dirname(__FILE__)) . '/languages');
    }

    public static function register_custom_post_type()
    {
        new VassRickMorty\Includes\CharacterCPT();
    }

    public static function register_taxonomy()
    {
        new VassRickMorty\Includes\SpeciesTaxonomy();
    }
}
