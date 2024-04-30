<?php

namespace Atproto\Contracts;

/**
 * Interface ClientContract
 *
 * This interface defines the contract for a client that can execute a request.
 */
interface ClientContract
{
    /**
     * Execute the request.
     *
     * @return mixed The result of executing the request.
     */
    public function execute();

    /**
     * Get the request object associated with this client.
     *
     * @return mixed The request object.
     */
    public function getRequest();
}
