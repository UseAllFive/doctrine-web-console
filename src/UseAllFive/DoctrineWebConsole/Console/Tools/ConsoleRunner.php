<?php

namespace UseAllFive\DoctrineWebConsole\Console\Tools;

use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Doctrine\ORM\Version;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Handles running the Console Tools inside Symfony Console context.
 */
class ConsoleRunner
{
    /**
     * Create a Symfony Console HelperSet
     *
     * @param EntityManager $entityManager
     * @return HelperSet
     */
    public static function createHelperSet(EntityManager $entityManager)
    {
        return new HelperSet(
            array(
                'db' => new ConnectionHelper($entityManager->getConnection()),
                'em' => new EntityManagerHelper($entityManager),
            )
        );
    }

    /**
     * Runs console with the given helperset.
     *
     * @param \Symfony\Component\Console\Helper\HelperSet  $helperSet
     * @param \Symfony\Component\Console\Command\Command[] $commands
     *
     * @return void
     */
    public static function run(HelperSet $helperSet, $commands = array(), OutputInterface $output = null)
    {
        $cli = new Application('Doctrine Command Line Interface', Version::VERSION);
        $cli->setCatchExceptions(true);
        $cli->setHelperSet($helperSet);
        $cli->setAutoExit(false);
        $commands = array_merge(self::getDefaultCommands(), $commands);
        $cli->addCommands($commands);
        $cli->run(null, $output);
    }

    /**
     * @param Application $cli
     *
     * @return void
     */
    public static function getDefaultCommands()
    {
        return array(
            // DBAL Commands
            new \Doctrine\DBAL\Tools\Console\Command\RunSqlCommand(),
            new \Doctrine\DBAL\Tools\Console\Command\ImportCommand(),

            // ORM Commands
            new \Doctrine\ORM\Tools\Console\Command\ClearCache\MetadataCommand(),
            new \Doctrine\ORM\Tools\Console\Command\ClearCache\ResultCommand(),
            new \Doctrine\ORM\Tools\Console\Command\ClearCache\QueryCommand(),
            new \Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand(),
            new \Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand(),
            new \Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand(),
            new \Doctrine\ORM\Tools\Console\Command\EnsureProductionSettingsCommand(),
            new \Doctrine\ORM\Tools\Console\Command\ConvertDoctrine1SchemaCommand(),
            new \Doctrine\ORM\Tools\Console\Command\GenerateRepositoriesCommand(),
            new \Doctrine\ORM\Tools\Console\Command\GenerateEntitiesCommand(),
            new \Doctrine\ORM\Tools\Console\Command\GenerateProxiesCommand(),
            new \Doctrine\ORM\Tools\Console\Command\ConvertMappingCommand(),
            new \Doctrine\ORM\Tools\Console\Command\RunDqlCommand(),
            new \Doctrine\ORM\Tools\Console\Command\ValidateSchemaCommand(),
            new \Doctrine\ORM\Tools\Console\Command\InfoCommand(),
        );
    }
}
