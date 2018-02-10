<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10.02.18
 * Time: 12:54
 */
namespace AppBundle\Service;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class NoteManager
 * @package AppBundle\Service
 */
class NoteManager
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
