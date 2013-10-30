<?php

namespace UseAllFive\DoctrineWebConsole;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\HttpFoundation\Request;
use UseAllFive\DoctrineWebConsole\Console\Tools\ConsoleRunner;

class ConsoleControllerProvider implements ControllerProviderInterface
{
    protected $customCommands;

    /**
     * Pass custom doctrine commands to this method
     * to enable them in the web console.
     *
     * @param array $customCommands Custom console commands
     */
    public function __construct(array $customCommands = array())
    {
        $this->customCommands = $customCommands;
    }

    /**
     * {@inheritdoc}
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/', function (Application $app) {
            return file_get_contents(__DIR__ . '/Resources/views/Default/index.html');
        });

        $controllers->post('/execute', function (Request $request, Application $app) {
            $helperSet = ConsoleRunner::createHelperSet($app['orm.em']);
            ob_start();
            $output = new StreamOutput(fopen("php://output", "w"));
            $_SERVER['argv'] = explode(' ', "doctrine ".$request->request->get('command'));
            $_SERVER['argc'] = count($_SERVER['argv']);
            ConsoleRunner::run($helperSet, $this->customCommands, $output);
            return ob_get_clean();
        });

        return $controllers;
    }
}
