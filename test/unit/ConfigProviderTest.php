<?php

declare(strict_types=1);

namespace BoesingTest\HttpHandlerRunner\Roadrunner;

use Boesing\HttpHandlerRunner\Roadrunner\ConfigProvider;
use Laminas\HttpHandlerRunner\RequestHandlerRunner;
use Laminas\HttpHandlerRunner\RequestHandlerRunnerInterface;
use PHPUnit\Framework\TestCase;
use Spiral\RoadRunner\Http\PSR7WorkerInterface;
use Spiral\RoadRunner\WorkerInterface;

class ConfigProviderTest extends TestCase
{
    private ConfigProvider $configProvider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->configProvider = new ConfigProvider();
    }

    public function testApplicationConfigurationWillContainOverriddenServices(): void
    {
        $config = ($this->configProvider)();
        self::assertArrayHasKey('dependencies', $config);
        self::assertIsArray($config['dependencies']);
        $dependencies = $config['dependencies'];
        self::assertArrayHasKey('factories', $dependencies);
        self::assertIsArray($dependencies['factories']);
        $factories = $dependencies['factories'];
        self::assertArrayHasKey(RequestHandlerRunnerInterface::class, $factories);

        self::assertArrayHasKey('aliases', $dependencies);
        self::assertIsArray($dependencies['aliases']);
        $aliases = $dependencies['aliases'];
        self::assertArrayHasKey(RequestHandlerRunner::class, $aliases);
        self::assertEquals(RequestHandlerRunnerInterface::class, $aliases[RequestHandlerRunner::class]);
    }

    public function testApplicationConfigurationWillContainRoadrunnerFactories(): void
    {
        $config = ($this->configProvider)();
        self::assertArrayHasKey('dependencies', $config);
        self::assertIsArray($config['dependencies']);
        $dependencies = $config['dependencies'];
        self::assertArrayHasKey('factories', $dependencies);
        self::assertIsArray($dependencies['factories']);
        $factories = $dependencies['factories'];
        self::assertArrayHasKey(WorkerInterface::class, $factories);
        self::assertArrayHasKey(PSR7WorkerInterface::class, $factories);
    }
}
