<?php

namespace Insolis\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Application;
use Silex\Api\BootableProviderInterface;

class RepositoryServiceProvider implements ServiceProviderInterface, BootableProviderInterface
{
    public function register(Container $app)
    {
    }

    public function boot(Application $app)
    {
        foreach ($app['repository.repositories'] as $label => $class) {
            $app[$label] = function ($app) use ($class) {
                return new $class($app['db']); 
            };
        }
    }
}
