<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Integration\Component;

use org\bovigo\vfs\vfsStream;

trait VirtualFileHelperTrait
{
    /**
     * @param string $fileName
     * @param string $contentsOfFile
     * @return string
     */
    protected function createVirtualFile(string $fileName, string $contentsOfFile)
    {
        $structure = [
            $fileName => $contentsOfFile
        ];
        $rootPath = vfsStream::setup(
            'root',
            NULL,
            $structure
        );

        return $rootPath->url().'/'.$fileName;
    }

    protected function createVirtualPath()
    {
        $structure = [
            'file' => 'contents'
        ];
        $rootPath = vfsStream::setup(
            'root',
            NULL,
            $structure
        );

        return $rootPath->url();
    }
}
