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

    # return file name
    public function name(): string|bool
    {
        if (isset($this->name)) {
            return $this->name;
        }
        return false;
    }

    # Return file type
    public function type(): string|bool
    {
        if (isset($this->type)) {
            return $this->type;
        }
        return false;
    }

    # Return uploaded temp name
    public function tmp_name(): string|bool
    {
        if (isset($this->tmp_name)) {
            return $this->tmp_name;
        }
        return false;
    }

    # Return errors
    public function errors(): string|bool
    {
        if (isset($this->errors)) {
            return $this->errors;
        }
        return false;
    }

    # Return boolean of error
    public function error(): bool
    {
        if (isset($this->error)) {
            return $this->error;
        }
        return false;
    }

    # Return error message or false
    public function errorMessage(): string|bool
    {
        if (isset($this->errorMessage)) {
            return $this->errorMessage;
        }
        return false;
    }

    /**
     * Set a prefix error message
     * @param  string $prefix
     */
    public function setPrefixErrorMessage(string $prefix): object|bool
    {
        if (isset($this->errorMessage['description'])) {
            $this->errorMessage['description'] = "{$prefix} {$this->errorMessage['description']}";
            return $this;
        }
        return false;
    }

    # Return prefixed error message
    public function getPrefixErrorMessage(): string
    {
        if (isset($this->errorMessage['description'])) {
            return $this->errorMessage['description'];
        }
        return '';
    }

    # Return file size
    public function size(): int|bool
    {
        if (isset($this->size)) {
            return $this->size;
        }
        return false;
    }

    # Set property
    private function setProperty(): void
    {
        if (!isset($this->file['error']) || is_array($this->file['error'])) {
            throw new \RuntimeException('Invalid parameters.');
            $this->error = true;
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

    # Check every error
    private function hasFileErrors(): bool
    {
        $this->error = false;

        switch ($this->file['error']) {
            case UPLOAD_ERR_OK:
                $this->errorMessage = [
                    'type'        => 'SUCCESS',
                    'description' => 'The file uploaded with success.'
                ];
                break;

            case UPLOAD_ERR_NO_FILE:
                $this->error = true;
                $this->errorMessage = [
                    'type'        => 'UPLOAD_ERR_NO_FILE',
                    'description' => 'No file sent'
                ];
                break;

            case UPLOAD_ERR_NO_TMP_DIR:
                $this->error = true;
                $this->errorMessage = [
                    'type'        => 'UPLOAD_ERR_NO_TMP_DIR',
                    'description' => 'Missing a temporary folder'
                ];
                break;

            case UPLOAD_ERR_CANT_WRITE:
                $this->error = true;
                $this->errorMessage = [
                    'type'        => 'UPLOAD_ERR_CANT_WRITE',
                    'description' => 'Failed to write file to disk'
                ];
                break;

            case UPLOAD_ERR_EXTENSION:
                $this->error = true;
                $this->errorMessage = [
                    'type'        => 'UPLOAD_ERR_EXTENSION',
                    'description' => 'A PHP extension stopped the file upload'
                ];
                break;

            case UPLOAD_ERR_INI_SIZE:
                $this->error = true;
                $this->errorMessage = [
                    'type'        => 'UPLOAD_ERR_INI_SIZE',
                    'description' => 'The uploaded file exceeds the upload_max_filesize directive'
                ];
                break;

            case UPLOAD_ERR_PARTIAL:
                $this->error = true;
                $this->errorMessage = [
                    'type'        => 'UPLOAD_ERR_PARTIAL',
                    'description' => 'The uploaded file was only partially uploaded'
                ];
                break;

            case UPLOAD_ERR_FORM_SIZE:
                $this->error = true;
                $this->errorMessage = [
                    'type'        => 'UPLOAD_ERR_FORM_SIZE',
                    'description' => 'Exceeded filesize limit.'
                ];
                break;

            default:
                $this->error = true;
                $this->errorMessage = [
                    'type'        => 'default',
                    'description' => 'Unknown errors.'
                ];
                break;
        }

        return $this->error;
    }
}
