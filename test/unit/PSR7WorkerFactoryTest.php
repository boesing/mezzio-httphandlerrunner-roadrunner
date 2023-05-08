<?php

declare(strict_types=1);

namespace BoesingTest\HttpHandlerRunner\Roadrunner;

use Boesing\HttpHandlerRunner\Roadrunner\PSR7WorkerFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Spiral\RoadRunner\Http\PSR7Worker;
use Spiral\RoadRunner\WorkerInterface;

final class PSR7WorkerFactoryTest extends TestCase
{
    private PSR7WorkerFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->factory = new PSR7WorkerFactory();
    }

    public function testWillRequireExpectedServicesFromContainer(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $container
            ->expects(self::exactly(4))
            ->method('get')
            ->willReturnCallback(function (string $argument): mixed {
                $mapping = [
                    WorkerInterface::class               => $this->createMock(WorkerInterface::class),
                    ServerRequestFactoryInterface::class => $this->createMock(ServerRequestFactoryInterface::class),
                    StreamFactoryInterface::class        => $this->createMock(StreamFactoryInterface::class),
                    UploadedFileFactoryInterface::class  => $this->createMock(UploadedFileFactoryInterface::class),
                ];

                self::assertArrayHasKey($argument, $mapping);
                return $mapping[$argument];
            });

        self::assertInstanceOf(PSR7Worker::class, ($this->factory)($container));
    }
}
