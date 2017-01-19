<?php

namespace AutoDeploy\Vcs;

use AutoDeploy\Cli;

/**
 * Git wrapper.
 */
class Git
{
    private $bin;

    /**
     * Construct.
     *
     * @param string $vcs_path Binary path
     */
    final public function __construct($vcs_path)
    {
        $this->bin = $vcs_path;
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
        /*
         * Clone or fecth from repository
         */

        if (!is_dir($destination.DIRECTORY_SEPARATOR.'.git')) {
            echo 'Cloning repository'.PHP_EOL;

            $command = $this->bin.' clone git@'.$domain.':'.$repo.'.git '.$destination;
            $out = Cli::exec($command);

            if (strlen($out) === 0) {
                die('Failed: check access for '.exec('whoami'));
            }

            echo 'Success'.PHP_EOL;
        } else {
            $success = false;

            $out = Cli::exec('cd '.$destination.' && '.$this->bin.' fetch');
            $out = Cli::exec('cd '.$destination.' && '.$this->bin.' pull');

            if (strpos($out, 'Already up-to-date') !== false) {
                $success = true;
            } elseif (strpos($out, 'Fast-forward') !== false) {
                $success = true;
            } elseif (strlen($out) === 0) {
                die('Failed: check access for '.exec('whoami'));
            }

            $head = Cli::exec('cd '.$destination.' && '.$this->bin.' rev-parse --short HEAD');

            if ($success) {
                echo 'Success head set to '.$head.PHP_EOL;
            } else {
                echo 'Failed head left to '.$head.PHP_EOL;
            }
        }
    }
}
