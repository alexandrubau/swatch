<?php

namespace Swatch;

use Swatch\Command\ReportCommand;
use Symfony\Component\Console\Application as BaseApplication;

/**
 * Class Application
 *
 * @author Alexandru Bau <alexandru.bau@gmail.com>
 */
class Application extends BaseApplication
{
    /**
     * @var string
     */
    private static $logo = '                        __       __  
   ______      ______ _/ /______/ /_ 
  / ___/ | /| / / __ `/ __/ ___/ __ \
 (__  )| |/ |/ / /_/ / /_/ /__/ / / /
/____/ |__/|__/\__,_/\__/\___/_/ /_/ 
';

    /**
     * Application constructor.
     */
    public function __construct()
    {
        parent::__construct(Swatch::NAME, Swatch::VERSION);
    }

    /**
     * @inheritdoc
     */
    public function getHelp()
    {
        return self::$logo . PHP_EOL . parent::getHelp();
    }

    /**
     * @inheritdoc
     */
    protected function getDefaultCommands()
    {
        $commands = array_merge(parent::getDefaultCommands(), [
            new ReportCommand()
        ]);

        return $commands;
    }
}