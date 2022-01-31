<?php

declare(strict_types=1);

namespace Boesing\HttpHandlerRunner\Roadrunner;

use Mezzio\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class RunCommand extends Command
{
    public const NAME = 'roadrunner:run';

    private Application $application;

    public function __construct(Application $application)
    {
        parent::__construct(self::NAME);
        $this->application = $application;
    }

    protected function configure(): void
    {
        $this->setHelp('By executing this command, your application will start running with roadrunner.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->application->run();

        return self::SUCCESS;
    }
}
