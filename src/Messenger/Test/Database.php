<?php

namespace Messenger\Test;

abstract class Database extends \PHPUnit_Extensions_Database_TestCase
{
    protected $connection = null;

    public function getConnection()
    {
        $databasePath = __DIR__ . "/../../../resources/messenger_test.db";
        $config = new \Doctrine\DBAL\Configuration();

        $connectionParams = array(
            'path' => $databasePath,
            'driver' => 'pdo_sqlite',
        );

        $this->connection = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);

        return $this->createDefaultDBConnection($this->connection->getWrappedConnection(), "messenger_test");
    }
}
