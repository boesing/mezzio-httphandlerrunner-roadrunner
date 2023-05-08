<?php

declare(strict_types=1);

namespace BoesingTest\HttpHandlerRunner\Roadrunner;

use Boesing\HttpHandlerRunner\Roadrunner\ConfigProvider;
use Boesing\HttpHandlerRunner\Roadrunner\RequestHandlerRunner;
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
        $config       = ($this->configProvider)();
        $dependencies = $config['dependencies'];
        self::assertArrayHasKey('factories', $dependencies);
        self::assertIsArray($dependencies['factories']);
        $factories = $dependencies['factories'];
        self::assertArrayHasKey(RequestHandlerRunner::class, $factories);

        self::assertArrayHasKey('aliases', $dependencies);
        self::assertIsArray($dependencies['aliases']);
        $aliases = $dependencies['aliases'];
        self::assertArrayHasKey(RequestHandlerRunnerInterface::class, $aliases);
        self::assertEquals(RequestHandlerRunner::class, $aliases[RequestHandlerRunnerInterface::class]);
    }

    public function testApplicationConfigurationWillContainRoadrunnerFactories(): void
    {
        $config       = ($this->configProvider)();
        $dependencies = $config['dependencies'];
        self::assertArrayHasKey('factories', $dependencies);
        self::assertIsArray($dependencies['factories']);
        $factories = $dependencies['factories'];
        self::assertArrayHasKey(WorkerInterface::class, $factories);
        self::assertArrayHasKey(PSR7WorkerInterface::class, $factories);
    }
}
