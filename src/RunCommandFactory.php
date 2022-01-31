<?php
declare(strict_types=1);

namespace Boesing\HttpHandlerRunner\Roadrunner;

use Laminas\HttpHandlerRunner\RequestHandlerRunnerInterface;
use Psr\Container\ContainerInterface;

final class RunCommandFactory
{
    public function __invoke(ContainerInterface $container): RunCommand
    {
        return new RunCommand($container->get(RequestHandlerRunnerInterface::class));
    }
}
