<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 14.03.17
 * Time: 12:54
 */
namespace AppBundle\Service;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class NoteLabelManager
 * @package AppBundle\Service
 */
class NoteLabelManager
{
    /**
     * @var ObjectManager $em
     */
    protected $em;

    /**
     * @var ContainerInterface $container
     * */
    protected $container;


    /**
     * @param ContainerInterface $container
     * @param ObjectManager $em
     */
    public function __construct(ContainerInterface $container, ObjectManager $em)
    {
        $this->em = $em;
        $this->container = $container;
    }
}
