<?php
namespace App\Pondit\Mobile;

use PDO;

class Mobile
{
    public $id = '';
    public $title = '';
    public $conn = '';
    public $dbuser = 'root';
    public $dbpass = '';
    public $order = '';
    public $page_number = '';
    public $item_per_page;

    public function __construct()
    {
        session_start();
        $this->conn = new PDO("mysql:host=localhost;dbname=pondit", $this->dbuser, $this->dbpass);
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
        if (array_key_exists('mobile_model', $data) && !empty($data['mobile_model'])) {
            $this->title = $data['mobile_model'];
        }
        if (array_key_exists('order', $data) && !empty($data['order'])) {
            $this->order = $data['order'];
        }
        if (array_key_exists('item_p_pg', $data) && !empty($data['item_p_pg'])) {
            $this->item_per_page = $data['item_p_pg'];
        }
        if (array_key_exists('page', $data) && !empty($data['page'])) {
            $this->page_number = $data['page'];
        }
        if (array_key_exists('id', $data) && !empty($data['id'])) {
            $this->id = $data['id'];
        }

        return $this;

    }

    public function store()
    {
        try {
            $qr = "SELECT * FROM `mobile_models` WHERE title=" . "'" . $this->title . "'";
            $stmt1 = $this->conn->query($qr);
            $result = $stmt1->fetch();
            if (empty($result)) {
                $query = "INSERT INTO mobile_models(id, title) VALUES(:id, :title)";
                $stmt = $this->conn->prepare($query);
                $stmt->execute(array(
                    ':id' => null,
                    ':title' => $this->title,
                ));
                if ($stmt) {
                    $_SESSION['Message'] = "<h3>Successfully Submited</h3>";

                } else {
                    $_SESSION['Message'] = "<h3>Opps Something going wrong</h3>";
                }
            } else {

                $_SESSION['Message'] = "<h3>Sorry ! Same mobile model already exist</h3>";
                header('location:create.php');
            }

            if (empty($_POST['moreadd'])) {
                header('location:index.php');
            }else{
                header('location:create.php');
            }

        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function index()
    {
        try {
            if ($this->order == 'a-z') {
                $query = "SELECT * FROM mobile_models where is_delete=0 ORDER BY title LIMIT $this->item_per_page OFFSET " . $this->page_number * $this->item_per_page;
            } else {
                $query = "SELECT * FROM mobile_models where is_delete=0 ORDER BY id DESC LIMIT $this->item_per_page OFFSET " . $this->page_number * $this->item_per_page;
            }
//            echo $query;
//            die();
            $stmt = $this->conn->query($query);
            $data = $stmt->fetchAll();
            return $data;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }

    }

    public function show()
    {

        try {
            $query = "SELECT * FROM mobile_models where id=" . $this->id;
            $stmt = $this->conn->query($query);
            $result = $stmt->fetch();
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
        return $result;

    }

    public function update()
    {

        try {
            $qr = "SELECT * FROM `mobile_models` WHERE title=" . "'" . $this->title . "'";
            $stmt1 = $this->conn->query($qr);
            $result = $stmt1->fetch();
            if (empty($result)) {
                $query = "UPDATE mobile_models SET title=:title where id=:id";
                $stmt = $this->conn->prepare($query);
                $stmt->execute(array(
                    ':id' => $this->id,
                    ':title' => $this->title,
                ));
                if ($stmt) {
                    $_SESSION['Message'] = "<h3>Successfully Updated</h3>";
                    header('location:index.php');
                }
            } else {
                $_SESSION['Message'] = "<h3>Mobile model already exist</h3>";
                header("location:edit.php?id=$this->id");
            }

        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }

    }

    public function delete()
    {

        try {
            $query = "delete from mobile_models where id=" . $this->id;
            $stmt = $this->conn->query($query);
            $stmt->execute();
            if ($stmt) {
                $_SESSION['Message'] = "<h3>Successfully Deleted</h3>";
                header("location:trashList.php");
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