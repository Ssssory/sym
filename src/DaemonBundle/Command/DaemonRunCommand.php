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
use AutoDialBundle\Entity\Sound;

class DaemonRunCommand extends ContainerAwareCommand
{
    private   $dc;
    private   $ami;
    protected $pid;

    protected $timeFormat   = 'd-m-Y H:i:s';
    protected $timeBDFormat = 'H:i:s';
    protected $dateBDFormat = 'd-m-Y';

    public $path         = '/var/www/html/sym/call/var/daemon/';
    public $sys          = '/var/www/html/sym/call/var/daemon/sys/';
    public $log          = '/var/www/html/sym/call/var/daemon/log/';
    protected $soundPath = '/var/www/html/sym/call/var/sound/';

    protected $yandexKey = 'f03ce46a-4293-4210-a29a-58f0336d0d8d';

    protected $live = true;

    protected function configure()
    {
        $this
            ->setName('daemon:run')
            ->setDescription('Start daemon')
//            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
//            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
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


        $resDir = opendir($this->sys);
        while (false !== ($file = readdir($resDir))) {

            if ($file != '.' && $file != '..') {
                $output->writeln("process already started\n");
                return false;
            }
        }
        closedir($resDir);

        if ($this->isStop()) {
            $output->writeln("File stop is exist\n");
            return false;
        }
//        $this->initPid();
//        $this->start();
//        $this->unsetPid();
        $this->lookBase();

//        $em = $this->getContainer()->get('doctrine')->getRepository('AutoDialBundle:Dial')
//            ->find(1);
//        $output->writeln(var_dump($em->getId()));

    }

    function start(){
        $this->ami->connect();
        if ($this->ami->ini["con"])
        {
            $this->ami->init();

//            $this->ami->write("Action: Status\n");
//            print_r($this->ami->read());
            // echo '!!!!!!!!!!!!!-----!!!!!!!!!!!!!!';
            $output->writeln('Start daemon');
            $output->writeln('================================');

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
            if (!empty($temp)){
                $this->writeInBase($temp);
                $this->logSelf($temp);
            }


            // $this->getGoodFile();
        }
        if ($this->ami->ini["con"]) {
            $this->ami->disconnect();
        }
    }

    function isStop(){
        $resDir = opendir($this->path);
        while (false !== ($file = readdir($resDir))) {
            if ($file == 'stop') {
                return true;
            }
        }
        return false;
    }

    /**
     * создать управляющий файл
     */
    function initPid(){
        $prosPid = getmypid();
        if ($prosPid) {
            $this->pid = $prosPid;
        }else{
            $this->live = false;
        }
        file_put_contents($this->sys.$prosPid,'');
    }

    /**
     * удалить управляющий файл
     */
    function unsetPid(){
        unlink($this->sys.$this->pid);
        $output->writeln('Daemon stop');
        $output->writeln('================================');
    }

    /**
     * запись в логи
     */
    function logSelf($input){
        file_put_contents($this->log.'log'.date('ymd').'.log',date('h:i:s').' '.var_export($input,true) .PHP_EOL,FILE_APPEND);
    }

    /**
     * запись логов в базу
     */
    function writeInBase($array){

        foreach ($array as $key => $value){

            if (empty($value)){
                continue;
            }
            if (!empty($value[0])){
                foreach ($value as $innerKey=> $innerValue){

                    if (empty($innerValue)){
                        continue;
                    }
                    if (empty($innerValue['Event'])){
                        $innerValue["Event"] = '';
                    }
                    if (empty($innerValue['Uniqueid'])){
                        $innerValue['Uniqueid'] = '';
                    }

                    $inputEvents = new AmiInputEvents();

                    $inputEvents->setUid($innerValue['Uniqueid']);
                    $inputEvents->setAction($innerValue['Event']);
                    $inputEvents->setDateIn(date($this->dateBDFormat));
                    $inputEvents->setTimeIn(date($this->timeBDFormat));
                    $inputEvents->setArray(stripcslashes(serialize($innerValue)));

                    $em = $this->dc->getManager();
                    $em->persist($inputEvents);
                    $em->flush();
                }
            }else{
                if (empty($value['Event'])){
                    $value['Event'] = '';
                }
                if (empty($value['Uniqueid'])){
                    $value['Uniqueid'] = '';
                }

                $inputEvents = new AmiInputEvents();

                $inputEvents->setUid($value['Uniqueid']);
                $inputEvents->setAction($value['Event']);
                $inputEvents->setDateIn(date($this->dateBDFormat));
                $inputEvents->setTimeIn(date($this->timeBDFormat));
                $inputEvents->setArray(stripcslashes(serialize($value)));

                $em = $this->dc->getManager();
                $em->persist($inputEvents);
                $em->flush();
            }
        }
    }


