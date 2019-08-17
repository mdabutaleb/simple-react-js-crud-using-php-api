<?php
include_once "User.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $user = new User();
    echo $user->setData($_POST)->store();
}

if ($_GET['status']=='all'){
   $user = new User();
   echo json_encode($user->index());
}
if ($_GET['status']=='delete'){
   $user = new User();
   echo json_encode($user->delete($_GET['id']));
}

if ($_GET['status']=='edit'){
   $user = new User();
   echo json_encode($user->show($_GET['id']));
}

