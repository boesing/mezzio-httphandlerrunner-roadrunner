<?php

declare(strict_types=1);

namespace Boesing\HttpHandlerRunner\Roadrunner;

use Mezzio\Application;
use Psr\Container\ContainerInterface;

final class RunCommandFactory
{
    public function __invoke(ContainerInterface $container): RunCommand
    {
        return new RunCommand($container->get(Application::class));
    }
}
