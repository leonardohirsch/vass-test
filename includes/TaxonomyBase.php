<?php

namespace VassRickMorty\Includes;

/**
 * Class TaxonomyBase
 *
 * This is base clase for registering custom taxonomies.
 */
class TaxonomyBase {
    public function __construct(
        protected string $taxonomy,
        protected array $object_type,
        protected array $args
    )
    {}

    public function register()
    {
        register_taxonomy($this->taxonomy, $this->object_type, $this->args);
    }
}
