<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Sector;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Faker\Factory as FakerFactory;
use Faker\Generator;
use Doctrine\Bundle\FixturesBundle\Fixture;


class LoadSectorsData extends Fixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    const REFERENCE_PREFIX = '_app_sector_';

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Generator
     */
    private $faker;

    
    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->faker = FakerFactory::create();
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $circlesData = LoadCirclesData::getCirclesDate();

        foreach ($circlesData as $circleName => $circlesDatum) {
            $diffAngle = 360/$circlesDatum["countSectors"];
            $beginAngle = 0;
            $endAngle = $diffAngle;
            for($i = 0; $i < $circlesDatum["countSectors"]; $i++){
                $sector = new Sector();

                $sector->setCategory($this->getReference(LoadCategoriesData::REFERENCE_PREFIX.$circleName.$i))
                    ->setCircle($this->getReference(LoadCirclesData::REFERENCE_PREFIX.$circleName))
                    ->setBeginAngle($beginAngle)
                    ->setEndAngle($endAngle)
                    ->setColor($this->faker->hexColor)
                ;

                $manager->persist($sector);

                if ($this->referenceRepository) {
                    $this->addReference($circleName.self::REFERENCE_PREFIX . $i, $sector);
                }
                $manager->flush();

                $beginAngle += $diffAngle;
                $endAngle += $diffAngle;
            }
        }
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 3;
    }
}