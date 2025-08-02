<?php
/**
 * This file is part of PHPUnit resources helpers library
 *
 * @copyright   Copyright (c) Erwane BRETON
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */
declare(strict_types=1);

namespace ResourceHelper;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Helper for tests resources files.
 */
class File
{
    /**
     * Look in $_paths for file resource and return path.
     *
     * @param string $path Resource name or path.
     * @return string
     * @throws \InvalidArgumentException
     */
    public static function getPath(string $path): string
    {
        $resourcePath = ResourceHelper::getBaseDir() . $path;
        if (!is_file($resourcePath)) {
            throw new InvalidArgumentException(sprintf('Path "%s" not found', $path));
        }

        return $resourcePath;
    }

    /**
     * Get resource file info.
     *
     * @param string $path Resource name or path.
     * @return array
     */
    public static function getInfo(string $path): array
    {
        $resourcePath = self::getPath($path);

        $fileInfo = pathinfo($resourcePath);
        $hash = hash_file('sha1', $resourcePath);

        return [
            'path' => $resourcePath,
            'filename' => $fileInfo['basename'],
            'hash' => $hash,
        ];
    }

    /**
     * Get resources file content.
     *
     * @param string $path Resource name or path.
     * @return string
     */
    public static function getContent(string $path): string
    {
        return file_get_contents(self::getPath($path));
    }

    /**
     * Get resources file infos with a copy of file resource.
     *
     * @param string $path Resource name or path.
     * @param \PHPUnit\Framework\TestCase $test Running test
     * @return array
     */
    public static function getCopy(string $path, TestCase $test): array
    {
        $infos = self::getInfo($path);
        $resourcePath = $infos['path'];

        $infos['path'] = ResourceHelper::createTmpTestDir($test) . $infos['filename'];

        copy($resourcePath, $infos['path']);

        return $infos;
    }
}
