<?php

namespace Core\Model;

class Database
{
    /**
     * Variables
     *
     * @var [string] $Host
     * @var [string] $Database
     * @var [string] $User
     * @var [string] $Password
     * @var [mysqli] $Instance
     */
    protected string $Host = '';
    protected string $Port = '';
    protected string $Database = '';
    protected string $User = '';
    protected string $Password = '';
    protected object $Instance;
    protected string $sql = '';

    public function __construct()
    {
        // Task 1: Used enironment variable here
        // Task 2: Study the implementation of .env

        $this->Host     = getenv("DB_HOST");
        $this->User     = getenv("DB_USERNAME");
        $this->Password = getenv("DB_PASSWORD");
        $this->Database = getenv("DB_DATABASE");
        $this->Port     = getenv("DB_PORT");
    }

    /**
     * Database::connect()
     * Opens up the connection to the database based on the object's attributes¸
     *
     * @return void
     */
    public function connect()
    {
        $con = mysqli_connect($this->Host, $this->User, $this->Password, $this->Database, $this->Port);

        if (mysqli_connect_errno()) {
            die($this->getError());
        } else {
            $this->Instance = $con;
        }

        return $this->Instance;
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
        self::connect();
        $this->sql = "SHOW COLUMNS FROM " . $table . ";";
        $results   = $this->Instance->query($this->sql);
        foreach ($results as $row) {
            if ($row["Key"] == "PRI") {
                self::unconnect();
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
        self::connect();

        $fk_array  = [];
        $return    = [];
        $this->sql = "SHOW CREATE TABLE " . $table;
        $results   = $this->Instance->query($this->sql);

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

        self::unconnect();
        return $return;
    }

    public function select(string $table, array $columns)
    {
        $this->sql = "SELECT ";

        foreach ($columns as $key => $column) {
            $this->sql .= "{$column} FROM {$table}";
        }

        return $this;
    }

    public function get()
    {
        $this->sql .= ";";

        self::connect();

        $results = $this->Instance->query($this->sql);

        if (!$results) {
            die($this->getError());
        }

        while ($row = $results->fetch_assoc()) {
            $return[] = $row;
        }

        array_walk_recursive($return, function (&$item, $key) {
            $item = html_entity_decode(utf8_decode($item));
        });

        self::unconnect();

        return $return;
    }


    /**
     * Implements where condition
     *
     * Task 11: Problem with this implementation is: It implement like this statement
     *  "where id=1" but not like "where id=1 and id=2"
     *  calling it again it would look like "where id=1 where id=2" which is wrong
     *
     * @param  array  $wheres
     * @return void
     */
    public function whereArray(array $where)
    {
        $where["value"] = "{$where["value"]}";
        $where["value"] = htmlentities(utf8_encode($where["value"]));
        $this->sql     .= " WHERE {$where["column"]} {$where["condition"]} {$where["value"]}";
        return $this;
    }

    private function getError()
    {
        return "<br/>" . $this->sql . "<br/> SQL Exception #" . $this->Instance->errno . " : " . $this->Instance->error . "<br/>";
    }
}
