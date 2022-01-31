<?php

declare(strict_types=1);

namespace BoesingTest\HttpHandlerRunner\Roadrunner;

use Boesing\HttpHandlerRunner\Roadrunner\RequestHandlerRunner;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Spiral\RoadRunner\Http\PSR7WorkerInterface;
use Spiral\RoadRunner\WorkerInterface;
use Throwable;

final class RequestHandlerRunnerTest extends TestCase
{
    private RequestHandlerRunner $runner;

    private RequestHandlerInterface & MockObject $requestHandler;

    private PSR7WorkerInterface & MockObject $psr7Worker;

    private WorkerInterface & MockObject $worker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->psr7Worker     = $this->createMock(PSR7WorkerInterface::class);
        $this->worker         = $this->createMock(WorkerInterface::class);
        $this->requestHandler = $this->createMock(RequestHandlerInterface::class);
        $this->runner         = new RequestHandlerRunner(
            $this->psr7Worker,
            $this->worker,
            $this->requestHandler
        );
    }

    public function testRunCanExecuteMultipleRequests(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);

        $this->psr7Worker
            ->expects(self::exactly(2))
            ->method('waitRequest')
            ->willReturnOnConsecutiveCalls($request, null);

        $this->psr7Worker
            ->expects(self::once())
            ->method('respond')
            ->with();

        $this->runner->run();
    }

    public function testRunPassesServerRequestToRequestHandler(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);

        $this->psr7Worker
            ->expects(self::exactly(2))
            ->method('waitRequest')
            ->willReturnOnConsecutiveCalls($request, null);

        $response = $this->createMock(ResponseInterface::class);

        $this->requestHandler
            ->expects(self::once())
            ->method('handle')
            ->with($request)
            ->willReturn($response);

        $this->psr7Worker
            ->expects(self::once())
            ->method('respond')
            ->with($response);

        $this->runner->run();
    }

    public function testRunPassesThrowablesToWorkerInterface(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);

        $this->psr7Worker
            ->expects(self::exactly(2))
            ->method('waitRequest')
            ->willReturnOnConsecutiveCalls($request, null);

        $throwable = $this->createMock(Throwable::class);

        $this->requestHandler
            ->expects(self::once())
            ->method('handle')
            ->willThrowException($throwable);

        $this->psr7Worker
            ->expects(self::never())
            ->method('respond');

        $this->worker
            ->expects(self::once())
            ->method('error');

        $this->runner->run();
    }
}
