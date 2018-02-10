<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 10/02/2018
 * Time: 13:38
 */

namespace AppBundle\Entity;


/**
 * Trait Select
 * @package AppBundle\Entity
 */
trait Select
{
    /**
     * @var bool
     */
    protected $selected = false;

    /**
     * @return bool
     */
    public function isSelected()
    {
        return $this->selected;
    }

    /**
     * @param bool $selected
     * @return $this
     */
    public function setSelected($selected)
    {
        $this->selected = $selected;
        return $this;
    }

}