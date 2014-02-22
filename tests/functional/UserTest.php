<?php

namespace Messenger;

use Messenger\Test\WebTestCase;

class UsersTest extends WebTestCase
{
    public function testShouldRenderUsersList()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isOk());

        $form = $crawler->selectButton('Sign in')->form(array('login' => 'testuser'));

        $client->submit($form);

        $crawler = $client->request('GET', '/user/all');

        $this->assertCount(1, $crawler->filter('table'), $crawler->text());
    }

    public function testShouldRedirectUserToLoginPageWhenItIsNotLoggedIn()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/user/all');

        $this->assertTrue($client->getResponse()->isOk());

        $this->assertEquals('http://localhost/', $client->getRequest()->getUri());
    }
}
