<?php

namespace AutoDeploy;

use AutoDeploy\Origin\Requester;
use AutoDeploy\Vcs\System;

/**
 * Auto deploy handler.
 */
class Handler
{
    private $payload;
    private $repositories;

    /**
     * Construct.
     */
    final public function __construct()
    {
        $this->repositories = [];
    }

    final public function info($message)
    {
        echo $message.PHP_EOL;
    }

    final public function error($message)
    {
        echo $message.PHP_EOL;
    }

    final public function warning($message)
    {
        echo $message.PHP_EOL;
    }

    /**
     * Add repository.
     *
     * @param string $full_name Full repo name
     * @param array  $options   Options
     */
    final public function add($full_name, array $options = [])
    {
        /*
         * Default options
         */

        $defaults = [
            'remote' => 'origin',
            'branch' => 'master',
            'folder' => null,
            'destination' => null,
            'vcs' => 'git',
            'vcs_path' => 'git',
        ];

        $options = array_merge($defaults, $options);

        /*
         * Validate destination
         */

        if (strlen($options['folder']) === 0) {
            die('Missing folder name');
        }

        if (!is_writable($options['destination'])) {
            die('Destination is not writable for '.Cli::exec('whoami'));
        }

        if (!is_writable($options['destination'])) {
            die('Destination is not writable for '.Cli::exec('whoami'));
        }

        /*
         * Add repository
         */

        $this->repositories[$full_name][] = $options;
    }

    /**
     * Execute handler.
     */
    final public function register()
    {
        $Requester = new Requester();
        $ServiceProvider = $Requester->ServiceProvider;

        /*
         * Validations
         */

        if (is_null($ServiceProvider)) {
            die('Webhook comes from an unkown service provider');
        }

        if (!isset($this->repositories[$ServiceProvider->repo])) {
            die('Repository '.$ServiceProvider->repo.' isn\'t register');
        }

        if ($ServiceProvider->update === false) {
            $this->info('Nothing to update');
        }

        $selected = null;
        foreach ($this->repositories as $repo => $options) {
            if ($ServiceProvider->repo === $repo) {
                foreach ($options as $key => $config) {
                    if (
                        $ServiceProvider->hasBranch($config['branch']) &&
                        $config['vcs'] === $ServiceProvider->vcs
                    ) {
                        $config['repo'] = $repo;
                        $selected = $config;
                    }
                }
            }
        }

        if (is_null($selected)) {
            die('No configuration found');
        }

        /*
         * Pull changes
         */

        $this->info('Pulling '.$selected['repo'].' on branch '.$selected['branch']);

        $System = new System($selected['vcs'], $selected['vcs_path']);
        $System->update(
            $ServiceProvider->domain,
            $ServiceProvider->repo,
            $selected['remote'],
            $selected['branch'],
            $selected['destination'].DIRECTORY_SEPARATOR.$selected['folder']
        );
    }
}
