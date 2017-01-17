<?php

namespace AutoDeploy\Origin;

use AutoDeploy\ServiceProvider\BitBucket;

/**
 * Requester class.
 */
class Requester
{
    private $ServiceProvider;

    /**
     * Detect service provider.
     */
    final public function __construct()
    {
        $Request = new Request();

        /*
         * BitBucket 2.0
         */

        if ($Request->getHeader('User-Agent') === 'Bitbucket-Webhooks/2.0') {
            $this->ServiceProvider = new BitBucket($Request, '2.0');
        }
    }

    /**
     * Get parameter.
     *
     * @param string $key Parameter name
     *
     * @return string Parameter value
     */
    final public function __get($key)
    {
        return (isset($this->{$key})) ? $this->{$key} : null;
    }
}
