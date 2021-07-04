<?php
    class billDetailModel extends dB{
        //get all data
        public function getAll()
        {
            $sql = parent::$connection->prepare("SELECT * FROM bill_detail");
            return parent::select($sql);
        }

        //get data by ID
        public function getDataByIDBill($idBill)
        {
            $sql = parent::$connection->prepare("SELECT * FROM bill_detail WHERE id_bill = ?");
            $sql->bind_param("i",$idBill);

            return parent::select($sql);
        }

        //insert data
        public function insertData($idBill, $idProduct, $quantity)
        {
            $sql = parent::$connection->prepare("INSERT INTO bill_detail VALUES (?,?,?)");
            $sql->bind_param("iii", $idBill, $idProduct, $quantity);
            return $sql->execute();
        }
    }
