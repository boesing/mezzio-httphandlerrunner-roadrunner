<?php

declare(strict_types=1);

namespace Boesing\HttpHandlerRunner\Roadrunner;

use Laminas\HttpHandlerRunner\RequestHandlerRunner;
use Laminas\HttpHandlerRunner\RequestHandlerRunnerInterface;
use Spiral\RoadRunner\Http\PSR7WorkerInterface;
use Spiral\RoadRunner\Worker;
use Spiral\RoadRunner\WorkerInterface;

final class ConfigProvider
{
    /**
     * @return array<mixed>
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    /**
     * @return array<mixed>
     */
    public function getDependencies(): array
    {
        return [
            'factories' => [
                RequestHandlerRunnerInterface::class => RequestHandlerRunnerFactory::class,
                WorkerInterface::class               => static fn (): WorkerInterface => Worker::create(),
                PSR7WorkerInterface::class           => PSR7WorkerFactory::class,
            ],
            'aliases'   => [
                RequestHandlerRunner::class => RequestHandlerRunnerInterface::class,
            ],
        ];
    }
}
