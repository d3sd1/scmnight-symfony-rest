<?php

namespace App\Command;

use React\Socket\Server;
use React\EventLoop\Factory;
use React\Socket\SecureServer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Websockets\AdminPanelWebsockets;

class WebsocketsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('scm:server')
            ->setDescription('Starts websocket server.')
            ->setHelp('Starts websocket server for admin panel.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /* Esto va con RATCHET, ya que REACT es para SOCKETS y RATCHET para WEBSOCKETS */
        $loop   = \React\EventLoop\Factory::create();

        /*
         * $pusher = new Beheren\Pusher;

        // ZMQ binding
        $context = new \React\ZMQ\Context($loop);
        $pull = $context->getSocket(ZMQ::SOCKET_PULL);
        $pull->bind('tcp://0.0.0.0:5555'); // Binding to 127.0.0.1 means the only client that can connect is itself (127.0.0.1 || 0.0.0.0)
        $pull->on('error', array($pusher, 'onError'));
        $pull->on('message', array($pusher, 'onLogIn'));
         */

        // Set up secure React server

        if($this->getContainer()->get("kernel")->getEnvironment() == "dev")
        {
            $webSock = new \React\Socket\Server('127.0.0.1:9325', $loop);
        }
        else{
            $webSock = new \React\Socket\SecureServer(
                new \React\Socket\Server('127.0.0.1:9325',$loop),
                $loop,
                array(
                    'local_cert' => 'xxxx/combined.pem',
                    //'allow_self_signed' => true,
                    'verify_peer' => false
                )
            );
        }

        // Ratchet magic
        $webServer = new \Ratchet\Server\IoServer(
            new \Ratchet\Http\HttpServer(
                new \Ratchet\WebSocket\WsServer(new AdminPanelWebsockets($this->getContainer()))
            ),
            $webSock
        );

        $loop->run();
    }
}