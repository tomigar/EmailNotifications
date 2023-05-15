<?php
// db conf
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "notifications";

// db connect for dashboard   
class Database
{
    public $conn;
    public function __construct()
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function __destruct()
    {
        $this->conn->close();
    }

    public function getData($query)
    {
        $result = $this->conn->query($query);
        if ($result->num_rows > 0) {
            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        } else {
            return false;
        }
    }
}

//* Attempt to connect to MySQL database for authentication */
$link = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
