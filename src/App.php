<?php
namespace OAF;

use DI\ContainerBuilder;
use OAF\Middleware\AcceptMiddleware;
use Symfony\Console\Application\Application;

class App extends \Slim\App
{
    public function __construct()
    {
        $builder = new ContainerBuilder;

        // We want auto wiring, but annotations cluters up the
        // doc comments and is less implicit than type hints
        $builder->useAutowiring(true);
        $builder->useAnnotations(false);

        // Register config/service files
        $builder->addDefinitions(__DIR__ . '/../config/slim_services.php');
        $builder->addDefinitions(__DIR__ . '/../config/services.php');

        // Load up custom container definitions. These definitions
        // are defined by the end user and contains application specific
        // dependencies
        $builder = $this->extendContainer($builder);

        parent::__construct($builder->build());

        // Register routes after container exists, this will ensure
        // that middleware gets injected properly
        $this->registerRoutes();
    }

    protected function extendContainer(ContainerBuilder $builder)
    {
        return $builder;
    }

    protected function registerRoutes()
    {
        return false;
    }

    protected function registerCommands(Application $app)
    {
        return $app;
    }

    public function cli()
    {
        $app = $this->getContainer()->get(Application::class);

        $app = $this->registerCommands($app);

        return $app->run();
    }
}