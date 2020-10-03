<?php

namespace App\Models;

use Core\Model\Model;

/**
 * @author Fil Beluan
 */
class User extends Model
{
    /**
     * Table name
     */
    protected string $table = 'users';

    /**
     * Initiate model
     */
    public function __construct()
    {
        parent::__construct();
    }
}
