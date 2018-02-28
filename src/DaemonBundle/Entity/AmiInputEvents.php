<?php

namespace DaemonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AmiInputEvents
 *
 * @ORM\Table(name="ami_input_events")
 * @ORM\Entity
 */
class AmiInputEvents
{
    /**
     * @var string
     *
     * @ORM\Column(name="uid", type="string", length=50, nullable=false)
     */
    private $uid;

    /**
     * @var string
     *
     * @ORM\Column(name="action", type="string", length=150, nullable=false)
     */
    private $action;

    /**
     * @var string
     *
     * @ORM\Column(name="date_in", type="string", length=20, nullable=false)
     */
    private $dateIn;

    /**
     * @var string
     *
     * @ORM\Column(name="time_in", type="string", length=10, nullable=false)
     */
    private $timeIn;

    /**
     * @var string
     *
     * @ORM\Column(name="array", type="text", length=65535, nullable=false)
     */
    private $array;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set uid
     *
     * @param string $uid
     *
     * @return AmiInputEvents
     */
    public function setUid($uid)
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * Get uid
     *
     * @return string
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * Set action
     *
     * @param string $action
     *
     * @return AmiInputEvents
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set dateIn
     *
     * @param string $dateIn
     *
     * @return AmiInputEvents
     */
    public function setDateIn($dateIn)
    {
        $this->dateIn = $dateIn;

        return $this;
    }

    /**
     * Get dateIn
     *
     * @return string
     */
    public function getDateIn()
    {
        return $this->dateIn;
    }

    /**
     * Set timeIn
     *
     * @param string $timeIn
     *
     * @return AmiInputEvents
     */
    public function setTimeIn($timeIn)
    {
        $this->timeIn = $timeIn;

        return $this;
    }

    /**
     * Get timeIn
     *
     * @return string
     */
    public function getTimeIn()
    {
        return $this->timeIn;
    }

    /**
     * Set array
     *
     * @param string $array
     *
     * @return AmiInputEvents
     */
    public function setArray($array)
    {
        $this->array = $array;

        return $this;
    }

    /**
     * Get array
     *
     * @return string
     */
    public function getArray()
    {
        return $this->array;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
