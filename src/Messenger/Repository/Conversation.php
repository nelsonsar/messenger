<?php

namespace Messenger\Repository;

use Doctrine\DBAL\Connection;

class Conversation
{
    private $connection = null;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function retrieveAll($username)
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $asReceiver = $queryBuilder->select('sender AS conversation')
            ->from('messages', 'm')
            ->where('receiver = ?')
            ->setParameter(0, $username)
            ->execute()
            ->fetchAll();

        $asSender = $queryBuilder->select('receiver AS conversation')
            ->from('messages', 'm')
            ->where('sender = ?')
            ->setParameter(0, $username)
            ->execute()
            ->fetchAll();

        $all = array_merge($asReceiver, $asSender);

        return array_unique($all);
    }
}
