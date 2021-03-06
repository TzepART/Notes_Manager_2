<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;

/**
 * Sector
 *
 * @ORM\Table(name="sector")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SectorRepository")
 */
class Sector
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
     * @var Category
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Category", inversedBy="sector")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;


    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string")
     */
    private $color;

    /**
     * @var Circle
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Circle", inversedBy="sectors")
     */
    private $circle;


    /**
     * @var NoteLabel[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\NoteLabel", mappedBy="sector", cascade={"persist"}, orphanRemoval=true)
     * @ORM\OrderBy({"radius" = "ASC"})
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
     * @var string
     */
    private $name;


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

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $color
     * @return $this
     */
    public function setColor($color)
    {
        $this->color = $color;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        if($this->category instanceof Category){
            return $this->category->getName();
        }else{
            return $this->name;
        }
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }


}

