<?php

namespace AutoDeploy;

class Rsync
{
    /**
     * Create link.
     *
     * @param string $source      Source repository
     * @param string $destination Destination
     * @param string $filter      Filters
     */
    final public static function link($source, $destination, $filter)
    {
        $command = 'rsync -a --delete';
        if (is_file($filter)) {
            $command .= ' --include-from='.$filter;
        }
        $command .= ' '.$source.'/ '.$destination;

        echo 'Executing sudo -Hu '.exec('whoami').' '.$command.PHP_EOL;
        Cli::exec($command);
    }
}
