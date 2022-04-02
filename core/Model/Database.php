<?php

namespace Core\Model;

use Core\Traits\DebugTrait;

class Database
{
    use DebugTrait;

    protected string $Host = '';
    protected string $Port = '';
    protected string $Database = '';
    protected string $User = '';
    protected string $Password = '';
    protected string $Driver = '';
    protected string $sql = '';
    protected object $Instance;
    protected bool $connected = false;
    protected int $rows_count = 0;

    // Instanciate database
    public function __construct()
    {
        $this->Host     = $_ENV["DB_HOST"]; 
        $this->User     = $_ENV["DB_USERNAME"];
        $this->Password = $_ENV["DB_PASSWORD"];
        $this->Database = $_ENV["DB_DATABASE"];
        $this->Port     = $_ENV["DB_PORT"];
        $this->Driver   = $_ENV["DB_DRIVER"];

        self::connect();
    }

    /**
     * Return database driver
     *
     * @return string
     */
    public function getDatabaseDriver(): string
    {
        return $this->Driver;
    }

    /**
     * Run direct query
     * @param  string $query
     */
    public function query(string $query): mixed
    {
        if ($_ENV["DEBUG_QUERY"] == 'true') {
            file_put_contents('query.log', print_r($query, true)."\n", FILE_APPEND);
        }

        if ($this->connected === true) {
            switch ($this->Driver) {
                case 'psql':
                    $this->sql = $query;
                    return self::makePsqlQuery();
                    break;
                
                default:
                    $this->sql = $query;
                    $results = self::makeMysqlQuery();
                    if ($results !== false) {
                        return $results;
                    }
                    break;
            }
        }


        return null;
    }

    # Return the last insert ID
    public function insertId(): int
    {
        return $this->Instance->insert_id;
    }

    /**
     * Scape given
     * @param  string $value
     */
    #[Database('scape')]
    public function scape($value): string
    {
        return $this->Instance->real_escape_string($value);
    }

    // Return the count of rows from previous query
    #[Database('count')]
    public function count(): int
    {
        return $this->Instance->affected_rows;
    }

    /**
     * Database::connect()
     * Opens up the connection to the database based on the object's attributes¸
     */
    #[Database('connect')]
    public function connect()
    {
        if (self::isDatabaseCredentialEmpty()) {
            file_put_contents(
                'error.log',
                print_r('No credentials define in .env for database connection', true) . "\n",
                FILE_APPEND
            );
            return;
        }

        match ($this->Driver) {
            "mysql" => self::connectToMysql(),
            "psql"  => self::connectToPsql()
        };     
    }

    # Check if database connected
    #[Database('isConnected')]
    public function isConnected(): bool
    {
        return $this->connected;
    }

    /**
     * Database::unconnect()
     * Closes the database's connection to avoid conflict and release memory
     */
    #[Database('unconnect')]
    public function unconnect()
    {
        if ($this->Instance->close() === false) {
            die($this->getError());
        }
    }

    /**
     * Return primary key
     * @param  string $table
     */
    #[Database('getPrimaryKey')]
    public function getPrimaryKey(string $table): string
    {
        if (!$this->connected) return false;

        $this->sql = "SHOW COLUMNS FROM {$table}";

        match ($this->Driver) {
            'mysql' => self::makeMysqlQuery(),
            'psql'  => self::makePsqlQuery()
        };
    }

    /**
     * Return the array of foriegn key(s)
     * @param  string $table
     */
    #[Database('getForeignKey')]
    public function getForeignKey(string $table): array
    {
        if (!$this->connected) return [];

        $fk_array  = [];
        $return    = [];
        $this->sql = "SHOW CREATE TABLE {$table}";
        $results   = $this->Instance->query($this->sql);

        if ($results === false) return [];

        while ($row = $results->fetch_assoc()) {
            $fk_array[] = $row;
        }

        $fk_array = explode("FOREIGN KEY", $fk_array[0]["Create Table"]);

        foreach ($fk_array as $fk) {
            if (strpos($fk, "REFERENCES")) {
                $column_name = substr($fk, 3, strpos($fk, ")") - 4);
                $return[]    = $column_name;
            }
        }

        return $return;
    }

    // Check if there is given credentials on .env
    #[Database('isDatabaseCredentialEmpty')]
    private function isDatabaseCredentialEmpty(): bool
    {
        if (
            $this->Host == '' ||
            $this->User == '' ||
            $this->Password == '' ||
            $this->Database == '' ||
            $this->Port == ''
        ) {
            return true;
        }

        return false;
    }

    /**
     * Create a connection to mysql database
     *
     * @return void
     */
    private function connectToMysql()
    {
        $con = mysqli_connect(
            $this->Host,
            $this->User,
            $this->Password,
            $this->Database,
            $this->Port
        );

        if (mysqli_connect_errno()) {
            file_put_contents('sql.log', print_r(mysqli_connect_error(), true)."\n", FILE_APPEND);
        }

        if (!is_object($con)) return;

        $this->Instance = $con;
        $this->connected = true;
    }

    /**
     * Create a connection postgre sql
     *
     * @return void
     */
    private function connectToPsql()
    {
        $this->Instance = pg_connect("host={$this->Host} port={$this->Port} dbname={$this->Database} user={$this->User} password={$this->Password}");
        $this->connected = true;
    }

    /**
     * Run a psql query
     *
     * @return array|null
     * @todo Todo-1
     */
    private function makePsqlQuery()
    {
        $result = pg_query($this->Instance, "{$this->sql};");
        
        if  (!$result) {
            return null;
        }
        
        $rows = pg_fetch_assoc($result);
        
        if (!$rows) {
            return null;
        }

        return $rows;
    }
    
    /**
     * Run a mysql query
     *
     * @return array|null
     * @todo Todo-1
     */
    private function makeMysqlQuery()
    {
        $results = $this->Instance->query($this->sql);
        
        if ($results === false) return false;

        foreach ($results as $row) {
            if ($row["Key"] == "PRI") {
                return $row["Field"];
            }
        }

        return null;
    }    
}
