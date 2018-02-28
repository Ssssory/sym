<?php

namespace DaemonBundle\Entity;

/**
 * AmiActive
 */
class AmiActive
{
    /**
     * @var string
     */
    private $sip;

    /**
     * @var string
     */
    private $action;

    /**
     * @var string
     */
    private $dateStart;

    /**
     * @var string
     */
    private $timeStart;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set sip
     *
     * @param string $sip
     *
     * @return AmiActive
     */
    public function setSip($sip)
    {
        $this->sip = $sip;

        return $this;
    }

    /**
     * Get sip
     *
     * @return string
     */
    public function getSip()
    {
        return $this->sip;
    }

    /**
     * Set action
     *
     * @param string $action
     *
     * @return AmiActive
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
     * Set dateStart
     *
     * @param string $dateStart
     *
     * @return AmiActive
     */
    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    /**
     * Get dateStart
     *
     * @return string
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * Set timeStart
     *
     * @param string $timeStart
     *
     * @return AmiActive
     */
    public function setTimeStart($timeStart)
    {
        $this->timeStart = $timeStart;

        return $this;
    }

    /**
     * Get timeStart
     *
     * @return string
     */
    public function getTimeStart()
    {
        return $this->timeStart;
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
