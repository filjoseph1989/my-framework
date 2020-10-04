<?php

namespace Core;

// Todo-1
class Files
{
    private array $file;

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
            throw new RuntimeException('Invalid parameters.');
            $this->error = true;
            $this->errorMessage = 'Invalid parameters.';
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
                $this->errorMessage = 'The file uploaded with success.';
                break;

            case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('No file sent.');
                $this->error = true;
                $this->errorMessage = 'No file sent';

            case UPLOAD_ERR_NO_TMP_DIR:
                throw new RuntimeException('Missing a temporary folder.');
                $this->error = true;
                $this->errorMessage = 'Missing a temporary folder';

            case UPLOAD_ERR_CANT_WRITE:
                throw new RuntimeException('Failed to write file to disk.');
                $this->error = true;
                $this->errorMessage = 'Failed to write file to disk';

            case UPLOAD_ERR_EXTENSION:
                throw new RuntimeException('A PHP extension stopped the file upload.');
                $this->error = true;
                $this->errorMessage = 'A PHP extension stopped the file upload';

            case UPLOAD_ERR_INI_SIZE:
                throw new RuntimeException('The uploaded file exceeds the upload_max_filesize directive in php.ini');
                $this->error = true;
                $this->errorMessage = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';

            case UPLOAD_ERR_PARTIAL:
                throw new RuntimeException('The uploaded file was only partially uploaded');
                $this->error = true;
                $this->errorMessage = 'The uploaded file was only partially uploaded';

            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('Exceeded filesize limit.');
                $this->error = true;
                $this->errorMessage = 'Exceeded filesize limit.';

            default:
                throw new RuntimeException('Unknown errors.');
                $this->error = true;
                $this->errorMessage = 'Unknown errors.';
        }

        return $this->error;
    }
}
