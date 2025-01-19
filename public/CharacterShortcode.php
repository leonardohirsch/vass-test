<?php

namespace VassRickMorty\Public;

class CharacterShortcode {

    private $load_assets = false;

    public function __construct(private EntityQueryHandler $queryHandler) 
    {}

    public function execute()
    {
        add_shortcode('rick_morty_characters', [$this, 'renderShortcode']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function renderShortcode()
    {
        $this->load_assets = true;
        
        $species_options = $this->queryHandler->getTaxOptions('species');
        $initial_posts = $this->queryHandler->fetchEntity(RICK_MORTY_PREFIX . 'character');
        ob_start();
        include plugin_dir_path(__FILE__) . 'views/CharacterShortcodeView.php';
        return ob_get_clean();
    }

    public function enqueue_assets() {
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
}