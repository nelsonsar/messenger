<?php

namespace Messenger\Service\RabbitMQ;

class HttpClientTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldReturnAListOfAllQueuesFromAVirtualHost()
    {
        $clientMock = $this->getMock('Guzzle\Http\Client');
        $apiAddress = 'http://localhost:15672';
        $user = 'guest';
        $password = 'guest';
        $virtualHost = '/';

        $expectedResult = array(
            'testuser',
            'nelson',
            'abcdfg',
        );

        $responseMockJsonCallResult = array(
            array("name" => "testuser", "vhost" => "/"),
            array("name" => "nelson", "vhost" => "/"),
            array("name" => "abcdfg", "vhost" => "/")
        );

        $responseMock = $this->getMockBuilder('Guzzle\Http\Message\Response')
            ->setConstructorArgs(array(
                200, 
                null, 
                '[{"name":"testuser", "vhost":"/"}, {"name":"nelson", "vhost":"/"}, {"name":"abcdfg", "vhost":"/"}]'
            ))
            ->getMock();

        $responseMock->expects($this->once())
            ->method('json')
            ->will($this->returnValue($responseMockJsonCallResult));

        $requestMock = $this->getMockBuilder('Guzzle\Http\Message\Request')
            ->setConstructorArgs(array('GET', $apiAddress . '/api/queues/%2f'))
            ->getMock();

        $requestMock->expects($this->once())->method('setAuth')->with($user, $password)->will($this->returnSelf());

        $requestMock->expects($this->once())->method('send')->will($this->returnValue($responseMock));

        $clientMock->expects($this->once())->method('setBaseUrl')->with($apiAddress)->will($this->returnSelf());
        $clientMock->expects($this->once())
            ->method('get')
            ->with('/api/queues/%2f')
            ->will($this->returnValue($requestMock));

        $client = new HttpClient($clientMock, $apiAddress, $user, $password);

        $result = $client->getAllQueuesFromVirtualHost($virtualHost);

        $this->assertEquals($expectedResult, $result);
    }
}
