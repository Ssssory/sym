<?php
namespace DaemonBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DaemonBundle\Helper\DaemonStart;
use DaemonBundle\Entity\AmiInputEvents;
use AutoDialBundle\Entity\Sound;
use AutoDialBundle\Entity\UserSound;
use AutoDialBundle\Entity\PhoneWav;
use Doctrine\ORM\EntityManager;

use AutoDialBundle\Entity\Process;
use AutoDialBundle\Entity\Phones;
use AutoDialBundle\Entity\Rules;
use AutoDialBundle\Entity\DialRuleArray;
use AutoDialBundle\Entity\PhonesWav;
use AutoDialBundle\Entity\ActiveCalling;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    protected $yandexKey = 'f03ce46a-4293-4210-a29a-58f0336d0d8d';
    protected $soundPath = '/var/www/html/sym/call/var/sound/';
    /**
     * @Route("/daemon/old")
     */
    public function indexAction()
    {
      // die('common');
        return $this->render('default/empty.html.twig',array(
          'tmp'=> '',
        ));
    }
    /**
     * @Route("/daemon")
     */
    public function daemonIndexAction()
    {
        return $this->render('dial/daemon.control.html.twig',array(
            'breadcrumbs'=>'Управление демоном',
          'tmp'=> '',
        ));
    }

    /**
     * @Route("/daemon/add/{text}")
     */
    public function newSoundAction($text)
    {
      // $allDial = $this->getDoctrine()
      //   ->getRepository('DaemonBundle:AmiInputEvents')
      //   ->findAll();

      $result = 'empty';
      // приходит на вход в метод
      // $text = 'Улица набережная дом 5';
      // переписать сокращения
      // убрать точки
      $text = mb_strtolower($text,'utf-8');
      $fileName = md5($text);

      // если есть в базе, получить имя.
      $sound = $this->getDoctrine()
        ->getRepository('AutoDialBundle:Sound')
        ->findBy([
          'hash'=>$fileName
        ]);
      if (!empty($sound)) {
        // add to dial
        if (file_exists($this->soundPath.$fileName.'.wav')){
          $result = $this->soundPath.$fileName.'.wav';
        }else{
          return $this->render('default/error.html.twig',array(
            'tmp'=> 'файл есть в базе, но отсутствует на диске',
          ));
        }

      }else{
        // если нет в базе, скачать файл
        $this->generateSpeechFile($text, $this->soundPath.$fileName.'_old.wav');
        // преобразовать файл в сжатый вав
        $this->convertoToAsteriskFormat($this->soundPath.$fileName.'_old.wav', $this->soundPath.$fileName.'.wav');
        // записать по фтп
        $tempp = $this->uploadFTPSound($fileName);
        // if ($tempp !== true) {
        //   return $this->render('default/error.html.twig',array(
        //     'tmp'=> $tempp,
        //   ));
        // }
        $result = $tempp;

        // записать в базу
        if (file_exists($this->soundPath.$fileName.'.wav')){
          $newSound = new Sound;

          $newSound->setName($text);
          $newSound->setHash($fileName);
          $newSound->setPath($this->soundPath.$fileName.'.wav');
          $em = $this->getDoctrine()->getManager();
          $em->persist($newSound);
          $em->flush();
          // $result = 'success';
        }

      }
        return $this->render('default/empty.html.twig',array(
          'tmp'=> $result,
        ));
    }

    public function generateSpeechFile($message, $fileSaveName)
    {

        $apiKey = $this->yandexKey;

        $wget = 'wget -U "Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.1.5) Gecko/20091102 Firefox/3.5.5" ';
        $wget .= '"https://tts.voicetech.yandex.net/generate?text=' . $message . '&format=wav&lang=ru-RU&speaker=oksana&speed=0.8&emotion=neutral&key=' . $apiKey . '" -O ' . $fileSaveName;

        exec($wget);

        // if (!file_exists($fileSaveName)){
        //     $result->add(TranslateModel::getTranslateMessageByCode('generate_speech_error'));
        //     return $result;
        // }

        // return $result;
    }

    private function convertoToAsteriskFormat($filePath, $convetrFilePath){
        if (file_exists($convetrFilePath)){
            unlink($convetrFilePath);
        }

        //exec('sox -v 0.5 ' . $filePath . ' -t wav -b 16 -r 8000 -c 1 ' . $convetrFilePath);
        exec('sox -v 0.5 ' . $filePath . ' -t wav -2 -r 8000 -c 1 ' . $convetrFilePath);
        unlink($filePath);
    }
    private function uploadFTPSound($name){
      $conn_id = ftp_connect('ast-01.callrobots.ru',211);
      // входим при помощи логина и пароля
      $login_result = ftp_login($conn_id, 'astmpsnd', 'Ts0yZh1V');
      ftp_pasv($conn_id,true);
      // проверяем подключение
      if ((!$conn_id) || (!$login_result)) {
        ftp_close($conn_id);
        return "FTP connection has failed!";
       }
       // загружаем файл
      $upload = ftp_put($conn_id, $name.'.wav', $this->soundPath.$name.'.wav', FTP_BINARY);
      // проверяем статус загрузки
      if (!$upload) {
        ftp_close($conn_id);
         return  "Error: FTP upload has failed!";
      }
      ftp_close($conn_id);
      return true;
    }







    /**
     * @Route("/daemon/work", name="work_ajax")
     *
     */
    public function workAction( Request $request )
    {

      $route = $request->request->get('route');
      $dialId = $request->request->get('dial');
      $step = $request->request->get('step');


      if ($route == 'start') {
        $sound = $this->getDoctrine()
          ->getRepository('AutoDialBundle:DialRuleArray')
          ->findBy([
            'dialBase'=>$dialId
          ]);
        if (!empty($sound)) {
          die(json_encode(array('continue'=>'Y')));
        }
        unset($sound);

        $em = $this->getDoctrine()->getEntityManager()
            ->createQuery('
          SELECT p FROM AutoDialBundle:Process p
          WHERE p.active = :active AND p.name = :name AND p.process != :dial
          ')->setParameters(array('active'=>'Y','name'=>'calling','dial'=>$dialId));
        if (!empty($em->getResult())) {
          die(json_encode(array('stop'=>'already Started another dial')));
        }
        unset($em);
        $em = $this->getDoctrine()->getEntityManager()
              ->createQuery('
            SELECT p FROM AutoDialBundle:Process p
            WHERE p.active = :active AND p.name = :name AND p.process = :dial
            ')->setParameters(array('active'=>'Y','name'=>'calling','dial'=>$dialId));
        if (!empty($em->getResult())) {
          die(json_encode(array('stop'=>'Dial already Started')));
        }
        unset($em);

        $obProcess = new Process;
        $obProcess->setName('calling');
        $obProcess->setProcess($dialId);
        $obProcess->setActive('Y');
        $obProcess->setPid('');
        $em = $this->getDoctrine()->getManager();
        $em->persist($obProcess);
        $em->flush();

        // разносим по роутам
        $em = $this->getDoctrine()->getEntityManager()
              ->createQuery('
            SELECT p FROM AutoDialBundle:Phones p
            WHERE p.active = :active AND p.dial = :dial
            ')->setParameters(array('active'=>'Y','dial'=>$dialId));
          $allPhones = $em->getResult();
        if (empty($allPhones)) {
          die(json_encode(array('stop'=>'Have no phones to call')));
        }
        foreach ($allPhones as $key => $value) {
          if (!empty($value->getPhone())) {
            $onPhone = new ActiveCalling;
            $onPhone->setDialBase($dialId);
            $onPhone->setPhone($value->getPhone());
            $em = $this->getDoctrine()->getManager();
            $em->persist($onPhone);
            $em->flush();
          }
        }

        unset($em,$allPhones);

        $em = $this->getDoctrine()->getEntityManager()
              ->createQuery('
            SELECT p FROM AutoDialBundle:Rules p
            WHERE p.dial = :dial
            ')->setParameters(array('dial'=>$dialId));
        $currentRules = $em->getResult();
        if (empty($currentRules)) {
          die(json_encode(array('stop'=>'Empty rules')));
        }



        $n = 0;
        $currentType = '';
        $arMorfing = array();
        $arAllAudio = array();
        $arResultRule = array();
        foreach ($currentRules as $keyNum => $valueRule) {
          $type = $valueRule->getType();
          $value = $valueRule->getValue();
          $currentId = $valueRule->getId();
          if ($currentType == $type) {
            $arMorfing[] = $value;
          }else{
            if ($n++>0) {
              $arCollapse=array();
              $arCollapse['type'] = $currentType;
              $arCollapse['id'] = $currentId;
              $arCollapse['value'] = $arMorfing;
              $arAllAudio[] = $arCollapse;
            }
            $currentType = $type;
            $arMorfing = array();
            $arMorfing[] = $value;
          }
        }
        $arCollapse=array();
        $arCollapse['type'] = $currentType;
        $arCollapse['id'] = $currentId;
        $arCollapse['value'] = $arMorfing;
        $arAllAudio[] = $arCollapse;
        unset($n,$currentType,$arMorfing,$arCollapse,$type,$value);
        foreach ($arAllAudio as $keyNN => $arRule) {
          if ($arRule['type'] == 'sound') {
            if (count($arRule['value'])>1) {
              $arConcat = array();
              foreach ($arRule['value'] as $nn => $idWAV) {
                $sound = $this->getDoctrine()
                    ->getRepository('AutoDialBundle:UserSound')
                    ->find($idWAV);
                $arConcat[] =$sound->getPath();
              }
              $arResultRule[]=$this->concatAnyWavFile($arConcat);
            }else{
              $sound = $this->getDoctrine()
                  ->getRepository('AutoDialBundle:UserSound')
                  ->find($arRule['type'][0]);

              $arResultRule[]=$sound->getPath();
            }
          }elseif ($arRule['type'] == 'text_to_speech') {
            $arResultRule[] = array('type'=>'text_to_speech','value'=>$arRule['value'],'rule_id'=>$arRule['id']);
          }elseif ($arRule['type'] == 's') {
            $arResultRule[] = array('type'=>'s','value'=>$arRule['value']);
          }else{
            die(json_encode(array('stop'=>'New type Rule! #'.$arRule['type'].'# I do not know how it work(')));
          }
        }
        $nameRuleFile = md5(serialize($arResultRule));
        file_put_contents('/var/www/html/sym/call/var/sound/rule/'.$nameRuleFile,serialize($arResultRule));
        foreach ($arResultRule as $key => $value) {
          if (!is_array($value)) {
              $conn_id = ftp_connect('ast-01.callrobots.ru',211);
              // входим при помощи логина и пароля
              $login_result = ftp_login($conn_id, 'astmpsnd', 'Ts0yZh1V');
              ftp_pasv($conn_id,true);
              // проверяем подключение
              if ((!$conn_id) || (!$login_result)) {
                ftp_close($conn_id);
                return "FTP connection has failed!";
               }
               // загружаем файл
              $arCurrentNameConcatFile =  explode('/',$value);
              $currentNameConcatFile =  array_pop($arCurrentNameConcatFile);
              $upload = ftp_put($conn_id, $currentNameConcatFile.'.wav', $value.'.wav', FTP_BINARY);
              // проверяем статус загрузки
              if (!$upload) {
                ftp_close($conn_id);
                 return  "Error: FTP upload has failed!";
              }
              ftp_close($conn_id);
          }
        }
        $obResult = new DialRuleArray;
        $obResult->setDialBase($dialId);
        $obResult->setArray($nameRuleFile);
        $em = $this->getDoctrine()->getManager();
        $em->persist($obResult);
        $em->flush();
        unset($em);
        die(json_encode(array('continue'=>'Y')));
        // die(json_encode(array(serialize($arResultRule))));
        // // формируем общие файлы по обзвону


      }
      if ($route == 'arrays') {

        $allCallingPhones = array();
        $allCallingPhonesWork = $this->getDoctrine()->getRepository('AutoDialBundle:ActiveCalling')
        ->findBy([
          'dialBase'=>$dialId
        ]);
        if (!empty($allPhones)) {
          die(json_encode(array('stop'=>'Have no phones in worck table')));
        }

        foreach ($allCallingPhonesWork as $keyCallingPhone => $valueCallingPhone) {

          $obPhone = $this->getDoctrine()
            ->getRepository('AutoDialBundle:Phones')
            ->findBy([
              'phone'=>$valueCallingPhone->getPhone(),
              'active'=>'Y'
            ]);

          if (!empty($obPhone)) {
            $allCallingPhones[] = $obPhone[0];
          }
        }
        // unset($em);


        $rule = $this->getDoctrine()
          ->getRepository('AutoDialBundle:DialRuleArray')
          ->findBy([
            'dialBase'=>$dialId
          ]);
        if (empty($rule)) {
          die(json_encode(array('stop'=>'Have no finalRule')));
        }

        $arRule = unserialize(file_get_contents('/var/www/html/sym/call/var/sound/rule/'.$rule[0]->getArray()));
        // проверка
        $arBaseRuleNN = array();
        foreach ($arRule as $key => $value) {
          if (is_array($value)) {
            if ($value['type'] == 'text_to_speech') {
              $arBaseRuleNN[$value['rule_id']] = $value['value'];
            }
          }
        }

        foreach ($allCallingPhones as $nn => $obPhone) {

          $arMap = array(
            '{fio}' => $obPhone->getFio(),
            '{adress}' => $obPhone->getAddr(),
            '{opt1}' => $obPhone->getOpt1(),
            '{opt2}' => $obPhone->getOpt2(),
            '{opt3}' => $obPhone->getOpt3(),
          );

          foreach ($arBaseRuleNN as $keyRule => $valueText) {
            $textSpeech = $valueText[0];
            foreach ($arMap as $map => $mapValue) {
              $textSpeech = str_replace($map,$mapValue,$textSpeech);
            }
            $fileName = md5($textSpeech);

            $sound = $this->getDoctrine()
              ->getRepository('AutoDialBundle:Sound')
              ->findBy([
                'hash'=>$fileName
              ]);


            if (empty($sound)) {
              sleep(1);

              $this->generateSpeechFile($textSpeech, $this->soundPath.$fileName.'_old.wav');
              // преобразовать файл в сжатый вав
              $this->convertoToAsteriskFormat($this->soundPath.$fileName.'_old.wav', $this->soundPath.$fileName.'.wav');
              // записать по фтп
              $this->uploadFTPSound($fileName);
            }

            $phoneWave = new PhoneWav;
            $phoneWave->setPhone($obPhone->getPhone());
            $phoneWave->setDialRuleId($keyRule);
            $phoneWave->setSound($this->soundPath.$fileName);
            $phoneWave->setUpload('Y');
            $em = $this->getDoctrine()->getManager();
            $em->persist($phoneWave);
            $em->flush();
          }
        }
        // die(json_encode(array('stop'=>$arRule)));

        // формируем файлы для каждого номера индивидуально
        die(json_encode(array('continue'=>'file')));
      }

      if ($route == 'calling') {
        $answer = exec('/var/www/html/sym/call/bin/console daemon:call');
        die(json_encode(array('continue'=>'success','answer'=>$answer)));
      }


      // $answer = exec('/var/www/html/sym/call/bin/console daemon:call');
       //
      // return new JsonResponse('{"a":"aa"}');
      die(json_encode(array('route'=>$route))); //,'ans'=>$answer


    }

    function concatAnyWavFile($arrPath){
      // проверить что в папке нет итогового файла
      $count = count($arrPath);
      $inpWav= $outputName = '';
      foreach ($arrPath as $key => $value) {
        $inpWav.= $value.' -i ';
        $outputName.= $value;
      }
      $inpWav = mb_substr($inpWav,0,-3,"UTF-8");
      // $maskStr = '\'';
      // for ($i=0; $i < $count; $i++) {
      //   $maskStr.= '['.$i.':0]';
      // }
      $maskStr='concat=n='.$count.':v=0:a=1 -f WAV -vn -y /var/www/html/sym/call/var/sound/user/'.md5($outputName).".wav";

      $execStr = "ffmpeg -i ".$inpWav." -filter_complex ".$maskStr;
      exec($execStr);
      return '/var/www/html/sym/call/var/sound/user/'.md5($outputName);
    }













}
