<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Sector
 *
 * @ORM\Table(name="sector")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SectorRepository")
 */
class Sector
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="category", type="int")
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @var Circle
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Circle", inversedBy="sectors")
     */
    private $circle;


    /**
     * @var NoteLabel[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\NoteLabel", mappedBy="user", cascade={"persist"}, orphanRemoval=true)
     */
    protected $noteLabels;

    /**
     * Sector constructor.
     */
    public function __construct()
    {
        $this->noteLabels = new ArrayCollection();
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
     * Set category
     *
     * @param int $category
     *
     * @return Sector
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return int
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Sector
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
     * @return Circle
     */
    public function getCircle()
    {
        return $this->circle;
    }

    /**
     * @param Circle $circle
     * @return $this
     */
    public function setCircle(Circle $circle)
    {
        $this->circle = $circle;
        return $this;
    }


    /**
     * Add noteLabel
     * @param NoteLabel $noteLabel
     * @return $this
     */
    public function addNoteLabel(NoteLabel $noteLabel)
    {
        $this->noteLabels[] = $noteLabel;
        return $this;
    }

    /**
     * Remove noteLabel
     * @param NoteLabel $noteLabel
     */
    public function removeNoteLabel(NoteLabel $noteLabel)
    {
        $this->noteLabels->removeElement($noteLabel);
    }

    /**
     * Get noteLabels
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNoteLabels()
    {
        return $this->noteLabels;
    }

    /**
     * @param NoteLabel[] $noteLabels
     * @return $this
     */
    public function setNoteLabels($noteLabels)
    {
        $this->noteLabels = $noteLabels;
        return $this;
    }
}

