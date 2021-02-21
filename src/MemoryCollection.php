<?php

namespace Live\Collection;

/**
 * Memory collection
 *
 * @package Live\Collection
 */
class MemoryCollection implements CollectionInterface
{
    /**
     * Collection data
     *
     * @var array
     */
    protected $data;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->data = [];
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $index, $defaultValue = null)
    {
        if (!$this->has($index)) {
            return $defaultValue;
        }

        return $this->data[$index]['data'];
    }

    /**
     * {@inheritDoc}
     */
    public function set(string $index, $value, $expiration = null)
    {
        $this->data[$index]['data'] = $value;

        if ($expiration == null) {
            $expiration = strtotime("now + 5 minutes");
        } else {
            $expiration = strtotime("now ".$expiration." minutes");
        }

        $this->data[$index]['expiration'] = $expiration;
    }

    /**
     * {@inheritDoc}
     */
    public function has(string $index)
    {
        $validation = array_key_exists($index, $this->data);

        if (!$validation) {
            return false;
        }
        
        if (strtotime("now") >= $this->data[$index]['expiration']) {
            unset($this->data[$index]);
            return false;
        } else {
            return true;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        return count($this->data);
    }

    /**
     * {@inheritDoc}
     */
    public function clean()
    {
        $this->data = [];
    }
}
