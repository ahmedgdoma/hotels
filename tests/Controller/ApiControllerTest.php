<?php
namespace App\Tests\Controller;


use App\Entity\Hotel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;
    private $hotel_id;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $hotels = $this->entityManager
            ->getRepository(Hotel::class)
            ->findAll(['name' => 'Priceless widget']);
        $this->hotel_id = $hotels[0]->getId();
        self::ensureKernelShutdown();
    }
    public function testOverTimeNoDates()
    {

        $client = static::createClient();
        $client->request('GET', '/overtime/'. 50);

        $this->assertEquals(500, $client->getResponse()->getStatusCode());
    }
    public function testOverTimeInvalidDates()
    {

        $client = static::createClient();
        $client->request('GET',
            '/overtime/'.$this->hotel_id.'?start_date=dssd&end_date=b');

        $this->assertEquals('"enter valid dates"', $client->getResponse()->getContent());
    }
    public function testOverTime()
    {

        $client = static::createClient();
        $client->request('GET',
            '/overtime/'.$this->hotel_id.'?start_date=2020-07-15&end_date=2020-07-19');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertIsString($client->getResponse()->getContent());
        $this->assertContains('group', $client->getResponse()->getContent());
    }
    public function testBenchMark()
    {

        $client = static::createClient();
        $client->request('GET',
            '/benchmark/'.$this->hotel_id.'?start_date=2020-07-15&end_date=2020-07-19');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertIsString($client->getResponse()->getContent());
        $this->assertContains('hotel_average', $client->getResponse()->getContent());
        $this->assertContains('requested_hotel', $client->getResponse()->getContent());
    }
}