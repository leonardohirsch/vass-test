<?php

namespace VassRickMorty\Includes;

/**
 * Class TaxonomyBase
 *
 * This is base clase for registering custom taxonomies.
 */
class TaxonomyBase {
    protected $taxonomy;
    protected $object_type;
    protected $args;

    public function __construct($taxonomy, $object_type, $args)
    {
        $this->taxonomy = $taxonomy;
        $this->object_type = $object_type;
        $this->args = $args;

        add_action('init', [$this, 'register_taxonomy']);
    }

    public function register_taxonomy()
    {
        register_taxonomy($this->taxonomy, $this->object_type, $this->args);
    }
}
