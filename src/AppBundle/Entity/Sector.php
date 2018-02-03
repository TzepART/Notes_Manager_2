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
     * @var Category
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Category", inversedBy="sector", cascade={"persist","remove"}, orphanRemoval=true)
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
     * @var float
     * @ORM\Column(name="beginAngle", type="float")
     */
    private $beginAngle;

    /**
     * @var float
     * @ORM\Column(name="endAngle", type="float")
     */
    private $endAngle;


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
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Category $category
     * @return $this
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;
        return $this;
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

    /**
     * @return float
     */
    public function getBeginAngle()
    {
        return $this->beginAngle;
    }

    /**
     * @param float $beginAngle
     * @return $this
     */
    public function setBeginAngle($beginAngle)
    {
        $this->beginAngle = $beginAngle;
        return $this;
    }

    /**
     * @return float
     */
    public function getEndAngle()
    {
        return $this->endAngle;
    }

    /**
     * @param float $endAngle
     * @return $this
     */
    public function setEndAngle($endAngle)
    {
        $this->endAngle = $endAngle;
        return $this;
    }
}

