<?php

namespace VassRickMorty\Public;

use VassRickMorty\Public\EntityQueryHandler;

class CharacterShortcodeManager {

    public function __construct(private EntityQueryHandler $queryHandler) 
    {}

    public function execute()
    {
        add_shortcode('rick_morty_characters', [$this, 'renderShortcode']);
    }

    public function renderShortcode() {
        $species_options = $this->queryHandler->getTaxOptions();
        ob_start();
        include 'views/shortcode-form.php';
        return ob_get_clean();
    }
}