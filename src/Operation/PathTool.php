<?php
/**
 * Copyright (C) Jyxon, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace Jyxon\DataTools\Operation;

/**
 * This class creates operational functions for paths.
 */
class PathTool
{
    /**
     * Contains the path that was set in the constructor.
     *
     * @var string
     */
    private $path;

    /**
     * Contains the expanded path.
     *
     * @var array
     */
    private $expPath;

    /**
     * Determines wether a supplied query string has to be parsed.
     *
     * @var bool
     */
    private $parseQuery;

    /**
     * Contains the queries that are set in the string.
     *
     * @var array
     */
    private $queries;

    /**
     * Contains the previous path object.
     *
     * @var PathTool
     */
    private $previousPath;

    /**
     * Constructor
     *
     * @param string $path
     * @param bool $parseQuery
     */
    public function __construct(string $path, bool $parseQuery = false)
    {
        $this->parseQuery = $parseQuery;
        $this->setPath($path);
    }

    /**
     * Returns the path that is set.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Sets the path that needs to be parsed.
     *
     * @param string $path
     *
     * @return void
     */
    public function setPath(string $path)
    {
        $this->previousPath = clone $this;
        $this->internalSetPath($path);
    }

    /**
     * Internally sets the path. Contains the logic of setting a path.
     *
     * @param string $path
     *
     * @return void
     */
    private function internalSetPath(string $path)
    {
        $this->queries = null;
        $this->path = $this->correctPath($path);
        $this->expPath = explode('/', $this->path);
    }

    /**
     * Returns the previous path.
     *
     * @return PathTool
     */
    public function getPreviousPath(): PathTool
    {
        if ($this->previousPath !== null) {
            return $this->previousPath;
        }

        return false;
    }

    /**
     * Reverts the current object to the last.
     *
     * @return void
     */
    public function revertToPreviousPath()
    {
        if ($this->previousPath !== null) {
            $previousPath = $this->getPreviousPath();
            $this->internalSetPath($previousPath->getPath());
            $this->previousPath = $previousPath->getPreviousPath();
        }
    }

    /**
     * Returns the expanded path.
     *
     * @return array
     */
    public function getExpanded(): array
    {
        return $this->expPath;
    }

    /**
     * Does standard corrections on the path.
     *
     * @param string $path
     *
     * @return string Returns the corrected path.
     */
    private function correctPath(string $path): string
    {
        $pos = strpos($path, '?');
        if ($pos !== false) {
            $tempPath = substr($path, 0, $pos);
            if ($this->parseQuery === true) {
                $queryString = substr($path, $pos);
                $this->parseQueryString($queryString);
            }

            $path = $tempPath;
        }

        $path = str_replace('\\', '/', $path);
        while (strpos($path, '//') !== false) {
            $path = str_replace('//', '/', $path);
        }

        $path = trim($path);
        $prepath = '';
        if (substr($path, 0, 1) == '/') {
            $prepath = '/';
        }

        $path = $prepath . trim($path, '/');
        return $path;
    }

    /**
     * Parses and stores the query string.
     *
     * @param string $queryString
     *
     * @return void
     */
    private function parseQueryString(string $queryString)
    {
        $queryString = trim($queryString, '?');
        $params = explode('&', $queryString);
        foreach ($params as $param) {
            $comb = explode('=', $param);
            if (count($comb) == 2) {
                $this->queries[urldecode($comb[0])] = urldecode($comb[1]);
            }
        }
    }

    /**
     * Merges multiple paths into the current path.
     *
     * @param string ...$param
     *
     * @return void
     */
    public function mergePaths()
    {
        $path = $this->getPath();
        foreach (func_get_args() as $slug) {
            $path = $path . '/' . trim($slug, '/');
        }

        $this->setPath($path);
    }

    /**
     * Returns an associative array of the parameters from the path.
     *
     * @return array
     */
    public function getParameters(): array
    {
        return $this->queries;
    }
}
