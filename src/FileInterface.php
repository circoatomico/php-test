<?php

namespace Live\Collection;

/**
 * File interface
 *
 * @package Live\Collection
 */
interface FileInterface
{

    /**
     * Set the file name to read/write
     *
     * @param mixed $fileName
     * @return void
     */
    public function __construct($fileName);

    /**
     * Returns the value from the specified file
     *
     * @param mixed $fileName
     * @return mixed
     */
    public function get($fileName = null);

    /**
     * Adds a content to a file
     *
     * @param mixed $value
     * @param mixed $expiration
     * @return void
     */
    public function set($value, $expiration = null);

    /**
     * Adds a content to a new file
     *
     * @param string $fileName
     * @param mixed $value
     * @param mixed $expiration
     * @return void
     */
    public function setNew($fileName, $value, $expiration = null);

    /**
     * Checks whether the file exists in the /files directory
     *
     * @param mixed $fileName
     * @return boolean
     */
    public function has(string $fileName);

    /**
     * Returns the count of items in the /files directory
     *
     * @return integer
     */
    public function count(): int;

    /**
     * Cleans the /files directory
     *
     * @return void
     */
    public function clean();
}
