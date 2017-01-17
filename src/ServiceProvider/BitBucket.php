<?php

namespace AutoDeploy\ServiceProvider;

use AutoDeploy\Origin\Request;

/**
 * BitBucket.
 */
class BitBucket extends ServiceProvider
{
    /**
     * Construct.
     *
     * @param Request $Request Request object
     * @param string  $version Version
     */
    public function __construct(Request $Request, $version = null)
    {
        $this->domain = 'bitbucket.org';
        $this->repo = (isset($Request->repository->full_name)) ? $Request->repository->full_name : null;
        $this->vcs = (isset($Request->repository->scm)) ? $Request->repository->scm : null;

        /*
         * Detect updated branches
         */

        if (isset($Request->push->changes)) {
            foreach ($Request->push->changes as $change) {
                if (isset($change->new->type) && $change->new->type === 'branch') {
                    if (!in_array($change->new->name, $this->branches)) {
                        $this->branches[] = $change->new->name;
                    }
                }
            }
        }

        /*
         * Detect update
         */

        // $this->update = true;
    }
}
