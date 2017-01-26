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

        $this->repositories[$full_name] = $options;
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

        $options = $this->repositories[$ServiceProvider->repo];

        if (!$ServiceProvider->hasBranch($options['branch'])) {
            die('Branch '.$options['branch'].' was not updated');
        }

        if ($options['vcs'] !== $ServiceProvider->vcs) {
            die('VCS mismatch expected '.$options['vcs'].' received '.$ServiceProvider->vcs);
        }

        if ($ServiceProvider->update === false) {
            $this->info('Nothing to update');
        }

        /*
         * Pull changes
         */

        $this->info('Pulling '.$ServiceProvider->repo);

        $System = new System($options['vcs'], $options['vcs_path']);
        $System->update(
            $ServiceProvider->domain,
            $ServiceProvider->repo,
            $options['remote'],
            $options['branch'],
            $options['destination'].DIRECTORY_SEPARATOR.$options['folder']
        );
    }
}
