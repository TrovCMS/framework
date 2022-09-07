<?php

namespace Trov\Concerns;

use Trov\Utils\AdderException;

trait AbortsCommands
{
    public function abortIf(bool $abort, string $message, $process = null)
    {
        if ($abort) {
            if ($process) {
                throw new AdderException("{$message}\nFailed to run: '{$process->getCommandLine()}'.");
            }
            throw new AdderException("{$message}");
        }
    }
}
