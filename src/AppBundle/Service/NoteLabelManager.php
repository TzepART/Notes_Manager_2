<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 14.03.17
 * Time: 12:54
 */
namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class NoteLabelManager
 * @package AppBundle\Service
 */
class NoteLabelManager
{
    /**
     * @var EntityManager $em
     */
    protected $em;

    /**
     * @var Container $container
     * */
    protected $container;


    /**
     * @param Container $container
     * @param EntityManager $em
     */
    public function __construct(Container $container, EntityManager $em)
    {
        $this->em = $em;
        $this->container = $container;
    }
}
