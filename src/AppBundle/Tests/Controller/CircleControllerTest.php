<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\CommonTestsClass;
use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Class CircleControllerTest
 * @package AppBundle\Tests\Controller
 */
class CircleControllerTest extends WebTestCase
{
    use CommonAppTrait;

    /**
     * @param string $url
     * @dataProvider getUrlsForChecking
     */
    public function testStatusPagesForNoLogin($url)
    {
        $client = static::createClient();
        $this->loadFixtures(CommonTestsClass::getAllFixturesArray());

        $client->request('GET', $url);

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    /**
     * @param string $url
     * @dataProvider getUrlsForChecking
     */
    public function testStatusPagesForLogin($url)
    {
        $this->loadFixtures(CommonTestsClass::getAllFixturesArray());
        $this->logIn();

        $this->client->request('GET', $url);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }


    public function getUrlsForChecking()
    {
        $urls = [
            ['/circle/'],
        ];

        return $urls;
    }

}