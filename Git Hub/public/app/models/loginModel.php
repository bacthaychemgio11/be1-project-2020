<?php
// LOGIN MODELS
// HỒ SĨ HÙNG - LÊ TUẤN LIÊM
class loginModel extends dB
{
    //CHỨC NĂNG ĐĂNG KÝ THÀNH VIÊN
    public function registerMember($username, $password, $email)
    {
        $password = password_hash($password, PASSWORD_DEFAULT);

        $sql = parent::$connection->prepare("INSERT INTO accounts (userName, pass, account_email, Type_account) VALUES (?, ?, ?, 1)");
        $sql->bind_param("sss", $username, $password, $email);

        return  $sql->execute();
    }

    //CHỨC NĂNG ĐĂNG KÝ Admin
    public function registerAdmin($username, $password, $email)
    {
        $password = password_hash($password, PASSWORD_DEFAULT);

        $sql = parent::$connection->prepare("INSERT INTO accounts (userName, pass, account_email, Type_account) VALUES (?, ?, ?, 0)");
        $sql->bind_param("sss", $username, $password, $email);

        return  $sql->execute();
    }

    //KIỂM TRA TÀI KHOẢN VÀ CHUYỂN TRANG KHI DÙNG CHỨC NĂNG LOGIN
    public function logIn($username, $password)
    {
        $sql = parent::$connection->prepare("SELECT * FROM accounts");

        $accountList = parent::select($sql);

        for ($i = 0; $i < count($accountList); $i++) {
            if (
                $accountList[$i]['userName'] === $username
                && password_verify($password, $accountList[$i]['pass'])
            ) {
                return $accountList[$i]['Type_account'];
            }
        }

        return -1;
    }

    // Hồ SĨ HÙNG
    // 04/12/2020
    // CHỨC NĂNG KIỂM TRA USERNAME CÓ TỒN TẠI TRONG HỆ THỐNG?
    // CHỈ DÙNG CHO REGISTER
    public function checkUsernameForRegistration($username)
    {
        $sql = parent::$connection->prepare("SELECT * FROM accounts");

        $accountList = parent::select($sql);

        for ($i = 0; $i < count($accountList); $i++) {
            if ($accountList[$i]['userName'] === $username) {
                return false;
            }
        }

        return true;
    }

    // HỒ SĨ HÙNG
    // 26/12/2020
    // CHỨC NĂNG LẤY ID DỰA VÀO USERNAME
    // DÙNG ĐỂ PHỤC VỤ SHOPPING CART
    public function getIDByUserName($username)
    {
        $sql = parent::$connection->prepare(("SELECT accounts.ID_account FROM accounts WHERE accounts.userName = ? "));
        $sql->bind_param("s", $username);

        return parent::select($sql)[0];
    }
}
