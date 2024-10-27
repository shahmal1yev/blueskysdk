<?php

namespace Atproto\Contracts;

/**
 * Interface AuthStrategyContract
 *
 * This interface defines the contract for an authentication strategy.
 */
interface AuthStrategyContract
{
    /**
     * Authenticate using the provided credentials.
     *
     * @param array $credentials The authentication credentials
     * @return mixed The authentication result
     */
    public function authenticate(array $credentials);
}
