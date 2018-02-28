<?php

namespace DaemonBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Doctrine\ORM\EntityManager;

use DaemonBundle\Helper\Ami;

use DaemonBundle\Entity\AmiInputEvents;
use AutoDialBundle\Entity\Dial;
use AutoDialBundle\Entity\Process;
use AutoDialBundle\Entity\ActiveCalling;
use AutoDialBundle\Entity\PhoneWav;
use AutoDialBundle\Entity\DialRuleArray;
use AutoDialBundle\Entity\Sound;

class DaemonCallCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('daemon:call')
            ->setDescription('StartCalling')
            // ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            // ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
      //        $argument = $input->getArgument('argument');
      //        if ($input->getOption('option')) {
      //            // ...
      //        }
              $this->dc = $this->getContainer()->get('doctrine');
              $this->ami = new Ami();

              // проверка на активность обзвона
              $em = $this->dc->getEntityManager()
                  ->createQuery('
                SELECT p FROM AutoDialBundle:Process p
                WHERE p.active = :active AND p.name = :name
            ')->setParameters(array('active'=>'Y','name'=>'calling'));


            if (empty($em->getResult())) {
              $output->writeln('Nothing to start');
              return false;
            }else{
              $result = $em->getResult();
            }

            if (count($result)>1) {
              $output->writeln('Error. Many active process.');
              return false;
            }
            $result = $result[0];
            if ($result->getEnd() != '') {
              // такой процесс надо выключить
              $output->writeln('take off');
              return false;
            }
            // if ($result->getStart() != '') {
            //   // такой процесс надо выключить
            //   $output->writeln('already started!');
            //   return false;
            // }
            $prosPid = getmypid();
            if ($prosPid) {
                $this->pid = $prosPid;
            }else{
              $output->writeln('get pid error!!!');
              return false;
            }
            $this->dialBaseId = $result->getProcess();
              // если нет, то сделать текущий обзвон активным
            $result->setPid($this->pid);
            $obDate = new \DateTime();
            $result->setStart($obDate);
            $em = $this->dc->getManager();
            $em->persist($result);
            $em->flush();


              // $output->writeln('Command result: '. var_export($em->getResult()));

      //        $this->initPid();
      //        $this->start();
      //        $this->unsetPid();
              $this->lookBase();

      //        $em = $this->getContainer()->get('doctrine')->getRepository('AutoDialBundle:Dial')
      //            ->find(1);
      //        $output->writeln(var_dump($em->getId()));

        $output->writeln('Command result.');
    }


    public function lookBase(){
        $currentTimeDate = time();
        $arWork = array();
       $arPhonesCalling = $this->dc->getRepository('AutoDialBundle:ActiveCalling')
       ->findBy([
         'dialBase'=>$this->dialBaseId
       ]);

       $rule = $this->dc->getRepository('AutoDialBundle:DialRuleArray')
         ->findBy([
           'dialBase'=>$this->dialBaseId
         ]);
       if (empty($rule)) {
         $output->writeln('Have no finalRule');
         return false;
       }

       $arRule = unserialize(file_get_contents('/var/www/html/sym/call/var/sound/rule/'.$rule[0]->getArray()));
       // var_dump($arRule);
       // return false;
            // заглушить пустоту
//         foreach ($allRules->getResult() as $item) {
// //            print_r($item->getID);
// //            echo strtotime($item->getDateStart()) ." ->".$item->getDateStart()."\n";
//             if (strtotime($item->getDateStart()) > $currentTimeDate ){
//                 continue;
//             }else{
// //                echo "пора\n";
//                 $arWork[] = $item->getId();
//             }
//         }
        $arAMIItog = array();
        foreach ($arPhonesCalling as $key => $value) {
          $currentPhone = array();
          $currentPhone['phone'] = $value->getPhone();
          $currentPhone['PLAY_FILE'] = '';
          foreach ($arRule as $keyNN => $valueNN) {
            if (is_array($valueNN)) {
              if ($valueNN['type'] == 'text_to_speech') {
                $speechSound = $this->dc->getRepository('AutoDialBundle:PhoneWav')
                  ->findBy([
                    'dialRuleId'=>$valueNN['rule_id'],
                    'phone'=>$currentPhone['phone']
                  ]);
                $currentPhone['PLAY_FILE'].= str_replace("/var/www/html/sym/call/var/sound/","/var/spool/astmpsnd/",$speechSound[0]->getSound()).'&';
              }
            }else{
              $currentPhone['PLAY_FILE'].= str_replace("/var/www/html/sym/call/var/sound/user/","/var/spool/astmpsnd/",$valueNN).'&';
            }
          }
          $currentPhone['PLAY_FILE'] = mb_substr($currentPhone['PLAY_FILE'],0,-1,"UTF-8");
          $arAMIItog[] = $currentPhone;
        }
        // var_dump($arAMIItog);
        // return false;
        $this->ami->connect();
        $this->ami->init();
        foreach ($arAMIItog as $NN => $arPhoneItog) {
          $AMIAction =  "Action: Originate\r\n";
          $AMIAction .= "Channel: Local/".trim($arPhoneItog['phone'])."@office_out/n\r\n";
          $AMIAction .= "Context: autoinform\r\n";
          $AMIAction .= "ActionID: ".rand (10000000000000000,99999999900000000)."\r\n";
          $AMIAction .= "Exten: s\r\n";
          $AMIAction .= "Priority: 1\r\n";
          $AMIAction .= "Async: true\r\n";
          $AMIAction .= "CallerID: 79202285373\r\n";
          $AMIAction .= "Variable: PR_BUT=0\r\n";
          $AMIAction .= "Variable: PLAY_FILE=".$arPhoneItog['PLAY_FILE']."\r\n\r\n";
          // var_dump($AMIAction);
          // die('end');
          $this->ami->writeAnyAction($AMIAction);
        }

        // $currentDial = array_shift($arWork);
        // $currentDial = $this->dc->getRepository('AutoDialBundle:Dial')
        //     ->find($currentDial);
        // if (empty($currentDial->getDateCallStart())){
        //     $allPhones = $this->dc->getEntityManager()
        //         ->createQuery('
        //       SELECT p FROM AutoDialBundle:Phones p
        //       WHERE p.active = :active AND p.dial = :dial
        //   ')->setParameters(array('active'=>'Y','dial'=>$currentDial->getId()));
        //
        //     $arAllPhones = $allPhones->getResult();
        //     $arAddr = $arPhones = array();
        //     foreach ($arAllPhones as $onePhone){
        //         $timeArray = array();
        //         $timeArray['addr'] =  $this->putAddrBase($onePhone->getAddr()) ;
        //         $timeArray['phone'] = $onePhone->getPhone();
        //
        //         $arPhones[] = $timeArray;
        //     }
        //     // залил все файлы на сервер
        //     // записать в обзвон, что он стартовал
        //     //
        //     $this->ami->connect();
        //     $this->ami->init();
        //     foreach ($arPhones as $item) {
        //         $this->ami->originateAutoDialNumber($item['phone'],$item['addr']);
        //     }
        //
        //
        //     print_r($arPhones);
        // }
        //если есть дата начала обзвона, то ничего не делаем.

        // если есть дата окончания обзвона и есть все результаты звонков
        // переносим дату окончания обзвона на дату последнего звонка
        // деактивируем обзвон.

    }

}
