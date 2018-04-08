<?php
/**
 * Copyright (C) Jyxon, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace Jyxon\DataTools\Operation;

/**
 * This class creates operational functions for versioning.
 */
class VersionTool
{
    /**
     * Contains the supplied version.
     *
     * @var string
     */
    private $version;

    /**
     * Contains the depth of the version.
     *
     * @var int
     */
    private $versionDepth;

    /**
     * Contains the expanded version.
     *
     * @var array
     */
    private $versionExpanded;

    /**
     * Constructor
     *
     * @param string $version
     */
    public function __construct(string $version)
    {
        $this->setVersion($version);
    }

    /**
     * Compares one version to another.
     *
     * @param VersionTool $version
     *
     * @return bool
     */
    public function versionCompare(VersionTool $version, string $operator): bool
    {
        return version_compare($this->getVersion(), $version->getVersion, $operator);
    }

    /**
     * Resets the object with a new version.
     *
     * @param string $version
     *
     * @return void
     */
    public function setVersion(string $version)
    {
        $this->versionDepth = null;
        $this->versionExpanded = null;
        $this->version = $version;
    }

    /**
     * Returns the version that is set within the VersionTool.
     *
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * __toString interpreter hook.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->getVersion();
    }

    /**
     * Returns the version depth.
     *
     * @return int
     */
    public function getVersionDepth(): int
    {
        if ($this->versionDepth == null) {
            $this->versionDepth = count($this->getExpandedVersion());
        }

        return $this->versionDepth;
    }

    /**
     * Returns the expanded version.
     *
     * @return array
     */
    public function getExpandedVersion(): array
    {
        if ($this->versionExpanded == null) {
            $this->versionExpanded = explode('.', $this->getVersion);
        }

        return $this->versionExpanded;
    }

    /**
     * Returns the version number at a certain depth.
     *
     * @param int $depth
     *
     * @return int
     */
    public function getVersionAt(int $depth): int
    {
        $expandedVersion = $this->getExpandedVersion();
        if (isset($expandedVersion[$depth])) {
            return intval($expandedVersion);
        }

        return false;
    }
}
