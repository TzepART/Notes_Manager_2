<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;

/**
 * Circle
 *
 * @ORM\Table(name="circle")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CircleRepository")
 */
class Circle
{
    use Timestampable;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="circles")
     */
    private $user;


    /**
     * @var int
     *
     * @ORM\Column(name="count_layer", type="integer")
     */
    private $countLayer;


    /**
     * @var Sector[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Sector", mappedBy="circle", cascade={"persist"}, orphanRemoval=true)
     */
    protected $sectors;

    /**
     * Circle constructor.
     */
    public function __construct()
    {
        $this->sectors = new ArrayCollection();
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Circle
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }


    /**
     * Set countLayer
     *
     * @param int $countLayer
     *
     * @return Circle
     */
    public function setCountLayer($countLayer)
    {
        $this->countLayer = $countLayer;

        return $this;
    }

    /**
     * Get countLayer
     *
     * @return int
     */
    public function getCountLayer()
    {
        return $this->countLayer;
    }

    /**
     * Add sector
     * @param Sector $sector
     * @return $this
     */
    public function addSector(Sector $sector)
    {
        $this->sectors[] = $sector;
        return $this;
    }

    /**
     * Remove sector
     * @param Sector $sector
     */
    public function removeSector(Sector $sector)
    {
        $this->sectors->removeElement($sector);
    }

    /**
     * Get sectors
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSectors()
    {
        return $this->sectors;
    }

    /**
     * @param Sector[] $sectors
     * @return $this
     */
    public function setSectors($sectors)
    {
        $this->sectors = $sectors;
        return $this;
    }
}

