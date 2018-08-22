<?php
namespace OAF;

use DI\ContainerBuilder;

class App extends \Slim\App
{
    public function __construct(array $definitionFiles = [])
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
        foreach ($definitionFiles as $file) {
            $builder->addDefinitions($file);
        }

        parent::__construct($builder->build());
    }
}