<?php

namespace Swatch\Command;

use Swatch\Builder\Builder;
use Swatch\Config;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ReportCommand
 */
class ReportCommand extends Command
{
    const DEFAULT_CONFIG = 'swatch.json';

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('report')
            ->setDescription('Sends a report.')
            ->addArgument('config', InputArgument::OPTIONAL, 'The path to the config file.', static::DEFAULT_CONFIG)
        ;
    }

    /**
     * @inheritdoc
     *
     * @throws \ReflectionException
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getArgument('config');

        $config = new Config($path);

        $builder = new Builder($config);

        $swatch = $builder->build();

        $swatch->report();

        $output->writeln('Report complete.');
    }
}