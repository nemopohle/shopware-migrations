<?php

use Doctrine\Common\Collections\ArrayCollection;
use ShopwarePlugins\FrMigrations\Commands\MigrateUpCommand;

class Shopware_Plugins_Frontend_FrMigrations_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    public function getLabel()
    {
        return 'Framily Migrations Command';
    }

    public function getVersion()
    {
        return "1.0.0";
    }

    public function install()
    {
        $this->subscribeEvent(
            'Shopware_Console_Add_Command',
            'onAddConsoleCommand'
        );

        return true;
    }

    public function getInfo()
    {
        return [
            'version' => $this->getVersion(),
            'copyright' => 'Copyright framily GmbH',
            'author' => 'framily GmbH',
            'label' => $this->getLabel(),
            'description' => 'Erweitert die Shopware-Konsolenanwendung um ein Migrations-Command',
            'support' => '',
            'link' => 'http://www.framily.de',
            'changes' => [
                '1.0.0' => [
                    'releasedate' => '2016-04-06',
                    'lines' => [
                        'Erstes Release'
                    ]
                ]
            ],
        ];
    }

    public function afterInit()
    {
        $this->get('Loader')->registerNamespace(
            'ShopwarePlugins\\FrMigrations',
            $this->Path() . '/'
        );
    }

    /**
     * @return ArrayCollection
     */
    public function onAddConsoleCommand()
    {
        require_once $this->Path() . '/vendor/autoload.php';

        return new ArrayCollection([
                new MigrateUpCommand($this)
        ]);
    }
}
