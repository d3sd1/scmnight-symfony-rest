<?php
namespace App\Command;

use React\Socket\ConnectionInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncClientCommand extends Command
{
    protected function configure()
    {
        $this->setName('scm:syncclient')
            ->setDescription('Starts sync server.')
            ->setHelp('Starts sync server on SCM central server.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /* Esto va con REACT, ya que REACT es para SOCKETS */
        $loop = \React\EventLoop\Factory::create();
        $connector = new \React\Socket\Connector($loop);
        $connector
            ->connect('127.0.0.1:8080')
            ->then(
                function (ConnectionInterface $conn) use($output)  {
                    $output->write("CONECTADO");
                    $conn->write("test");
                    /*
                     * Cerrar conexión.
                     */
                    $conn->close();
                },
                function (Exception $exception) use ($loop,$output){
                    $output->write("Cannot connect to server: " . $exception->getMessage());
                    $loop->stop();
                });

        $loop->run();
    }
}