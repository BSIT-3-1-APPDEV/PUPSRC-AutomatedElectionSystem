<?php

/**
 * File function utilities.
 *
 */
class FileUtils
{
    /**
     * Replace forward slashes with appropriate directory separator in a file path for current operating system.
     *
     * @param string $path The file path to transform.
     * @return string The transformed file path.
     */
    public static function normalizeFilePath($path)
    {

        return str_replace('/', DIRECTORY_SEPARATOR, $path);
    }
}
