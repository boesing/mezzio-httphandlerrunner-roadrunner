<?php

declare(strict_types=1);

namespace Boesing\HttpHandlerRunner\Roadrunner;

use Mezzio\ApplicationPipeline;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Spiral\RoadRunner\Http\PSR7WorkerInterface;
use Spiral\RoadRunner\WorkerInterface;

use function assert;

final class RequestHandlerRunnerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerRunner
    {
        /**
         * @psalm-suppress UndefinedClass Mezzio has this pseudo-class which actually does not exist
         *                                to fetch the assembled application pipeline via PSR-11 container
         */
        $requestHandler = $container->get(ApplicationPipeline::class);
        assert($requestHandler instanceof RequestHandlerInterface);

        return new RequestHandlerRunner(
            $container->get(PSR7WorkerInterface::class),
            $container->get(WorkerInterface::class),
            $requestHandler
        );
    }
}
