<?php
/**
 * Copyright (C) Jyxon, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace Jyxon\DataTools\Native;

use ReflectionClass;
use ReflectionParameter;

/**
 * This class creates a layer over the native ReflectionClass.
 */
class ReflectorTool extends ReflectionClass
{
    /**
     * Unuseable type for reflection.
     *
     * @var integer
     */
    const TYPE_UNKNOWN = 0;

    /**
     * Interface type.
     *
     * @var integer
     */
    const TYPE_INTERFACE = 1;

    /**
     * Class type.
     *
     * @var integer
     */
    const TYPE_CLASS = 2;

    /**
     * Trait type.
     *
     * @var integer
     */
    const TYPE_TRAIT = 3;

    /**
     * Contains the type of the reflected class.
     *
     * @var int
     */
    private $type = 0;

    /**
     * Constructor
     *
     * @param string $namespace
     */
    public function __construct(string $namespace)
    {
        $this->type = $this->internalGetType($namespace);
        if ($this->type) {
            parent::__construct($namespace);
        }
    }

    /**
     * Returns the type of the object as defined in the constants.
     *
     * @return int This can be ReflectorTool::TYPE_UNKNOWN, ReflectorTool::TYPE_INTERFACE, ReflectorTool::TYPE_CLASS or ReflectorTool::TYPE_TRAIT.
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * Internal function to determine wether a class can even be used for reflection.
     *
     * @param string $namespace
     *
     * @return int
     */
    private function internalGetType(string $namespace): int
    {
        if (interface_exists($namespace)) {
            return self::TYPE_INTERFACE;
        }

        if (trait_exists($namespace)) {
            return self::TYPE_TRAIT;
        }

        if (class_exists($namespace)) {
            return self::TYPE_CLASS;
        }

        return self::TYPE_UNKNOWN;
    }

    /**
     * Fetches the constructor arguments.
     *
     * @return bool|ReflectionParameter[]
     */
    public function getConstructorArgs()
    {
        if ($this->isInstantiable()) {
            return $this->getMethodArguments('__construct');
        }

        return false;
    }

    /**
     * Fetches the arguments of a method.
     *
     * @param string $methodName
     *
     * @return bool|ReflectionParameter[]
     */
    public function getMethodArguments(string $methodName)
    {
        if ($this->hasMethod($methodName)) {
            $method = $this->getMethod($methodName);
            if ($method->getNumberOfParameters() > 0) {
                return $method->getParameters();
            }
        }

        return false;
    }
}
