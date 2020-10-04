<?php

namespace Core\Model;

class Database
{
    /**
     * Database Host
     * @var string
     */
    protected string $Host = '';

    /**
     * Database port
     * @var string
     */
    protected string $Port = '';

    /**
     * Database name
     * @var string
     */
    protected string $Database = '';

    /**
     * Database user
     * @var string
     */
    protected string $User = '';

    /**
     * Database password
     * @var string
     */
    protected string $Password = '';

    /**
     * Databse Driver instance
     * @var object
     */
    protected object $Instance;

    /**
     * Query string
     * @var string
     */
    protected string $sql = '';

    /**
     * Connection checker
     * @var boolean
     */
    protected bool $connected = false;

    /**
     * Query result count container
     * @var int
     */
    protected int $rows_count = 0;

    /**
     * Initiate database
     */
    public function __construct()
    {
        $this->Host     = $_ENV["DB_HOST"];
        $this->User     = $_ENV["DB_USERNAME"];
        $this->Password = $_ENV["DB_PASSWORD"];
        $this->Database = $_ENV["DB_DATABASE"];
        $this->Port     = $_ENV["DB_PORT"];

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

        return null;
    }

    /**
     * Return the last insert ID
     *
     * @return int
     */
    public function insertId()
    {
        return $this->Instance->insert_id;
    }

    /**
     * Scape given
     *
     * @param  string $value
     * @return string
     */
    public function scape($value)
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

    /**
     * Get sql error
     *
     * @return void
     */
    private function getError()
    {
        return $this->sql . "SQL Exception #" . $this->Instance->errno . " : " . $this->Instance->error;
    }
}