    public function lookBase(){
        $currentTimeDate = time();
        $arWork = array();
//        $allRules = $this->dc->getRepository('AutoDialBundle:Dial')
//            ->findAll();
        $allRules = $this->dc->getEntityManager()
            ->createQuery('
              SELECT p FROM AutoDialBundle:Dial p
              WHERE p.active = :active
          ')->setParameters(array('active'=>'Y'));
            // заглушить пустоту
        foreach ($allRules->getResult() as $item) {
//            print_r($item->getID);
//            echo strtotime($item->getDateStart()) ." ->".$item->getDateStart()."\n";
            if (strtotime($item->getDateStart()) > $currentTimeDate ){
                continue;
            }else{
//                echo "пора\n";
                $arWork[] = $item->getId();
            }
        }

        $currentDial = array_shift($arWork);
        $currentDial = $this->dc->getRepository('AutoDialBundle:Dial')
            ->find($currentDial);
        if (empty($currentDial->getDateCallStart())){
            $allPhones = $this->dc->getEntityManager()
                ->createQuery('
              SELECT p FROM AutoDialBundle:Phones p
              WHERE p.active = :active AND p.dial = :dial
          ')->setParameters(array('active'=>'Y','dial'=>$currentDial->getId()));

            $arAllPhones = $allPhones->getResult();
            $arAddr = $arPhones = array();
            foreach ($arAllPhones as $onePhone){
                $timeArray = array();
                $timeArray['addr'] =  $this->putAddrBase($onePhone->getAddr()) ;
                $timeArray['phone'] = $onePhone->getPhone();

                $arPhones[] = $timeArray;
            }
            // залил все файлы на сервер
            // записать в обзвон, что он стартовал
            //
            $this->ami->connect();
            $this->ami->init();
            foreach ($arPhones as $item) {
                $this->ami->originateAutoDialNumber($item['phone'],$item['addr']);
            }


            print_r($arPhones);
        }
        //если есть дата начала обзвона, то ничего не делаем.

        // если есть дата окончания обзвона и есть все результаты звонков
        // переносим дату окончания обзвона на дату последнего звонка
        // деактивируем обзвон.

    }

    public function putAddrBase($str){


        $result = 'empty';
        // приходит на вход в метод
        // $text = 'Улица набережная дом 5';
        // переписать сокращения
        // убрать точки
        $text = mb_strtolower($str,'utf-8');
        $fileName = md5($text);

        // если есть в базе, получить имя.
        $sound = $this->dc
            ->getRepository('AutoDialBundle:Sound')
            ->findBy([
                'hash'=>$fileName
            ]);
        if (!empty($sound)) {
            // add to dial
            if (file_exists($this->soundPath.$fileName.'.wav')){
                $result = $this->soundPath.$fileName.'.wav';
            }else{
                die('file in base, but have no on harddisk');
            }

        }else{
            sleep(1);
            // если нет в базе, скачать файл
            $this->generateSpeechFile($text, $this->soundPath.$fileName.'_old.wav');
            // преобразовать файл в сжатый вав
            $this->convertoToAsteriskFormat($this->soundPath.$fileName.'_old.wav', $this->soundPath.$fileName.'.wav');
            // записать по фтп
            $tempp = $this->uploadFTPSound($fileName);

            // записать в базу
            if (file_exists($this->soundPath.$fileName.'.wav')){
                $newSound = new Sound;

                $newSound->setName($text);
                $newSound->setHash($fileName);
                $newSound->setPath($this->soundPath.$fileName.'.wav');
                $em = $this->dc->getManager();
                $em->persist($newSound);
                $em->flush();
            }
            $result = $this->soundPath.$fileName.'.wav';
        }

//        return array("filename"=>$fileName,"path"=>$result);
        return $fileName;
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

}
