<?php

namespace BestTrip\AdminBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    public function testListguides()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/listGuides');
    }

    public function testListexperience()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/listExperience');
    }

    public function testListlieux()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/listLieux');
    }

    public function testListcompagnies()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/listCompagnies');
    }

    public function testNotifs()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/notifs');
    }

}
