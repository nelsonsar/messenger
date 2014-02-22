<?php

namespace Messenger\Repository;

use Messenger\Test\Database;

class ConversationTest extends Database
{
    protected function getDataSet()
    {
        return $this->createXMLDataSet(__DIR__ . "/fixtures/messages.xml");
    }

    public function testShouldGetConversationWithoutDuplicateRecords()
    {
        $expectedResult = array(
            array(
                'conversation' => 'augusto',
            )
        );

        $repository = new Conversation($this->connection);

        $result = $repository->retrieveAll('nelson');

        $this->assertEquals($expectedResult, $result);
    }
}
