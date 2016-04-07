<?php

namespace ShopwarePlugins\FrMigrations\Commands;

use Shopware\Commands\ShopwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateUpCommand extends ShopwareCommand
{
    /**
     * @var \Shopware_Components_Plugin_Bootstrap $bootstrap
     */
    protected $bootstrap;

    /**
     * Constants for migration tool
     */
    const MIGRATION_USERNAME = 'dev';
    const MIGRATION_PASSWORD = 'dev';
    const MIGRATION_HOST = 'db';
    const MIGRATION_SCHEMA = 'shopware';
    const MIGRATION_SHOPPATH = '/var/www/html';
    const MIGRATION_SUFFIX = 'fr';

    /**
     * @param \Shopware_Components_Plugin_Bootstrap $bootstrap
     */
    public function __construct($bootstrap)
    {
        $this->bootstrap = $bootstrap;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('framily:migrations:up')
            ->setDescription('Apply migrations found in _sql folder')
            ->addArgument('username', InputArgument::OPTIONAL, 'DB username (Default: '.self::MIGRATION_USERNAME.')')
            ->addArgument('password', InputArgument::OPTIONAL, 'DB password (Default: '.self::MIGRATION_PASSWORD.')')
            ->addArgument('host', InputArgument::OPTIONAL, 'DB host (Default: '.self::MIGRATION_HOST.')')
            ->addArgument('dbname', InputArgument::OPTIONAL, 'DB schema (Default: '.self::MIGRATION_SCHEMA.')')
            ->addArgument(
                'shoppath',
                InputArgument::OPTIONAL,
                'Shopware doc root (Default: '.self::MIGRATION_SHOPPATH.')'
            )
            ->setHelp("The <info>%command.name%</info> applies the migrations to the latest revision.");
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dbUsername = $input->getArgument('username') ?: self::MIGRATION_USERNAME;
        $dbPassword = $input->getArgument('password') ?: self::MIGRATION_PASSWORD;
        $dbHost = $input->getArgument('host') ?: self::MIGRATION_HOST;
        $dbSchema = $input->getArgument('host') ?: self::MIGRATION_SCHEMA;
        $shopPath = $input->getArgument('shoppath') ?: self::MIGRATION_SHOPPATH;
        $migrationPath = $shopPath . '/_sql/migrations';
        $migrationSuffix = self::MIGRATION_SUFFIX;

        $shellReturn = shell_exec(<<<EOD
php {$this->bootstrap->Path()}\\vendor/b3nl/sw-migrations/build/ApplyDeltas.php \
--username="$dbUsername" \
--password="$dbPassword" \
--host="$dbHost" \
--dbname="$dbSchema" \
--shoppath=$shopPath \
--tablesuffix=$migrationSuffix \
--migrationpath=$migrationPath \
--mode=update
EOD
        );
        $output->write($shellReturn);
    }
}
