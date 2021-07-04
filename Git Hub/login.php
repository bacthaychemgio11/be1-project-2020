<?php
// LOGIN.PHP
// HỒ SĨ HÙNG - LÊ TUẤN LIÊM
session_start();

require_once("./public/config/database.php");

spl_autoload_register(function ($class_name) {
    require "./public/app/models/" . $class_name . '.php';
});

$loginModel = new loginModel();

if (!empty($_POST['user']) && !empty($_POST['pass'])) {
    if ($loginModel->logIn($_POST['user'], $_POST['pass']) == 1) {
        $_SESSION["account"] = $_POST['user'];

        //Lấy id để phục vụ shopping cart
        $_SESSION["ID_account"] = $loginModel->getIDByUserName($_POST['user'])["ID_account"];
        header("Location: index.php");

    } else if ($loginModel->logIn($_POST['user'], $_POST['pass']) == 0) {
        $_SESSION["admin"] = $_POST['user'];

        //Lấy id để phục vụ shopping cart
        $_SESSION["ID_account"] = $loginModel->getIDByUserName($_POST['user'])["ID_account"];
        header("Location: admin.php");
    }
?>
    <?php
}
    ?>
