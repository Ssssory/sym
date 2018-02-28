<?php

namespace DaemonBundle\Helper;

class Ami
{
    public $ini = array();

    function __construct ()
    {
        $this->ini["con"] = false;
        $this->ini["host"] = "call.callrobots.ru";
        $this->ini["port"] = "5838";
        $this->ini["lastActionID"] = 0;
        $this->ini["lastRead"] = array ();
        $this->ini["sleep_time"]=1.5;
        $this->ini["login"] = "listener_ami";
        $this->ini["password"] = "7#k%A*U2Q*";
    }

    function __destruct()
    {
        unset ($this->ini);
    }

    public function connect()
    {
        $this->ini["con"] = fsockopen($this->ini["host"], $this->ini["port"],$a,$b,10);
        if ($this->ini["con"])
        {
            stream_set_timeout($this->ini["con"], 0, 400000);
        }
    }

    public function disconnect()
    {
        if ($this->ini["con"])
        {
            fclose($this->ini["con"]);
        }
    }

    public function write($a)
    {
        $this->ini["lastActionID"] = rand (10000000000000000,99999999900000000);
        fwrite($this->ini["con"], "ActionID: ".$this->ini["lastActionID"]."\r\n$a\r\n\r\n");
        $this->sleepi();
        return $this->ini["lastActionID"];
    }

    public function originateAutoDialNumber($number,$wav)
    {
        $number = trim($number);
        $wav = trim($wav);
        $inp = "Channel: Local/$number@office_out/n";
//        $inp = str_replace(".",'',$inp);
//        file_put_contents('/var/www/html/sym/call/var/daemon/log/logErr.log',date('h:i:s').' '.var_export($inp,true) .PHP_EOL,FILE_APPEND);
        $this->ini["lastActionID"] = rand (10000000000000000,99999999900000000);
        fwrite($this->ini["con"], "Action: Originate\r\n$inp\r\nContext: autoinform\r\nActionID: ".$this->ini["lastActionID"]."\r\nExten: s\r\nPriority: 1\r\nAsync: true\r\nCallerID: 79202285373\r\nVariable: PR_BUT=0\r\nVariable: PLAY_FILE=/var/spool/astmpsnd/8a0a2eef4ce28db1b07c4f5377e63c6d&/var/spool/astmpsnd/e74643c23e6c44ab66682fe7ef1a053b&/var/spool/astmpsnd/".$wav."&/var/spool/astmpsnd/3be7e3a564a085f5e5663e90d38b4669&/var/spool/astmpsnd/2cd1de7c725298f795cf37fb6bba2aa6&/var/spool/astmpsnd/e6d72ce6b19dfccbc2c30bc205bcd2df\r\n\r\n");
//        echo $inp."\n";
//        echo "Action: Originate\r\n".$inp."Context: autoinform\r\nActionID: ".$this->ini["lastActionID"]."\r\nExten: s\r\nPriority: 1\r\nCallerID: 79093839553\r\nVariable: PR_BUT=0\r\nVariable: PLAY_FILE=/var/spool/astmpsnd/8a0a2eef4ce28db1b07c4f5377e63c6d&/var/spool/astmpsnd/e74643c23e6c44ab66682fe7ef1a053b&/var/spool/astmpsnd/".$wav."&/var/spool/astmpsnd/3be7e3a564a085f5e5663e90d38b4669&/var/spool/astmpsnd/2cd1de7c725298f795cf37fb6bba2aa6&/var/spool/astmpsnd/e6d72ce6b19dfccbc2c30bc205bcd2df\r\n\r\n";
        $this->sleepi();
        return $this->ini["lastActionID"];
    }

    public function writeAnyAction($action)
    {
        fwrite($this->ini["con"], $action);
        $this->sleepi();
        return true;
    }

    public function sleepi ()
    {
        sleep($this->ini["sleep_time"]);
    }

    public function read()
    {
        $mm = array();
        $b = array();
        $k = 0;
        $s = "";
        $this->sleepi();
        do
        {
            $s.= fread($this->ini["con"],1024);
            sleep(0.005);
            $mmm=socket_get_status($this->ini["con"]);
        }   while ($mmm['unread_bytes']);
        $mm = explode ("\r\n",$s);
        $this->ini["lastRead"] = array();
        for ($i=0;$i<count($mm);$i++)
        {
            if ($mm[$i]=="")
            {
                $k++;
            }
            $m = explode(":",$mm[$i]);
            if (isset($m[1]))
            {
                $this->ini["lastRead"][$k][trim($m[0])] = trim($m[1]);
            }
        }
        unset ($b);
        unset ($k);
        unset ($mm);
        unset ($mm);
        unset ($mmm);
        unset ($i);
        unset ($s);
        return $this->ini["lastRead"];
    }

    public function init()
    {
    return $this->write("Action: Login\r\nUsername: ".$this->ini["login"]."\r\nSecret: ".$this->ini["password"]."\r\nEvents: on\r\n\r\n");
    }
}
