<?php

namespace VassRickMorty\Public;

/**
 * Base class for handling AJAX requests related to Custom Post Types.
 */
abstract class CptAjaxHandlerBase {

    /**
     * Constructor for the CptAjaxHandlerBase class.
     *
     * @param CptQueryHandler $queryHandler The query handler instance.
     */
    public function __construct(protected CptQueryHandler $queryHandler)
    {

    }

    /**
     * Add Ajax hooks.
     */
    abstract public function init();
   
    /**
     * Handle the loading of entities via AJAX.
     */
    abstract public function handle_loading();
}
