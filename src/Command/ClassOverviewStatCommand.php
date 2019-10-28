<?php

declare(strict_types=1);

/*
 * This file is part of the "PHP Static Analyzer" project.
 *
 * (c) Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Greeflas\StaticAnalyzer\Command;

use Greeflas\StaticAnalyzer\Analyzer\ClassOverviewAnalyzer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Feday2 <feday2@gmail.com>
 */
class ClassOverviewStatCommand extends Command
{
    /**
     * @param void
     */
    protected function configure(): void
    {
        $this
            ->setName('stat:class:overview')
            ->setDescription('Print count of each type properties and methods in class')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Full class name'
            )
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $className = $input->getArgument('name');
        $analyzer = new ClassOverviewAnalyzer($className);
        $result = $analyzer->analyze();
        $output->writeln($result->toConsoleOutput());
    }
}
