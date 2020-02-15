<?php
/**
 * This script is the DB Class
 *
 * PHP version 7.2
 *
 * @category DB_Class
 * @package  DB_Class
 * @author   Benson Imoh,ST <benson@stbensonimoh.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://stbensonimoh.com
 */
class DB
{
    private $_dsn;
    private $_host;
    private $_db;
    private $_username;
    private $_password;
    public $email;
    public $tablename;
    private $_conn;

    /**
     * Constructor function
     *
     * @param string $host     The host of the Database
     * @param string $db       The Database name
     * @param string $username The Database Username
     * @param string $password The Database Password
     */
    public function __construct($host, $db, $username, $password)
    {
        $this->_dsn = "mysql:host=$host;dbname=$db";
        $this->_host = $host;
        $this->_db = $db;
        $this->_username = $username;
        $this->_password = $password;

        //Create PDO Connection with the dbconfig data
        $this->_conn = new PDO($this->_dsn, $username, $password);

        return $this->_conn;
    }

    public function getConnection()
    {
        return $this->_conn;
    }


    /**
     * Check if User Exists in the Database
     *
     * @param string $email     The Email Address to be checked
     * @param string $tablename The name of the tale to be checked
     *
     * @return void
     */
    public function userExists($email, $tablename)
    {
        $usercheck = "SELECT * FROM $tablename WHERE email=?";
        // prepare the Query
        $usercheckquery = $this->_conn->prepare($usercheck);
        //Execute the Query
        $usercheckquery->execute(array("$email"));
        //Fetch the Result
        $usercheckquery->rowCount();
        if ($usercheckquery->rowCount() > 0) {
            return true;
        }
        return false;
    }

    public function query(string $query)
    {
        // prepare the Query
        $prepare = $this->_conn->query($query);

        return $prepare;
    }

    /**
     * Select User in the Database
     *
     * @param string $email     The Email Address to be checked
     * @param string $tablename The name of the tale to be checked
     *
     * @return void
     */
    public function userSelect($email, $tablename)
    {
        $usercheck = "SELECT * FROM $tablename WHERE email=?";
        // prepare the Query
        $usercheckquery = $this->_conn->prepare($usercheck);
        //Execute the Query
        $usercheckquery->execute(array("$email"));
        //Fetch the Result
        $usercheckquery->rowCount();
        if ($usercheckquery->rowCount() > 0) {

            // return fields
            return $usercheckquery->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }

    /**
     * Check if User Exists in the Database and has paid
     *
     * @param string $email     The Email Address to be checked
     * @param string $tablename The name of the tale to be checked
     *
     * @return void
     */
    public function userExistsAndPaid($email, $tablename)
    {
        $usercheck = "SELECT * FROM $tablename WHERE email=? AND paid='yes'";
        // prepare the Query
        $usercheckquery = $this->_conn->prepare($usercheck);
        // Execute the Query
        $usercheckquery->execute(array("$email"));
        // Fetch the Result
        $usercheckquery->rowCount();
        if ($usercheckquery->rowCount() > 0) {
            return true;
        }
        return false;
    }

    /**
     * Inserts a New User in to the Database
     *
     * @param string $tablename The Name of the table to insert user
     * @param array  $details   The Details to be entered into
     *
     * @return void
     */
    public function insertUser($tablename, $details)
    {
        $columns = array_keys($details);
        $values = array_values($details);
        // Insert the user into the database
        $enteruser = "INSERT into $tablename (" . implode(',', $columns) .")
                    VALUES ('" . implode("', '", $values) . "')";
                    // echo json_encode($enteruser);
        //  Prepare Query
        $enteruserquery = $this->_conn->prepare($enteruser);
        //  Execute the Query
        $enteruserquery->execute();
        //  Fetch Result
        $enteruserquery->rowCount();

        if ($enteruserquery->rowCount() > 0) {
            return true;
        }
        return false;
    }

    /**
     * Update the Database when user has paid
     *
     * @param string $tablename  The name of the table to Update
     * @param array  $details    The details to update in the database
     * @param string $wherefield The unique field in the database
     * @param string $wherevalue The value of the unique field
     *
     * @return void
     */
    public function updatePaid($tablename, $details, $wherefield, $wherevalue)
    {
        $columns = array_keys($details);
        $values = array_values($details);
        array_push($values, $wherevalue);
        $sql = "UPDATE $tablename SET " . implode('=?, ', $columns) . "=? WHERE $wherefield =?";
        // echo json_encode($sql);

        // Create the prepared statement
        $stmt = $this->_conn->prepare($sql);

        // Execute the Query
        $stmt->execute($values);

        //  Fetch Result
        $stmt->rowCount();

        // Check if execution was successfull
        if ($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }
}
