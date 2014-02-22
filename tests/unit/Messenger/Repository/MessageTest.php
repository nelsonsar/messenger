<?php

namespace Messenger\Repository;

use Messenger\Test\Database;

class MessageTest extends Database
{
    protected function getDataSet()
    {
        return $this->createXMLDataSet(__DIR__ . "/fixtures/messages.xml");
    }

    public function testShouldRetrieveAllMessagesFromSenderToAReceiver()
    {
        $expectedResult = array(
            array(
                'sender' => 'nelson',
                'receiver' => 'augusto',
                'created_at' => '2014-02-22 14:00:20',
                'message' => 'Te devo uma breja!'
            )
        );

        $sender = 'nelson';
        $receiver = 'augusto';

        $repository = new Message($this->connection);

        $result = $repository->retrieveAll($sender, $receiver);

        $this->assertEquals($expectedResult, $result);
    }

    public function testShouldReturnAnEmptyArrayWhenAreNoMessages()
    {
        $sender = 'test';
        $receiver = 'augusto';

        $repository = new Message($this->connection);

        $result = $repository->retrieveAll($sender, $receiver);

        $this->assertEmpty($result);
    }

    public function testShouldInsertANewRecordIntoMessageTable()
    {
        $sender = 'augusto';
        $receiver = 'nelson';
        $message = 'Vou cobrar!';

        $repository = new Message($this->connection);

        $this->assertTrue($repository->store($sender, $receiver, $message));
    }
}
