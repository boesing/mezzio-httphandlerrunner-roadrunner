<?php

declare(strict_types=1);

namespace BoesingTest\HttpHandlerRunner\Roadrunner;

use Boesing\HttpHandlerRunner\Roadrunner\ConfigProvider;
use Boesing\HttpHandlerRunner\Roadrunner\RequestHandlerRunner;
use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ServiceManager\ServiceManager;
use Mezzio\Application;
use Mezzio\Router\RouteCollector;
use Mezzio\Router\RouteCollectorInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use ReflectionProperty;
use Spiral\RoadRunner\WorkerInterface;

final class MezzioApplicationFactoryTest extends TestCase
{
    private ContainerInterface $container;

    protected function setUp(): void
    {
        parent::setUp();
        $this->container = $this->createContainer();
    }

    private function createContainer(): ContainerInterface
    {
        $aggregator = new ConfigAggregator([
            \Mezzio\ConfigProvider::class,
            \Laminas\HttpHandlerRunner\ConfigProvider::class,
            \Laminas\Diactoros\ConfigProvider::class,
            ConfigProvider::class,
        ]);

        $config = $aggregator->getMergedConfig();
        self::assertArrayHasKey('dependencies', $config);
        self::assertIsArray($config['dependencies']);
        /**
         * @psalm-suppress InvalidArgument laminas-servicemanager contains a union array type which is
         *                                           not (yet) supported by psalm
         */
        $container = new ServiceManager($config['dependencies']);
        $container->setService('config', $config);
        $container->setAlias('Config', 'config');
        $container->setService(RouteCollector::class, $this->createMock(RouteCollectorInterface::class));
        $container->setService(WorkerInterface::class, $this->createMock(WorkerInterface::class));

        return $container;
    }

    public function testApplicationWillUseRoaderunnerRequestHandler(): void
    {
        $application = $this->container->get(Application::class);
        self::assertInstanceOf(Application::class, $application);

        $runner = (new ReflectionProperty(Application::class, 'runner'))->getValue($application);
        self::assertInstanceOf(RequestHandlerRunner::class, $runner);
    }
}
