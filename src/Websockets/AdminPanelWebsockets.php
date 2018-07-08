<?php

namespace App\Websockets;


use Psr\Container\ContainerInterface;
use Ratchet\ConnectionInterface;
use App\Websockets\ScmConnection;
use Ratchet\MessageComponentInterface;

class AdminPanelWebsockets implements \Ratchet\MessageComponentInterface
{
    protected $connections;
    private $container;
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->connections = new \SplObjectStorage;
    }

    public function getConnection($connId) {
        return array_filter(
            $this->connections,
            function ($e) use (&$connId) {
                return $e->connId == $connId;
            }
        );
    }
    public function onOpen(ConnectionInterface $conn)
    {
        $scmConnection = new ScmConnection($conn);
        $this->connections->attach($scmConnection);
    }
    function str_replace_first($from, $to, $content)
    {
        $from = '/'.preg_quote($from, '/').'/';

        return preg_replace($from, $to, $content, 1);
    }

    public function onMessage(ConnectionInterface $from, $channelJson)
    {
        try {
            /** @var \App\Websockets\Channel $channel */
            $channel = $this->container->get("jms_serializer")->deserialize($channelJson, 'App\Websockets\Channel', 'json');
            $channelInternalTmpName = ucfirst($channel->getName());
            $stillFriendlyName = true;
            while($stillFriendlyName)
            {
                $pos = strpos($channelInternalTmpName, "/");
                if($pos === false)
                {
                    $channelInternalFinalName = $channelInternalTmpName;
                    $stillFriendlyName = false;
                }
                else
                {
                    $toUpper = strtoupper(substr($channelInternalTmpName, $pos+1, 1));
                    $channelInternalTmpName = $this->str_replace_first('/', '',  substr_replace($channelInternalTmpName, $toUpper, $pos+1, 1));
                }
            }
            $fullClassName = "\App\WebsocketChannels\\".$channelInternalFinalName;
            $channelHandler = new $fullClassName($channel->getData());
            if($channelHandler->isSuscription())
            {
                $this->getConnection($from->resourceId)->addChannel(new Channel());
            }
            var_dump($this->getConnection($from->resourceId)->getChannels());
            $channelHandler->load();
            //TODO AQUI: el tema de las sesiones, canales y suscripciones.
        } catch (\Exception $e) {
            var_dump($e);
            echo "channel not found: ". $e->getMessage();
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->connections->detach(array_filter(
            $this->connections,
            function ($e) use (&$conn) {
                return $e->connId == $conn->resourceId;
            }
        ));
        echo "user discon";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
    }
}