<?php
class categoriesModel extends dB
{
    //Get All Categories
    public function getCategories()
    {
        $sql = parent::$connection->prepare("SELECT * FROM categories ORDER BY category_id ASC");
        $items = parent::select($sql);

        return $items;
    }

    //Get CategoryID by IDProduct
    public function getIDCategory_By_IDProduct($idProduct)
    {
        $sql = parent::$connection->prepare("SELECT * FROM products INNER JOIN products_categories ON products.product_id = products_categories.product_id WHERE products.product_id  = ?");
        $sql->bind_param('i', $idProduct);

        $items = parent::select($sql);

        return $items[0]['category_id'];
    }

    //Get Category_name by IDProduct
    public function getCategoryName_By_IDCategory($idCategory)
    {
        $sql = parent::$connection->prepare("SELECT * FROM  categories WHERE category_id  = ?");
        $sql->bind_param('i', $idCategory);

        $items = parent::select($sql);

        return $items[0]['category_name'];
    }

    //Lấy số lượng sản phẩm mỗi loại
    public function getTotalProductsInCategory($categories_id)
    {
        $sql = parent::$connection->prepare("SELECT COUNT(*) AS Quantity FROM products_categories WHERE products_categories.category_id = ?");
        $sql->bind_param("i", $categories_id);

        return parent::select($sql)[0];
    }

    //Insert category
    public function createCategories($categories_Name)
    {
        $sql = parent::$connection->prepare("INSERT INTO categories (category_name) VALUES (?)");
        $sql->bind_param('s', $categories_Name);
        return $sql->execute();
    }

    //Update category
    public function updateCategoriesByID($categories_id, $categories_Name)
    {
        $sql = parent::$connection->prepare("UPDATE categories SET category_name = ? WHERE categories.category_id = ?");
        $sql->bind_param('si', $categories_Name, $categories_id);
        return $sql->execute();
    }
}
