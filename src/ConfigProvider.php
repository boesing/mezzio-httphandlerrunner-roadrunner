<?php

declare(strict_types=1);

namespace Boesing\HttpHandlerRunner\Roadrunner;

use Boesing\HttpHandlerRunner\Roadrunner\RequestHandlerRunner;
use Laminas\HttpHandlerRunner\RequestHandlerRunnerInterface;
use Spiral\RoadRunner\Http\PSR7WorkerInterface;
use Spiral\RoadRunner\Worker;
use Spiral\RoadRunner\WorkerInterface;

final class ConfigProvider
{
    /**
     * @return array{dependencies:array<string,mixed>}&array<string,mixed>
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'laminas-cli'  => [
                'commands' => [
                    RunCommand::NAME => RunCommand::class,
                ],
            ],
        ];
    }

    /**
     * @return array<string,mixed>
     */
    public function getDependencies(): array
    {
        return [
            'factories' => [
                RequestHandlerRunner::class => RequestHandlerRunnerFactory::class,
                WorkerInterface::class      => static fn(): WorkerInterface => Worker::create(),
                PSR7WorkerInterface::class  => PSR7WorkerFactory::class,
                RunCommand::class           => RunCommandFactory::class,
            ],
            'aliases'   => [
                RequestHandlerRunnerInterface::class => RequestHandlerRunner::class,
            ],
        ];
    }
}
