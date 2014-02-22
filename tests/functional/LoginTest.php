<?php

use Messenger\Test\WebTestCase;

class LoginTest extends WebTestCase
{
    public function testShouldRenderLoginPage()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertCount(1, $crawler->filter('form'));
        $this->assertCount(1, $crawler->filter('h2:contains("Please sign in")'));
    }

    public function testShouldGoToUserPageWhenUserCanLogIn()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isOk());

        $form = $crawler->selectButton('Sign in')->form(array('login' => 'testuser'));

        $client->submit($form);

        $this->assertEquals('http://localhost/home/', $client->getRequest()->getUri());
    }

    public function testShouldDisplayAnErrorMessagePageWhenUserLoginIsInvalid()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isOk());

        $form = $crawler->selectButton('Sign in')->form(array('login' => '123456'));

        $client->submit($form);

        $this->assertCount(1, $client->getCrawler()->filter('h2:contains("Sorry, something went wrong...")'));
    }

    public function testShouldRedirectUserWhenItIsNotLoggedInAndTryToAccessUserPage()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/home');

        $this->assertTrue($client->getResponse()->isOk());

        $this->assertEquals('http://localhost/', $client->getRequest()->getUri());
    }
}
