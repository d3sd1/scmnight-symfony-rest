<?php
namespace App\Command;

use React\Socket\ConnectionInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncSrvCommand extends Command
{
    protected function configure()
    {
        $this->setName('scm:syncsrv')
            ->setDescription('Starts sync server.')
            ->setHelp('Starts sync server on SCM central server.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /* Esto va con REACT, ya que REACT es para SOCKETS */
        $loop = \React\EventLoop\Factory::create();
        $socket = new \React\Socket\Server('127.0.0.1:8080', $loop);

        $socket->on('connection', function(ConnectionInterface $connection) use($output) {
            $output->write("CONECTADO CLIENTE");
            $connection->write('Hi!');
            $connection->on('data', function($data) use ($connection,$output){
                $output->write("datos cliente:".$data);
                $connection->write(strtoupper($data));
            });
        });

        echo "Listening on {$socket->getAddress()}\n";

        $loop->run();
    }
}