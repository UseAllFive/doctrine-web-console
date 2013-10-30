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
    protected $commandPath;

    protected $customCommands;

    /**
     * If a command path is not specified, commands will be executed
     * in the web directory's path.
     *
     * @param array $commandPath Path in which commands will be executed
     * @param array $customCommands Extra console commands
     */
    public function __construct($commandPath = null, array $customCommands = array())
    {
        $this->commandPath = $commandPath;
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
            if (null !== $this->commandPath) {
                if (@!is_dir($this->commandPath)) {
                    throw new \InvalidArgumentException(
                        "Unable to change directories into the specified command directory"
                    );
                }
                chdir($this->commandPath);
            }
            ConsoleRunner::run($helperSet, $this->customCommands, $output);
            return ob_get_clean();
        });

        return $controllers;
    }
}
