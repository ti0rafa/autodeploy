<?php

namespace AutoDeploy\ServiceProvider;

/**
 * Service provider abstract class.
 */
abstract class ServiceProvider
{
    protected $branches = [];
    protected $repo;
    protected $full_name;
    protected $update = true;
    protected $vcs;

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

    /**
     * Checks if branch was updated.
     *
     * @param string $branch Branch name
     *
     * @return bool
     */
    final public function hasBranch($branch)
    {
        return in_array($branch, $this->branches);
    }
}
