<?php

namespace VassRickMorty\Public;

/**
 * Class CharacterShortcode
 *
 * Handles the shortcode for displaying Rick and Morty characters in Front-end.
 */
class CharacterShortcode {

    private $load_assets = false;

    /**
     * CharacterShortcode constructor.
     *
     * @param CptQueryHandler $queryHandler The query handler for custom post types.
     */
    public function __construct(private CptQueryHandler $queryHandler) 
    {

    }

    /**
     * Loads css and script for the shortcode.
     *
     * @return void
     */
    public function enqueue_assets() : void
    {
        if ($this->load_assets) {
            wp_enqueue_script(RICK_MORTY_PREFIX . 'characters-ajax', plugin_dir_url(__FILE__) . 'js/rm-characters-shortcode.js', ['jquery'], null, true);
            wp_localize_script(RICK_MORTY_PREFIX . 'characters-ajax', 'rmAjax', [
                'ajaxurl' => admin_url('admin-ajax.php'),
                'rm_action' => "load_characters",
                'nonce' => wp_create_nonce('load_rick_morty_nonce')
            ]);

            wp_enqueue_style(RICK_MORTY_PREFIX . 'characters-style', plugin_dir_url(__FILE__) . 'css/rm-characters-style.css');
        }
    }

    /**
     * Add hooks for the shortcode.
     * 
     * @return void
     */
    public function execute() :  void
    {
        add_shortcode('rick_morty_characters', [$this, 'render']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    /**
     * Render the shortcode.
     *
     * @return string The rendered HTML of the shortcode.
     */
    public function render() : string
    {
        $this->load_assets = true;
        
        $species_options = $this->queryHandler->get_taxonomy_terms('species');
        $initial_posts = $this->queryHandler->get_cpt_posts(RICK_MORTY_PREFIX . 'character');
        ob_start();
        include plugin_dir_path(__FILE__) . 'views/CharacterShortcodeView.php';
        return ob_get_clean();
    }
    
}