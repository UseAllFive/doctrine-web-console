Doctrine Web Console for Silex
==============================
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/2bb3c50e-5035-4a76-bcf9-9aaf722aea3e/small.png)](https://insight.sensiolabs.com/projects/2bb3c50e-5035-4a76-bcf9-9aaf722aea3e)

The doctrine web console allows one to execute doctrine console commands via web browser. This is especially useful when one is required to execute commands in an enviroment which does not allow shell access (e.g. [Google App Engine](https://developers.google.com/appengine/)).

## Installation
Via composer:
```bash
php composer.phar require useallfive/doctrine-web-console dev-master
```
Mount the controller provider to the /console path.
```php
<?php
$app = new Silex\Application();
// ...
$app->mount(
        '/console',
        new \UseAllFive\DoctrineWebConsole\ConsoleControllerProvider()
    )
;
$app->run();
```
You're all set! Visit the the `/console` url of your site to use.

## Specifying a command path
If a command path is not specified, commands will be executed in the current working directory of the console controller (the web directory of your project). While this is fine for most commands, it's not ideal for a command which requires a path. You can specify a path in the first argument of the `ConsoleControllerProvider` constructor.
```php
<?php
$app = new Silex\Application();
// ...
$app->mount(
        '/console',
        new \UseAllFive\DoctrineWebConsole\ConsoleControllerProvider(__DIR__)
    )
;
$app->run();
```

## Adding commands
Adding your own Doctrine commands to the web console is trivial. We'll add the [Doctrine DataFixtures Command](https://github.com/UseAllFive/doctrine-data-fixtures-command) for this example.

The second argument of the `ConsoleControllerProvider` constructor takes an array of `Symfony\Component\Console\Command\Command` instances which is then passed to the Doctrine `ConsoleRunner`.
```php
<?php
$app = new Silex\Application();
// ...
$app->mount(
        '/console',
        new \UseAllFive\DoctrineWebConsole\ConsoleControllerProvider(
            __DIR__,
            array(
                new \UseAllFive\Command\LoadDataFixturesDoctrineCommand(),
            )
        )
    )
;
$app->run();
```
