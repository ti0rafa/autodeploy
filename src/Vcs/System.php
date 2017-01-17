<?php

namespace AutoDeploy\Vcs;

/**
 * VCS system class.
 */
class System
{
    public static $list = ['git'];

    private $VcsHandler;

    /**
     * Construct.
     *
     * @param string $vcs Version control system
     */
    final public function __construct($vcs, $vcs_path)
    {
        switch ($vcs) {
            case 'git':
                $this->VcsHandler = new Git($vcs_path);
                break;
        }
    }

    /**
     * Update repository.
     *
     * @param string $domain      Domain
     * @param string $repo        Repository
     * @param string $branch      Branch name
     * @param string $destination Destination
     */
    final public function update($domain, $repo, $branch, $destination)
    {
        $this->VcsHandler->update($domain, $repo, $branch, $destination);
    }
}
