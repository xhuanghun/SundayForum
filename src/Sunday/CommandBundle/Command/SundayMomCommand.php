<?php

namespace Sunday\CommandBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Console\Style\SymfonyStyle;

class SundayMomCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sunday:mom')
            ->setDescription('...')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $cliOutput = $output);

        $argument = $input->getArgument('argument');

        if ($input->getOption('option')) {
            // ...
        }

        if (!extension_loaded('pcntl')) {
            $output->writeln('no pcntl');
        }

        $pid = pcntl_fork();

        if ($pid < 0) {
            $io->error('Unable to start the processA.');

            return 1;
        }

        if ($pid > 0) {
            $io->success('new processA created');

            return;
        }

        if (posix_setsid() < 0) {
            $io->error('Unable to set the child process as session leader');

            return 1;
        }

        $command = 'php /Users/tony/Sites/SundayForum/SundayForum/app/console sunday:processA';
        $process = new Process($command);

        if(null === $process)
        {
            $output->writeln('can not create process');
        }
        $process->disableOutput();
        $process->start();
    }

}
