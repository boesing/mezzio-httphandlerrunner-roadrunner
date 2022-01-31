<?php
declare(strict_types=1);

namespace Boesing\HttpHandlerRunner\Roadrunner;

use Laminas\HttpHandlerRunner\RequestHandlerRunnerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class RunCommand extends Command
{
    public const NAME = 'roadrunner:run';

    private RequestHandlerRunnerInterface $runner;

    public function __construct(RequestHandlerRunnerInterface $runner)
    {
        parent::__construct(self::NAME);
        $this->runner = $runner;
    }

    protected function configure(): void
    {
        $this->setHelp('By executing this command, your application will start running with roadrunner.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->runner->run();

        return self::SUCCESS;
    }
}
