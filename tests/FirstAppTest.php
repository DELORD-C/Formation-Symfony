<?php

namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;

class FirstAppTest extends PantherTestCase
{
    public function testHomePage(): void
    {
        $client = static::createPantherClient();
        $client->request('GET', '/');
        $this->assertSelectorTextContains('h1', 'Hello World !');

        $client->clickLink('Default');
        $client->clickLink('Random');
        $this->assertSelectorTextContains('h2', 'Random');
    }
}
