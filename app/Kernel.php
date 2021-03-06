<?php

declare(strict_types=1);

namespace TuiMusement\App;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class Kernel
{
    private ContainerBuilder $container;

    public function __construct(string $env = 'prod')
    {
        $this->container = new ContainerBuilder();
        $loader = new YamlFileLoader($this->container, new FileLocator(__DIR__ . '/../config'));
        $loader->load('services.yml');
        $loader->load('parameters.yml');
        if ('test' === $env) {
            $loader->load('services_test.yml');
        }
        $this->container->compile(true);
    }

    public static function test(): Kernel
    {
        return new Kernel('test');
    }

    public function app(): Application
    {
        /** @var Application $app */
        $app = $this->container->get(Application::class);

        return $app;
    }

    public function run(): void
    {
        $this->app()->run();
    }
}
