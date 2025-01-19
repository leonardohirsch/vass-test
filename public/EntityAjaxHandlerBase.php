<?php

namespace VassRickMorty\Public;

abstract class EntityAjaxHandlerBase {

    public function __construct(private EntityQueryHandler $queryHandler)
    {}

    abstract public function execute();
   
    abstract public function handleLoading();
}
