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
