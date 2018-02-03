<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 02/02/2018
 * Time: 18:18
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Circle[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Circle", mappedBy="user", cascade={"persist"}, orphanRemoval=true)
     */
    protected $circles;

    /**
     * @var Note[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Note", mappedBy="user", cascade={"persist"}, orphanRemoval=true)
     */
    protected $notes;

    public function __construct()
    {
        $this->circles = new ArrayCollection();
        $this->notes = new ArrayCollection();
        parent::__construct();
    }

    /**
     * Add note
     * @param Note $note
     * @return $this
     */
    public function addNote(Note $note)
    {
        $this->notes[] = $note;
        return $this;
    }

    /**
     * Remove note
     * @param Note $note
     */
    public function removeNote(Note $note)
    {
        $this->notes->removeElement($note);
    }

    /**
     * Get notes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param Note[] $notes
     * @return $this
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
        return $this;
    }

    /**
     * Add circle
     * @param Circle $circle
     * @return $this
     */
    public function addCircle(Circle $circle)
    {
        $this->circles[] = $circle;
        return $this;
    }

    /**
     * Remove circle
     * @param Circle $circle
     */
    public function removeCircle(Circle $circle)
    {
        $this->circles->removeElement($circle);
    }

    /**
     * Get circles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCircles()
    {
        return $this->circles;
    }

    /**
     * @param Circle[] $circles
     * @return $this
     */
    public function setCircles($circles)
    {
        $this->circles = $circles;
        return $this;
    }


}