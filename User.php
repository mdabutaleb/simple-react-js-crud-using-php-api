<?php

use PDO;

class User
{

    private $title = '';
    private $email;
    private $job;
    private $id;
    private $conn = '';
    private $dbuser = 'root';
    private $dbpass = '111111';

    public function __construct()
    {
        $this->conn = new PDO("mysql:host=localhost;dbname=reactjs_php", $this->dbuser, $this->dbpass);
    }

    public function num_of_row($table)
    {
        try {
            $query = "SELECT * FROM $table";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
        return $stmt->rowCount();
    }

    public function setData($data = '')
    {
        if (array_key_exists('name', $data) && !empty($data['name'])) {
            $this->name = $data['name'];
        }

        if (array_key_exists('email', $data) && !empty($data['email'])) {
            $this->email = $data['email'];
        }
        if (array_key_exists('job', $data) && !empty($data['job'])) {
            $this->job = $data['job'];
        }

        if (array_key_exists('id', $data) && !empty($data['id'])) {
            $this->id = $data['id'];
        }

        return $this;

    }

    public function store()
    {
        try {
            $query = "INSERT INTO users(name, email, job) VALUES(:name, :email, :job)";
            $stmt = $this->conn->prepare($query);
            $status = $stmt->execute(array(
                ':name' => $this->name,
                ':email' => $this->email,
                ':job' => $this->job,
            ));
            if ($status) {
                return 'Stored successfully !';
            } else {
                return 'Something going wrong';
            }


        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function index()
    {
        try {
            $query = "SELECT * FROM users";
            $stmt = $this->conn->query($query);
            $data = $stmt->fetchAll();
            return $data;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function show($id)
    {

        try {
            $query = "SELECT * FROM users where id=" . $id;
            $stmt = $this->conn->query($query);
            $result = $stmt->fetch();
            return $result;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }


    }


    public function update()
    {

        try {
            $query = "UPDATE users SET name=:name, email=:email, job=:job where id=:id";
            $stmt = $this->conn->prepare($query);
            $status = $stmt->execute(array(
                ':id' => $this->id,
                ':name' => $this->name,
                ':email' => $this->email,
                ':job' => $this->job,
            ));
            if ($status) {
                return "Successfully Updated";
            }

        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }

    }

    public function delete($id)
    {

        try {
            $query = "delete from users where id=" . $id;
            $stmt = $this->conn->query($query);
            $status = $stmt->execute();
            if ($stmt) {
                return 'Deleted successfully !';
            }
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function trash()
    {
        try {
            $query = "UPDATE mobile_models SET is_delete=:true where id=:id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(array(
                    ':true' => '1',
                    ':id' => $this->id,
                )
            );
            if ($stmt) {
                $_SESSION['Message'] = "<h3>Successfully Deleted</h3>";
                header("location:index.php");
            }

        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function trashlist()
    {
        try {
            if ($this->order == 'a-z') {
                $query = "SELECT * FROM mobile_models where is_delete=1 ORDER BY title";
            } else {
                $query = "SELECT * FROM mobile_models where is_delete=1 ORDER BY id DESC";
            }
            $stmt = $this->conn->query($query);
            $data = $stmt->fetchAll();
            return $data;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function restore()
    {
        try {
            $query = "UPDATE mobile_models SET is_delete=:true where id=:id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(array(
                    ':true' => '0',
                    ':id' => $this->id,
                )
            );
            if ($stmt) {
                $_SESSION['Message'] = "<h3>Data Successfully Restored</h3>";
                header("location:trashList.php");
            }
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

}


