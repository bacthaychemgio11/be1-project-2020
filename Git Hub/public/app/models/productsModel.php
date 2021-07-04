<?php
class productsModel extends dB
{
    //Get All Products
    public function getProducts()
    {
        $sql = parent::$connection->prepare("SELECT * FROM products");
        $items = parent::select($sql);

        return $items;
    }

    //Get Product by ID
    public function getProductByID($id)
    {
        $sql = parent::$connection->prepare("SELECT * FROM products WHERE product_id = ?");
        $sql->bind_param("i", $id);

        $items = parent::select($sql);

        return $items[0];
    }

    //Get Popular Products
    public function get10PopularProducts()
    {
        $sql = parent::$connection->prepare("SELECT * FROM products WHERE product_ispopular = 1");
        $items = parent::select($sql);

        //Lấy 10 phần tử ngẫu nhiên trong mảng $items
        $positionArray = [];

        for ($i = 0; $i <= 9; $i++) {
            $tempPos = rand(0, count($items) - 1);
            $temProductID = $items[$tempPos]['product_id'];

            //Xử lý 10 phần tử không trùng nhau
            for ($j = 0; $j < count($positionArray); $j++) {
                while ($positionArray[$j] ==  $temProductID) {
                    $tempPos = rand(0, count($items) - 1);
                    $temProductID = $items[$tempPos]['product_id'];
                }
            }

            $positionArray[] =   $temProductID;
        }

        $caresoulItems = [];

        //Đưa các phần tử có vị trí trong $positionArray vào mảng
        foreach ($positionArray as $position) {
            foreach ($items  as $item) {
                if ($item['product_id'] === $position) {
                    $caresoulItems[] = $item;
                    break;
                }
            }
        }

        return   $caresoulItems;
    }

    //Get products by Category
    public function getProductsByCategory($idCategory)
    {
        $sql = parent::$connection->prepare("SELECT * FROM products INNER JOIN products_categories ON products.product_id = products_categories.product_id WHERE category_id = ?");
        $sql->bind_param('i', $idCategory);
        $items = parent::select($sql);

        return $items;
    }

    //29/11/2020
    //PAGINATION

    public function getProductsByPage($page, $itemsPerPage, $sortType)
    {
        $startPosition = ($page - 1) * $itemsPerPage;
        $sortString = '';

        //Xủ lý loại sort
        if ($sortType === "customerReview") {
            $sortString = 'ORDER BY product_quality DESC';
        } else if ($sortType === "price_asc") {
            $sortString = 'ORDER BY product_price ASC';
        } else if ($sortType === "price_des") {
            $sortString = 'ORDER BY product_price DESC';
        } else if ($sortType === "name_asc") {
            $sortString = 'ORDER BY product_name ASC';
        } else if ($sortType === "name_des") {
            $sortString = 'ORDER BY product_name DESC';
        }

        $sql = parent::$connection->prepare("SELECT * FROM products " . $sortString . " LIMIT ?,?");
        $sql->bind_param("ii", $startPosition, $itemsPerPage);

        $items = parent::select($sql);

        return $items;
    }

    public function getProductsByPageForCategory($page, $itemsPerPage, $idCategory, $sortType)
    {
        $startPosition = ($page - 1) * $itemsPerPage;
        $sortString = '';

        //Xủ lý loại sort
        if ($sortType === "customerReview") {
            $sortString = 'ORDER BY product_quality DESC';
        } else if ($sortType === "price_asc") {
            $sortString = 'ORDER BY product_price ASC';
        } else if ($sortType === "price_des") {
            $sortString = 'ORDER BY product_price DESC';
        } else if ($sortType === "name_asc") {
            $sortString = 'ORDER BY product_name ASC';
        } else if ($sortType === "name_des") {
            $sortString = 'ORDER BY product_name DESC';
        }


        $sql = parent::$connection->prepare("SELECT * FROM products INNER JOIN products_categories ON products.product_id = products_categories.product_id WHERE category_id = ? " . $sortString . " LIMIT ?,?");
        $sql->bind_param("iii", $idCategory, $startPosition, $itemsPerPage);

        $items = parent::select($sql);

        return $items;
    }

    //COUNT ALL PRODUCTS
    public function countAllProducts()
    {
        $sql = parent::$connection->prepare("SELECT COUNT(product_id) FROM products");
        $items = parent::select($sql);

        return $items[0]['COUNT(product_id)'];
    }

