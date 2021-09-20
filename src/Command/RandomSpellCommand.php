<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:random-spell',
    description: 'Cast a random spell',
)]
class RandomSpellCommand extends Command
{
    /**
     * Setup our command
     */
    protected function configure(): void
    {
        $this
            ->addArgument('your-name', InputArgument::OPTIONAL, 'Your name')
            ->addOption('yell', null, InputOption::VALUE_NONE, 'Yell?')
        ;
    }

    /**
     * Print a random spell message on the command line inteface
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $yourName = $input->getArgument('your-name');

        if ($yourName) {
            $io->note(sprintf('Hi: %s!', $yourName));
        }

        $spells = [
            'alohomora',
            'confundo',
            'engorgio',
            'expecto patronum',
            'expelliarmus',
            'impedimenta',
            'reparo',
        ];

        $spell = $spells[array_rand($spells)];


        if ($input->getOption('yell')) {
            $spell = strtoupper($spell);
        }

        $io->success($spell);

        return Command::SUCCESS;
    }
}
