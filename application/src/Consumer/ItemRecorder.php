<?php


namespace App\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;


class ItemRecorder implements ConsumerInterface
{
//    private $filesystem;
//    private $parameterBag;
//
//    public function __construct(ContainerBagInterface $parameterBag, Filesystem $filesystem)
//    {
//        $this->filesystem = $filesystem;
//        $this->parameterBag = $parameterBag;
//    }


    /**
     * @var AMQPMessage $msg
     * @return void
     */
    public function execute(AMQPMessage $msg)
    {
//        $this->filesystem->dumpFile($this->parameterBag->get('kernel.project_dir') . '/storage/items22.txt', "HELLO HOE's");
    }
}