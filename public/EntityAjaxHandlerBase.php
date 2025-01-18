<?php

namespace VassRickMorty\Public;

use VassRickMorty\Public\EntityQueryHandler;

abstract class EntityAjaxHandlerBase {

    public function __construct(private EntityQueryHandler $queryHandler)
    {}

    abstract public function execute();
   
    abstract public function handleLoading();
}
