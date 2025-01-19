<?php

namespace VassRickMorty\Public;

abstract class EntityAjaxHandlerBase {

    public function __construct(protected EntityQueryHandler $queryHandler)
    {}

    abstract public function init();
   
    abstract public function handle_loading();
}
