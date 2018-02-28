<?php
namespace DaemonBundle\Helper;

//use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use DaemonBundle\Entity\AmiInputEvents;

use Doctrine\ORM\EntityManager;

use DaemonBundle\Helper\Ami;
use DaemonBundle\Service\SmartBase;
// use DaemonBundle\Helper\;

/**
 *
 */
class DaemonStart
{
  // pid процесса
  protected $pid;
  protected $timeFormat = 'd-m-Y H:i:s';
  protected $timeBDFormat = 'H:i:s';
  protected $dateBDFormat = 'd-m-Y';

  public $path = '/var/www/html/sym/call/var/daemon/';
  public $sys  = '/var/www/html/sym/call/var/daemon/sys/';
  public $log  = '/var/www/html/sym/call/var/daemon/log/';

  protected $live = true;

    /**
     * DaemonStart constructor.
     * @param EntityManager $em
     */
  function __construct(EntityManager $em)
  {
      $this->em = $em;
//     $allDial = $this->getDoctrine()
//       ->getRepository('DaemonBundle:AmiInputEvents')
//       ->findAll();
    $resDir = opendir($this->sys);
    while (false !== ($file = readdir($resDir))) {

        if ($file != '.' && $file != '..') {
            echo 'process already started';
            return false;
        }
    }
    closedir($resDir);

    if ($this->isStop()) {
        echo 'File stop is exist';
        return false;
    }
    $this->initPid();
    $this->ami = new Ami();
    $this->start();
    $this->unsetPid();
  }

  // проверяем, надо ли остановить демона.
  function isStop(){
      $resDir = opendir($this->path);
      while (false !== ($file = readdir($resDir))) {
          if ($file == 'stop') {
              return true;
          }
      }
      return false;
  }
  // тело
  function start(){
    $this->ami->connect();
    if ($this->ami->ini["con"])
    {
        $this->ami->init();

        $this->ami->write("Action: Status\n");
        print_r($this->ami->read());
        // echo '!!!!!!!!!!!!!-----!!!!!!!!!!!!!!';

    }
    // $this->ami->originateAutoDialNumber(79202285373,'e9d7a13444eb0fed8e10b1d378f412ce');
    // echo "call\n";

      while ($this->live) {
           sleep(1);
           $temp = '';
          if ($this->isStop()) {
              $this->live = false;
          }
          $temp = $this->ami->read();
          // print_r($temp);
          $this->logSelf($temp);

          // $this->getGoodFile();
      }
      if ($this->ami->ini["con"]) {
        $this->ami->disconnect();
      }
  }

  // // логи действий
  // function saySelf($str){
  //     echo $str;
  //     self::$out .= $str;
  //     if (self::$out == "tic \ntic \ntic \ntic \ntic \ntic \n") {
  //         self::$out = '';
  //         file_put_contents('text.log','я жив'.date('h:i').PHP_EOL,FILE_APPEND);
  //     }
  // }
  // логи действий
  function logSelf($input){
    file_put_contents($this->log.'log'.date('ymd').'.log',date('h:i:s').' '.var_export($input,true) .PHP_EOL,FILE_APPEND);
  }

  // function getGoodFile(){
  //   $resDir = opendir($this->pathListen);
  //   while (false !== ($file = readdir($resDir))) {
  //       if ($file == 'callfile.php') {
  //           include($this->pathListen.'callfile.php');
  //       }
  //   }
  // }

  function initPid(){
    $prosPid = getmypid();
    if ($prosPid) {
      $this->pid = $prosPid;
    }else{
      $this->live = false;
    }
    file_put_contents($this->sys.$prosPid,'');
  }

  function unsetPid(){
    unlink($this->sys.$this->pid);
  }
}
