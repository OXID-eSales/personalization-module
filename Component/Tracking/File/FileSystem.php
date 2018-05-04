<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Component\Tracking\File;

use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem as SymfonyFileSystem;

class FileSystem
{
    private $filesystem;

    public function __construct(SymfonyFileSystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param string $pathToDirectory
     *
     * @return bool
     */
    public function createDirectory($pathToDirectory)
    {
        try {
            $this->filesystem->mkdir($pathToDirectory);
            return true;
        } catch (IOException $exception) {
            return false;
        }
    }

    /**
     * @param string $path
     * @return bool
     */
    public function isWritable($path)
    {
        return is_writable($path);
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    public function isFilePresent($path)
    {
        return $this->filesystem->exists($path);
    }
}
