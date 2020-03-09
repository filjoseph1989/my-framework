<?php

namespace Core\Model;

class Database
{
    /**
     * Variables
     *
     * @var string   $Host
     * @var string   $Database
     * @var string   $User
     * @var string   $Password
     * @var mysqli   $Instance
     * @var boolean  $connected
     */
    protected string $Host     = '';
    protected string $Port     = '';
    protected string $Database = '';
    protected string $User     = '';
    protected string $Password = '';
    protected object $Instance;
    protected string $sql      = '';
    protected bool $connected  = false;
    protected int $rows_count  = 0;

    /**
     * Initiate database
     */
    public function __construct()
    {
        $this->Host     = getenv("DB_HOST");
        $this->User     = getenv("DB_USERNAME");
        $this->Password = getenv("DB_PASSWORD");
        $this->Database = getenv("DB_DATABASE");
        $this->Port     = getenv("DB_PORT");

        self::connect();
    }

    /**
     * Run direct query
     *
     * @param  string $query
     * @return mixed
     */
    public function query(string $query)
    {
        $results = $this->Instance->query($query);

        if ($results !== false) {
            return $results;
        }

        /*
        if (!$results) {
            die($this->getError());
        }
         */

        # Issue 56
        debug_print_append("\nQuery is not successful on @ core\Model\Database.php:49\n");
        debug_print_append(trace(true));
        return null;
    }

    /**
     * Scape given
     *
     * @param  string $value
     * @return string
     */
    public function scape(string $value = '')
    {
        return $this->Instance->real_escape_string($value);
    }

    /**
     * Return the count of rows from previous query
     *
     * @return int
     */
    public function count()
    {
        return $this->Instance->affected_rows;
    }

    /**
     * Database::connect()
     * Opens up the connection to the database based on the object's attributes¸
     *
     * @return void
     */
    public function connect()
    {
        $con = mysqli_connect(
            $this->Host,
            $this->User,
            $this->Password,
            $this->Database,
            $this->Port
        );

        if (mysqli_connect_errno()) {
            die($this->getError()); # Issue 70
        } else {
            $this->Instance = $con;
            $this->connected = true;
        }
    }

    /**
     * Check if database connected
     * @return boolean
     */
    public function isConnected()
    {
        return $this->connected;
    }

    /**
     * Database::unconnect()
     * Closes the database's connection to avoid conflict and release memory
     *
     * @return void
     */
    public function unconnect()
    {
        if ($this->Instance->close() === false) {
            die($this->getError());
        }
    }

    /**
     * Return primary key
     *
     * @param  string $table
     * @return int
     */
    public function getPrimaryKey(string $table)
    {
        $this->sql = "SHOW COLUMNS FROM {$table};";
        $results   = $this->Instance->query($this->sql);

        if ($results === false) {
            return false;
        }

        foreach ($results as $row) {
            if ($row["Key"] == "PRI") {
                return $row["Field"];
            }
        }
    }

    /**
     * Return the array of foriegn key(s)
     *
     * @param  string $table
     * @return array
     */
    public function getForeignKey(string $table)
    {
        $fk_array  = [];
        $return    = [];
        $this->sql = "SHOW CREATE TABLE " . $table;
        $results   = $this->Instance->query($this->sql);

        if ($results === false) {
            return [];
        }

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
}
