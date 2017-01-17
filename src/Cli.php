<?php

namespace AutoDeploy;

/**
 * Comand line execution wrapper.
 */
class Cli
{
    /**
     * Execute command.
     *
     * @param string $command Command
     */
    final public static function exec($command)
    {
        return trim(shell_exec($command));
    }
}
