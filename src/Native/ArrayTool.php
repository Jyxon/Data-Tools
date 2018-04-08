<?php
/**
 * Copyright (C) Jyxon, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace Jyxon\DataTools\Native;

use Jyxon\DataTools\Operation\PathTool;

/**
 * This class creates new functionalities to maniupulate arrays.
 */
class ArrayTool
{
    /**
     * The array is expanded and multidimensional.
     *
     * @var integer
     */
    const STATUS_EXPANDED = 0;

    /**
     * The array is flat and one dimensional.
     *
     * @var integer
     */
    const STATUS_FLAT = 1;

    /**
     * Contains the array that requires tooling.
     *
     * @var array
     */
    private $array;

    /**
     * Determines if the array is flattened.
     *
     * @var int
     */
    private $arrayStatus;

    /**
     * Constructor
     *
     * @param array $array The array that needs to be loaded into the tool.
     * @param int $type The type of the array. Can either be ArrayTool::STATUS_EXPANDED or ArrayTool::STATUS_FLAT
     */
    public function __construct(array $array, int $type = self::STATUS_EXPANDED)
    {
        $this->array = $array;
        $this->arrayStatus = $type;
    }

    /**
     * Returns the array in it's current state.
     *
     * @return array
     */
    public function getArray(): array
    {
        return $this->array;
    }

    /**
     * Loads a new array into the tool.
     *
     * @return ArrayTool
     */
    public function setArray(array $array, int $type = self::STATUS_EXPANDED): ArrayTool
    {
        $this->array = $array;
        $this->arrayStatus = $type;
        return $this;
    }

    /**
     * Expands the array from the flattened form.
     *
     * @return ArrayTool
     */
    public function expandArray(): ArrayTool
    {
        if ($this->arrayStatus !== self::STATUS_EXPANDED) {
            $array = $this->array;
            $this->array = [];
            $this->arrayStatus = self::STATUS_EXPANDED;
            foreach ($array as $key => $value) {
                $pathExp = new PathTool($key);
                $this->setArrayValueByArrayPath($pathExp->getExpanded(), $value);
            }
        }

        return $this;
    }

    /**
     * Flattens the array.
     *
     * @return ArrayTool
     */
    public function flattenArray(): ArrayTool
    {
        if ($this->arrayStatus !== self::STATUS_FLAT) {
            $flat = [];
            foreach ($this->array as $key => $value) {
                $flat = array_merge($flat, $this->internalFlattenSubArray($key, $value));
            }

            $this->array = $flat;
            $this->arrayStatus = self::STATUS_FLAT;
        }

        return $this;
    }

    /**
     * Internal function to flatten the array.
     *
     * @param string $key
     * @param mixed $value
     *
     * @return array
     */
    private function internalFlattenSubArray(string $key, $value): array
    {
        $subArray = [];
        if (!is_array($value)) {
            return [$key => $value];
        }

        foreach ($value as $subKey => $subValue) {
            $subArray = array_merge($subArray, $this->internalFlattenSubArray($key . '/' . $subKey, $subValue));
        }

        return $subArray;
    }

    /**
     * Fetches a value in an array through a "/" separated path.
     *
     * @param string $path
     *
     * @return mixed
     */
    public function getByPath(string $path)
    {
        $pathExp = new PathTool($path);
        if ($this->arrayStatus === self::STATUS_FLAT) {
            return $this->array[$pathExp->getPath()];
        }

        return $this->getArrayValueByArrayPath($pathExp->getExpanded(), $this->getArray());
    }

    /**
     * Sets an entry by a path.
     *
     * @param string $path
     * @param mixed $value
     *
     * @return ArrayTool
     */
    public function setByPath(string $path, $value): ArrayTool
    {
        $pathExp = new PathTool($path);
        if ($this->arrayStatus === self::STATUS_FLAT) {
            $this->array[$pathExp->getPath()] = $value;
            return $this;
        }

        $this->setArrayValueByArrayPath($pathExp->getExpanded(), $value);
        return $this;
    }

    /**
     * Strips all empty strings from an array (array_filter, but explicitly with strings).
     *
     * @return ArrayTool
     */
    public function stripEmptyString(): ArrayTool
    {
        $this->setArray($this->coreStripEmptyString($this->getArray()));
        return $this;
    }

    /**
     * Does the core functionality for this operation, so it can be easily used within the tool itself.
     *
     * @param array $array
     *
     * @return array
     */
    private function coreStripEmptyString(array $array): array
    {
        return array_filter($array, (function ($var) {
            return !($var === '');
        }));
    }

    /**
     * Fetches the value within an array by the given array path.
     *
     * @return mixed
     */
    public function getArrayValueByArrayPath(array $path)
    {
        if ($this->arrayStatus === self::STATUS_EXPANDED) {
            $storage = $this->getArray();
            foreach ($path as $step) {
                $storage = &$storage[$step];
            }
            return $storage;
        }

        $path = implode('/', $path);
        $this->array[$path];
        return $this;
    }

    /**
     * Sets a value in the array by an array path.
     *
     * @param array $path
     * @param mixed $value
     *
     * @return ArrayTool
     */
    public function setArrayValueByArrayPath(array $path, $value): ArrayTool
    {
        if ($this->arrayStatus === self::STATUS_EXPANDED) {
            $array = $this->getArray();
            $storage = &$array;
            foreach ($path as $step) {
                if (!isset($storage[$step])) {
                    $storage = $this->forceSubArrayKey($storage, $step);
                }

                $storage = &$storage[$step];
            }

            $storage = $value;
            $this->setArray($array);
            return $this;
        }

        $path = implode('/', $path);
        $this->array[$path] = $value;
        return $this;
    }

    /**
     * Creates a subarray wether the mixed variable is an array or not.
     * If the mixed variable is an array it creates an entry.
     * If it is not an array it will create an array with the key set as a variable.
     *
     * @param mixed $mixed
     * @param string $key
     *
     * @return array
     */
    public function forceSubArrayKey($mixed, string $key): array
    {
        if (is_array($mixed)) {
            $mixed[$key] = [];
            return $mixed;
        }
        $mixed = [$key => ''];
        return $mixed;
    }
}
