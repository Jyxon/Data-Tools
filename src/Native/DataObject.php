<?php
/**
 * Copyright (C) Jyxon, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace Jyxon\DataTools\Native;

/**
 * Creates a simple object to store data.
 * Can also be used as a foundation for an object that requires storage with external exposure.
 */
class DataObject
{
    /**
     * Contains the data set on the object.
     *
     * @var array
     */
    private $data;

    /**
     * Sets data on the object.
     *
     * @param string $key
     * @param mixed $value
     */
    public function setData(string $key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Retrieves the value of the object.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getData(string $key = '')
    {
        if ($key == '') {
            return $this->data;
        }

        return $this->data[$key];
    }

    /**
     * Check wether the value is set or not.
     *
     * @param string $key
     *
     * @return boolean
     */
    public function hasData(string $key): bool
    {
        return isset($this->data[$key]);
    }
}
