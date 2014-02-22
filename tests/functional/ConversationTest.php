<?php

use Messenger\Test\WebTestCase;

class ConversationTest extends WebTestCase
{
    public function testShouldDisplayATableOfPeopleThatAUserAlreadyTalkedTo()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isOk());

        $form = $crawler->selectButton('Sign in')->form(array('login' => 'testuser'));

        $client->submit($form);

        $crawler = $client->request('GET', '/conversation/all');

        $this->assertCount(1, $crawler->filter('table'));
    }

    public function testShouldRedirectUserToLoginPageWhenItIsNotLoggedIn()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/conversation/all');

        $this->assertTrue($client->getResponse()->isOk());

        $this->assertEquals('http://localhost/', $client->getRequest()->getUri());
    }

    public function testShouldRenderNewConversationView()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isOk());

        $form = $crawler->selectButton('Sign in')->form(array('login' => 'testuser'));

        $client->submit($form);

        $crawler = $client->request('GET', '/conversation/new');

        $this->assertCount(1, $crawler->filter("button:contains('Go talk!')"));
    }
}
