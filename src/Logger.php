<?php
namespace Src;
use Src\Singleton;
use Analog\Analog;
use Analog\Handler\File;

// The logger could be saved in many ways, choosed File
// use Analog\Handler\Mail;
// use Analog\Handler\FirePHP;
// use Analog\Handler\PDO;
// use Analog\Handler\Redis;
// use Analog\Handler\Slackbot;
// use Analog\Handler\Buffer;


// encapsulate logger functionality
class Logger extends Singleton {
    private $logger = Analog::class;
    public function __construct(){
        $this->logger::handler(File::init('log.txt'));
    }

    public function writeLog(String $message){
        $this->logger::log($message);
    }

    public static function log(String $message){
        $logger = Logger::getInstance();
        $logger->writeLog($message);
    }
}
