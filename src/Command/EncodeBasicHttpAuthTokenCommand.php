<?php

namespace Ang3\Bundle\ApiBasicHttpAuthBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class EncodeBasicHttpAuthTokenCommand extends Command
{
    protected static $defaultName = 'security:api:basic-http-auth-token';

    protected function configure(): void
    {
        $this
            ->setDescription('Encode the username and optional password to a valid Basic HTTP authentication token.')
            ->setHelp('Use this command with username as first argument and password as second argument : <comment>php bin/console admin password</comment>')
            ->addArgument('username', InputArgument::REQUIRED, 'The username')
            ->addArgument('password', InputArgument::OPTIONAL, 'The password');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Encoding basic HTTP authentication token');

        /** @var string|null $username */
        $username = $input->getArgument('username') ?: null;
        /** @var string|null $password */
        $password = $input->getArgument('password') ?: null;

        $io->table(['Username', 'Password', 'Token'], [
            [$username, $password, base64_encode(sprintf('%s:%s', $username, $password))],
        ]);

        $io->success('Token generated successfully.');

        return 1;
    }
}
