<?php

namespace Live\Collection;

/**
 * File collection
 *
 * @package Live\Collection
 */
class FileCollection implements FileInterface
{
    /**
     * Collection data
     *
     * @var array
     */
    protected $data;

    /**
     * file name data
     *
     * @var string
     */
    protected $fileName;

    /**
     * file content data
     *
     * @var string
     */
    protected $fileData;

    /**
     * {@inheritDoc}
     */
    public function __construct($fileName)
    {
        $this->fileName = $fileName;
        $this->data[$fileName] = [];        
    }

    /**
     * {@inheritDoc}
     */
    public function get($fileName = null)
    {
        
        if ($fileName == null) {
            return $this->data[$this->fileName]['data'];
        } elseif ($this->has($fileName)) {

            $this->fileName = $fileName;

            if (!isset($this->data[$fileName])) {
                $this->data[$fileName]['data'] = file_get_contents('files/'.$fileName);
                return $this->data[$fileName]['data'];
            } else {
                return $this->data[$fileName]['data'];
            }
    
        } else {
            return false;
        }

    }

    /**
     * {@inheritDoc}
     */
    public function set($value, $expiration = null)
    {
        $this->data[$this->fileName]['data'] = $value;

        if ($expiration == null) {
            $expiration = strtotime("now + 5 minutes");
        } else {
            $expiration = strtotime("now ".$expiration." minutes");
        }

        $this->data[$this->fileName]['expiration'] = $expiration;
        $this->data[$this->fileName]['data'] = $value;
        $fo = fopen('files/'.$this->fileName, "a+");
        fwrite($fo, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function setNew($fileName, $value, $expiration = null)
    {
        $this->data[$fileName]['data'] = $value;
        $this->fileName = $fileName;

        if ($expiration == null) {
            $expiration = strtotime("now + 5 minutes");
        } else {
            $expiration = strtotime("now ".$expiration." minutes");
        }

        $this->data[$fileName]['expiration'] = $expiration;
        $this->data[$fileName]['data'] = $value;
        $fo = fopen('files/'.$fileName, "a+");
        fwrite($fo, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function has(string $fileName)
    {

        $validation = file_exists('files/'.$fileName);

        if (!$validation) {
            return false;
        }
        
        if (strtotime("now") >= $this->data[$fileName]['expiration']) {
            unset($this->data[$fileName]);
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
        $directories = scandir('files/');
        unset($directories[0]);
        unset($directories[1]);
        foreach ($directories as $dir) {
            unlink('files/'.$dir);
        }

        $this->data = [];
    }
}
