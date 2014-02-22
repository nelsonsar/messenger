<?php

namespace Messenger\Repository;

use Doctrine\DBAL\Connection;

class Message
{
    private $connection = null;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function retrieveAll($sender, $receiver)
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        return $queryBuilder->select('*')
            ->from('messages', 'm')
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->andX(
                        $queryBuilder->expr()->eq('sender', ':sender'),
                        $queryBuilder->expr()->eq('receiver', ':receiver')
                    ),
                    $queryBuilder->expr()->andX(
                        $queryBuilder->expr()->eq('sender', ':receiver'),
                        $queryBuilder->expr()->eq('receiver', ':sender')
                    )
                )
            )
            ->orderBy('created_at', 'ASC')
            ->setParameters(array("sender" => $sender, "receiver" => $receiver))
            ->execute()
            ->fetchAll();
    }

    public function store($sender, $receiver, $message)
    {
        $creationDate = new \DateTime;

        return (bool) $this->connection->insert('messages', array(
            'sender' => $sender,
            'receiver' => $receiver,
            'created_at' => $creationDate->format('Y-m-d H:i:s'),
            'message' => $message
        ));
    }
}
