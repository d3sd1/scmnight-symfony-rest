<?php
/**
 * Created by PhpStorm.
 * User: Andrei
 * Date: 08/07/2018
 * Time: 21:07
 */

namespace App\Websockets;
use JMS\Serializer\Annotation as JMS;


class Channel
{
    /**
     * @JMS\Type("string")
     */
    private $name;
    /**
     * @JMS\Type("array")
     */
    private $data;
    /**
     * @JMS\Type("string")
     */
    private $dataClassName;

    public function __construct($name = null,$data = null,$dataClassName = null)
    {
        $this->name = $name;
        $this->data = $data;
        $this->dataClassName = $dataClassName;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data): void
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getDataClassName()
    {
        return $this->dataClassName;
    }

    /**
     * @param mixed $dataClassName
     */
    public function setDataClassName($dataClassName): void
    {
        $this->dataClassName = $dataClassName;
    }

}