    //COUNT ALL PRODUCTS HAVE THE SAME CATEGORY
    public function countAllProductsHaveSameCateGory($idCategory)
    {
        $sql = parent::$connection->prepare("SELECT COUNT(products.product_id) FROM products INNER JOIN products_categories ON products.product_id = products_categories.product_id WHERE category_id = ?");
        $sql->bind_param("i", $idCategory);

        $items = parent::select($sql);

        return $items[0]['COUNT(products.product_id)'];
    }

    //GET PRODUCTS BY KEYWORD
    public function getProductsByKeyWord($keyword, $page, $itemsPerPage, $sortType)
    {
        $startPosition = ($page - 1) * $itemsPerPage;

        $sortString = '';

        //Xủ lý loại sort
        if ($sortType === "customerReview") {
            $sortString = 'ORDER BY product_quality DESC';
        } else if ($sortType === "price_asc") {
            $sortString = 'ORDER BY product_price ASC';
        } else if ($sortType === "price_des") {
            $sortString = 'ORDER BY product_price DESC';
        } else if ($sortType === "name_asc") {
            $sortString = 'ORDER BY product_name ASC';
        } else if ($sortType === "name_des") {
            $sortString = 'ORDER BY product_name DESC';
        }

        $sql = parent::$connection->prepare("SELECT * FROM products WHERE product_name LIKE ? " . $sortString . " LIMIT ?,? ");
        $q = "%" . $keyword . "%";
        $sql->bind_param("sii", $q,  $startPosition, $itemsPerPage);

        $items = parent::select($sql);

        return $items;
    }

    //COUNT ALL PRODUCTS HAVE KEYWORD IN NAME
    public function countAllProductsHaveKeyWordInName($keyword)
    {
        $sql = parent::$connection->prepare("SELECT COUNT(product_id) FROM products WHERE product_name LIKE ? ");
        $q = "%" . $keyword . "%";
        $sql->bind_param("s", $q);

        $items = parent::select($sql);

        return $items[0]['COUNT(product_id)'];
    }


    //INSERT NEW PRODUCT
    //Ho Si Hung
    //23/12/2020
    public function createProducts(
        $product_name,
        $product_description,
        $product_price,
        $product_quality,
        $product_state,
        $product_ispopular,
        $product_photo,
        $product_category
    ) {
        $arrPhoto = explode("#", $product_photo);
        
        $sql = parent::$connection->prepare("INSERT INTO products VALUES(Null, ?,?,?,?,?,?,?,?)");
        $sql->bind_param(
            "ssisiiis",
            $product_name,
            $product_description,
            $product_price,
            $arrPhoto[0],
            $product_state,
            $product_ispopular,
            $product_quality,
            $product_photo,
        );

        $sql->execute();

        //Add category for this product
        //get lasted auto increment id in server
        $id = mysqli_insert_id(parent::$connection);

        $sql = parent::$connection->prepare("INSERT INTO products_categories VALUES(?, ?)");
        $sql->bind_param(
            "ii",
            $id,
            $product_category
        );

        return $sql->execute();
    }

    //UPDATE PRODUCTS 24/12/2020
    public function updateProducts(
        $product_id,
        $product_name,
        $product_description,
        $product_price,
        $product_quality,
        $product_state,
        $product_ispopular,
        $product_photo,
        $product_category
    ) {
        $arrPhoto = explode("#", $product_photo);

        $sql = parent::$connection->prepare("UPDATE products SET 
        products.product_name = ?,
        products.product_description = ?, 
        products.product_price = ?,
        products.product_photo = ?,
        products.product_state = ?,
        products.product_ispopular = ? ,
        products.product_quality = ? ,
        products.product_more_photo = ? 
        WHERE products.product_id = ?;");
        $sql->bind_param(
            "ssisiiisi",
            $product_name,
            $product_description,
            $product_price,
            $arrPhoto[0],
            $product_state,
            $product_ispopular,
            $product_quality,
            $product_photo,
            $product_id
        );

        $sql->execute();

        //Update category for this product
        $sql = parent::$connection->prepare("UPDATE products_categories SET
        products_categories.category_id = ?
        WHERE products_categories.product_id = ?");
        $sql->bind_param(
            "ii",
            $product_category,
            $product_id
        );

        return $sql->execute();
    }

    //DELETE PRODUCTS
    //HO SI HUNG
    //24/12/2020
    public function deleteProducts($product_id)
    {
        $sql = parent::$connection->prepare("DELETE FROM products 
        WHERE products.product_id = ?;");

        $sql->bind_param("i", $product_id);

        $sql->execute();

        //Update category for this product
        $sql = parent::$connection->prepare("DELETE FROM products_categories
        WHERE products_categories.product_id = ?");

        $sql->bind_param("i",$product_id);

        return $sql->execute();
    }
}
