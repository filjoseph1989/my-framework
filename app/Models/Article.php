<?php

namespace App\Models;

use Core\Model\Model;

/**
 * @author Fil Beluan
 */
class Article extends Model
{
    /**
     * Table name
     */
    protected string $table = 'articles';

    /**
     * Define relations
     */
    protected array $relations = [
        'user_id' => 'App\\Models\\User'
    ];

    /**
     * Initiate model
     */
    public function __construct()
    {
        parent::__construct();
    }
}
