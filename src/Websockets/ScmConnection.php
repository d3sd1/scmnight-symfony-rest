<?php

namespace App\Websockets;


use Ratchet\ConnectionInterface;

class ScmConnection implements ConnectionInterface
{

    private $connId;
    private $conn;
    private $channels;
    public function __construct(ConnectionInterface $conn)
    {
        $this->connId = $conn->resourceId;
        $this->conn = $conn;
        $this->channels = array();
    }

    /**
     * @return mixed
     */
    public function getConnId()
    {
        return $this->connId;
    }

    /**
     * @param mixed $connId
     */
    public function setConnId($connId): void
    {
        $this->connId = $connId;
    }

    /**
     * @return ConnectionInterface
     */
    public function getConn(): ConnectionInterface
    {
        return $this->conn;
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function setConn(ConnectionInterface $conn): void
    {
        $this->conn = $conn;
    }

    /**
     * @return mixed
     */
    public function getChannels()
    {
        return $this->channels;
    }

    /**
     * @param mixed $channels
     */
    public function setChannels($channels): void
    {
        $this->channels = $channels;
    }

    /**
     * @param mixed $channel
     */
    public function addChannel($channel): void
    {
        array_push( $this->channels,$channel);
    }

    /**
     * @param mixed $channel
     */
    public function rmvChannel($channel): void
    {
        $this->channels->detach(array_filter(
            $this->channels,
            function ($e) use (&$channel) {
                return $e->getName() == $channel->getName();
            }
        ));
        array_push( $this->channels,$channel);
    }


    /**
     * Send data to the connection
     * @param  string $data
     * @return \Ratchet\ConnectionInterface
     */
    function send($data)
    {

    }

    /**
     * Close the connection
     */
    function close()
    {

    }
}