<?php

namespace VassRickMorty\Includes;

/**
 * Class TaxonomyBase
 *
 * This is base clase for registering custom taxonomies.
 */
class TaxonomyBase {
    
    /**
     * Constructor for the TaxonomyBase class.
     *
     * @param string $taxonomy_name The taxonomy name.
     * @param array $object_type The object type(s) for the taxonomy.
     * @param array $args The arguments for registering the taxonomy.
     */
    public function __construct(
        protected string $taxonomy_name,
        protected array $object_type,
        protected array $args
    ) {
        
    }

    /**
     * Register the custom taxonomy.
     *
     * @return void
     */
    public function register() : void
    {
        register_taxonomy($this->taxonomy_name, $this->object_type, $this->args);
    }
}
