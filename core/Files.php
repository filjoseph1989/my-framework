<?php

namespace Core;

/**
 * File upload handler class
 *
 * @author Fil Beluan
 */
class Files
{
    private array $file;
    private array $errorMessage = [];

    public function __construct($file)
    {
        $this->file = $file;
        self::setProperty();
    }

    /**
     * return file name
     * @return string|boolean
     */
    public function name()
    {
        if (isset($this->name)) {
            return $this->name;
        }
        return false;
    }

    /**
     * Return file type
     * @return string|boolean
     */
    public function type()
    {
        if (isset($this->type)) {
            return $this->type;
        }
        return false;
    }

    /**
     * Return uploaded temp name
     * @return string|boolean
     */
    public function tmp_name()
    {
        if (isset($this->tmp_name)) {
            return $this->tmp_name;
        }
        return false;
    }

    /**
     * Return errors
     * @return string|boolean
     */
    public function errors()
    {
        if (isset($this->errors)) {
            return $this->errors;
        }
        return false;
    }

    /**
     * Return boolean of error
     * @return boolean
     */
    public function error()
    {
        if (isset($this->error)) {
            return $this->error;
        }
        return false;
    }

    /**
     * Return error message or false
     * @return string|boolean
     */
    public function errorMessage()
    {
        if (isset($this->errorMessage)) {
            return $this->errorMessage;
        }
        return false;
    }

    /**
     * Return file size
     * @return int|boolean
     */
    public function size()
    {
        if (isset($this->size)) {
            return $this->size;
        }
        return false;
    }

    /**
     * Set property
     * @return void
     */
    private function setProperty()
    {
        if (!isset($this->file['error']) || is_array($this->file['error'])) {
            throw new \RuntimeException('Invalid parameters.');
            $this->error        = true;
            $this->errorMessage[] = 'Invalid parameters.';
            return false;
        }

        if (self::hasFileErrors()) {
            return false;
        }

        foreach ($this->file as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Check every error
     * @return boolean
     */
    private function hasFileErrors()
    {
        $this->error = false;

        switch ($this->file['error']) {
            case UPLOAD_ERR_OK:
                $this->errorMessage[] = 'The file uploaded with success.';
                break;

            case UPLOAD_ERR_NO_FILE:
                $this->error = true;
                $this->errorMessage[] = 'No file sent';
                break;

            case UPLOAD_ERR_NO_TMP_DIR:
                $this->error = true;
                $this->errorMessage[] = 'Missing a temporary folder';
                break;

            case UPLOAD_ERR_CANT_WRITE:
                $this->error = true;
                $this->errorMessage[] = 'Failed to write file to disk';
                break;

            case UPLOAD_ERR_EXTENSION:
                $this->error = true;
                $this->errorMessage[] = 'A PHP extension stopped the file upload';
                break;

            case UPLOAD_ERR_INI_SIZE:
                $this->error = true;
                $this->errorMessage[] = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
                break;

            case UPLOAD_ERR_PARTIAL:
                $this->error = true;
                $this->errorMessage[] = 'The uploaded file was only partially uploaded';
                break;

            case UPLOAD_ERR_FORM_SIZE:
                $this->error = true;
                $this->errorMessage[] = 'Exceeded filesize limit.';
                break;

            default:
                $this->error = true;
                $this->errorMessage[] = 'Unknown errors.';
                break;
        }

        return $this->error;
    }
}
