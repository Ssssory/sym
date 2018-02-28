<?php

namespace DaemonBundle\Entity;

/**
 * AmiNumsAll
 */
class AmiNumsAll
{
    /**
     * @var string
     */
    private $sip;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $dateEnter;

    /**
     * @var string
     */
    private $timeEnter;

    /**
     * @var string
     */
    private $dateExit;

    /**
     * @var string
     */
    private $timeExit;

    /**
     * @var string
     */
    private $operator;

    /**
     * @var string
     */
    private $countCall;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set sip
     *
     * @param string $sip
     *
     * @return AmiNumsAll
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
     * Set status
     *
     * @param string $status
     *
     * @return AmiNumsAll
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set dateEnter
     *
     * @param string $dateEnter
     *
     * @return AmiNumsAll
     */
    public function setDateEnter($dateEnter)
    {
        $this->dateEnter = $dateEnter;

        return $this;
    }

    /**
     * Get dateEnter
     *
     * @return string
     */
    public function getDateEnter()
    {
        return $this->dateEnter;
    }

    /**
     * Set timeEnter
     *
     * @param string $timeEnter
     *
     * @return AmiNumsAll
     */
    public function setTimeEnter($timeEnter)
    {
        $this->timeEnter = $timeEnter;

        return $this;
    }

    /**
     * Get timeEnter
     *
     * @return string
     */
    public function getTimeEnter()
    {
        return $this->timeEnter;
    }

    /**
     * Set dateExit
     *
     * @param string $dateExit
     *
     * @return AmiNumsAll
     */
    public function setDateExit($dateExit)
    {
        $this->dateExit = $dateExit;

        return $this;
    }

    /**
     * Get dateExit
     *
     * @return string
     */
    public function getDateExit()
    {
        return $this->dateExit;
    }

    /**
     * Set timeExit
     *
     * @param string $timeExit
     *
     * @return AmiNumsAll
     */
    public function setTimeExit($timeExit)
    {
        $this->timeExit = $timeExit;

        return $this;
    }

    /**
     * Get timeExit
     *
     * @return string
     */
    public function getTimeExit()
    {
        return $this->timeExit;
    }

    /**
     * Set operator
     *
     * @param string $operator
     *
     * @return AmiNumsAll
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * Get operator
     *
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Set countCall
     *
     * @param string $countCall
     *
     * @return AmiNumsAll
     */
    public function setCountCall($countCall)
    {
        $this->countCall = $countCall;

        return $this;
    }

    /**
     * Get countCall
     *
     * @return string
     */
    public function getCountCall()
    {
        return $this->countCall;
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
