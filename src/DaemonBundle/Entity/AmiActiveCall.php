<?php

namespace DaemonBundle\Entity;

/**
 * AmiActiveCall
 */
class AmiActiveCall
{
    /**
     * @var string
     */
    private $uniqueid;

    /**
     * @var string
     */
    private $linkedid;

    /**
     * @var string
     */
    private $calleridnum;

    /**
     * @var string
     */
    private $calleridname;

    /**
     * @var string
     */
    private $exten;

    /**
     * @var string
     */
    private $dateStart;

    /**
     * @var string
     */
    private $timeStart;

    /**
     * @var string
     */
    private $priority;

    /**
     * @var string
     */
    private $operator;

    /**
     * @var string
     */
    private $context;

    /**
     * @var string
     */
    private $accountcode;

    /**
     * @var string
     */
    private $channelstatedesc;

    /**
     * @var string
     */
    private $channel;

    /**
     * @var string
     */
    private $systemname;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set uniqueid
     *
     * @param string $uniqueid
     *
     * @return AmiActiveCall
     */
    public function setUniqueid($uniqueid)
    {
        $this->uniqueid = $uniqueid;

        return $this;
    }

    /**
     * Get uniqueid
     *
     * @return string
     */
    public function getUniqueid()
    {
        return $this->uniqueid;
    }

    /**
     * Set linkedid
     *
     * @param string $linkedid
     *
     * @return AmiActiveCall
     */
    public function setLinkedid($linkedid)
    {
        $this->linkedid = $linkedid;

        return $this;
    }

    /**
     * Get linkedid
     *
     * @return string
     */
    public function getLinkedid()
    {
        return $this->linkedid;
    }

    /**
     * Set calleridnum
     *
     * @param string $calleridnum
     *
     * @return AmiActiveCall
     */
    public function setCalleridnum($calleridnum)
    {
        $this->calleridnum = $calleridnum;

        return $this;
    }

    /**
     * Get calleridnum
     *
     * @return string
     */
    public function getCalleridnum()
    {
        return $this->calleridnum;
    }

    /**
     * Set calleridname
     *
     * @param string $calleridname
     *
     * @return AmiActiveCall
     */
    public function setCalleridname($calleridname)
    {
        $this->calleridname = $calleridname;

        return $this;
    }

    /**
     * Get calleridname
     *
     * @return string
     */
    public function getCalleridname()
    {
        return $this->calleridname;
    }

    /**
     * Set exten
     *
     * @param string $exten
     *
     * @return AmiActiveCall
     */
    public function setExten($exten)
    {
        $this->exten = $exten;

        return $this;
    }

    /**
     * Get exten
     *
     * @return string
     */
    public function getExten()
    {
        return $this->exten;
    }

    /**
     * Set dateStart
     *
     * @param string $dateStart
     *
     * @return AmiActiveCall
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
     * @return AmiActiveCall
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
     * Set priority
     *
     * @param string $priority
     *
     * @return AmiActiveCall
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return string
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set operator
     *
     * @param string $operator
     *
     * @return AmiActiveCall
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
     * Set context
     *
     * @param string $context
     *
     * @return AmiActiveCall
     */
    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Get context
     *
     * @return string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Set accountcode
     *
     * @param string $accountcode
     *
     * @return AmiActiveCall
     */
    public function setAccountcode($accountcode)
    {
        $this->accountcode = $accountcode;

        return $this;
    }

    /**
     * Get accountcode
     *
     * @return string
     */
    public function getAccountcode()
    {
        return $this->accountcode;
    }

    /**
     * Set channelstatedesc
     *
     * @param string $channelstatedesc
     *
     * @return AmiActiveCall
     */
    public function setChannelstatedesc($channelstatedesc)
    {
        $this->channelstatedesc = $channelstatedesc;

        return $this;
    }

    /**
     * Get channelstatedesc
     *
     * @return string
     */
    public function getChannelstatedesc()
    {
        return $this->channelstatedesc;
    }

    /**
     * Set channel
     *
     * @param string $channel
     *
     * @return AmiActiveCall
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Get channel
     *
     * @return string
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * Set systemname
     *
     * @param string $systemname
     *
     * @return AmiActiveCall
     */
    public function setSystemname($systemname)
    {
        $this->systemname = $systemname;

        return $this;
    }

    /**
     * Get systemname
     *
     * @return string
     */
    public function getSystemname()
    {
        return $this->systemname;
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
