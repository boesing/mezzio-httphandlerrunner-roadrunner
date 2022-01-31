<?php

declare(strict_types=1);

namespace Boesing\HttpHandlerRunner\Roadrunner;

use Laminas\HttpHandlerRunner\RequestHandlerRunnerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Spiral\RoadRunner\Http\PSR7WorkerInterface;
use Spiral\RoadRunner\WorkerInterface;
use Throwable;

final class RequestHandlerRunner implements RequestHandlerRunnerInterface
{
    public function __construct(
        private readonly PSR7WorkerInterface $psr7Worker,
        private readonly WorkerInterface $worker,
        private readonly RequestHandlerInterface $requestHandler
    ) {
    }

    public function run(): void
    {
        while ($request = $this->psr7Worker->waitRequest()) {
            try {
                $response = $this->requestHandler->handle($request);
                $this->psr7Worker->respond($response);
            } catch (Throwable $throwable) {
                $this->worker->error((string) $throwable);
            }
        }
    }
}
