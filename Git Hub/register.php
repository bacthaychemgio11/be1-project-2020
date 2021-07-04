<?php
require_once("./public/config/database.php");

spl_autoload_register(function ($class_name) {
    require "./public/app/models/" . $class_name . '.php';
});

$loginModel = new loginModel();

if(!empty($_POST['username']) && !empty($_POST['passwd']) && !empty($_POST['email'])) {

    // //KIỂM TRA USERNAME CÓ TỒN TẠI KHÔNG
    if($loginModel->checkUsernameForRegistration($_POST['username']) === true)
    {
        $loginModel->registerMember($_POST['username'], $_POST['passwd'], $_POST['email']);
        ?>
        <script>alert("Register successfully!!")</script>
        <?php
    }
    else {
        ?>
        <script>alert("Please choose different username!")</script>
        <?php
    }
}
