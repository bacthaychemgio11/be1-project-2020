<?php
class billsModel extends dB
{
    //get all bills
    public function getAllBills()
    {
        $sql = parent::$connection->prepare("SELECT * FROM bills ORDER BY id DESC");

        return parent::select($sql);
    }

    //get bill by idBill
    public function getBillByID($idBill)
    {
        $sql = parent::$connection->prepare("SELECT * FROM bills WHERE id = ?");
        $sql->bind_param("i", $idBill);

        return parent::select($sql)[0];
    }

    //get billS by idAccount
    public function getBillByIDAcount($idAccount)
    {
        $sql = parent::$connection->prepare("SELECT * FROM bills WHERE id_account = ? ORDER BY id DESC");
        $sql->bind_param("i", $idAccount);

        return parent::select($sql);
    }

    //INSERT BILL and bill details
    public function insertBillAndDetailsBill($idAccount, $billDetail)
    {
        $sql = parent::$connection->prepare("INSERT INTO bills VALUES(NUll, ? , 0 )");
        $sql->bind_param("i", $idAccount);

        $sql->execute();

        //EXPLODE $billDetail into array to add data in SQL
        $cartData = $billDetail;
        for ($i = 0; $i < count($cartData); $i++) {
            $cartData[$i] = explode(',', $billDetail[$i]);
        }

        //get lastest id in SQL
        $idBill = mysqli_insert_id(parent::$connection);
        $detailModel = new billDetailModel();

        //insert detail data in SQL
        for ($i = 0; $i < count($cartData); $i++) {
            $detailModel->insertData($idBill, $cartData[$i][0], $cartData[$i][1]);
        }

        return;
    }

    //Change state Of BILL
    public function changeState_of_Bill($idBill, $state)
    {
        $sql = parent::$connection->prepare("UPDATE bills SET bill_state = ? WHERE id = ?");
        $sql->bind_param("ii", $state, $idBill);

        return $sql->execute();
    }
}
