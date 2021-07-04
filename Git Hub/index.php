<!-- CONNECT PHP FILES -->
<?php
//SESSION
session_start();

// idAccount = -1 khi người dùng chưa đăng nhập
$idAccount = -1;

if (isset($_SESSION["ID_account"])) {
    $idAccount = $_SESSION["ID_account"];
}
//CHỨC NĂNG SHOPPING CART
//COOKIE
$listItemsInCart = array();

//NẾU COOKIE ĐÃ CÓ DỮ LIỆU SẴN, LẤY DỮ LIỆU CŨ CỦA COOKIE
if (isset($_COOKIE["cart"])) {
    $cookieData = stripslashes($_COOKIE["cart"]);
    $listItemsInCart = json_decode($cookieData, true);

    //NẾU TRONG COOKIE CHƯA CÓ SẴN 1 MẢNG CÓ KEY = ID ACOUNT, TẠO THÊM MẢNG PHỤ CÓ KEY LÀ ID ACCOUNT CHO COOKIE
    $exist = false;
    for ($i = 0; $i < count(array_keys($listItemsInCart)); $i++) {
        if (array_keys($listItemsInCart)["$i"] == $idAccount) {
            $exist = true;
            break;
        }
    }

    if (!$exist) {
        $listItemsInCart[$idAccount] = array();
    }
} else {
    $listItemsInCart[$idAccount] = array();
}

//Đóng gói và lưu trữ cookie
$listItemsInCart = json_encode($listItemsInCart);
setcookie('cart', $listItemsInCart, time() + 600);

//CHỨC NĂNG LOG OUT
if (isset($_POST["logOut"])) {
    session_destroy();
    header("Location: http://localhost:82/Git%20Hub/index.php");
}

//  Load Files
require_once("./public/config/database.php");

spl_autoload_register(function ($class_name) {
    require "./public/app/models/" . $class_name . '.php';
});

/*
    PAGINATON
*/
//Tạo biến $page cho Pagniation
$page = 1;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

//Mặc định item mỗi trang cho Pagination
$itemsPerPage = 16;
if (isset($_GET['itemsPerPage'])) {
    $itemsPerPage = $_GET['itemsPerPage'];
}

//Tạo biến Model
$productsModel = new productsModel();
$categoriesModel = new categoriesModel();

//Sắp xếp sản phẩm
$sortType = "Default";
if (isset($_GET['sortType'])) {
    $sortType = $_GET['sortType'];
}

//Lấy dữ liệu
$productsList = $productsModel->getProductsByPage($page, $itemsPerPage, $sortType);
$categoriesList = $categoriesModel->getCategories();

//CHỨC NĂNG ĐƯA DỮ LIỆU CART LÊN CSDL
if (isset($_POST["cartData"])) {
    $cartData = explode("#", $_POST["cartData"]);

    $bills = new billsModel();
    $bills->insertBillAndDetailsBill($idAccount, $cartData);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project_Hồ Sĩ Hùng</title>
    <link rel="stylesheet" href="public/css/bootstrap.min.css">
    <link rel="stylesheet" href="public/css/styles.css">
    <link rel="stylesheet" href="public/css/all.min.css">
    <script src="public/js/jquery-3.5.1.min.js"></script>
    <script src="public/js/bootstrap.bundle.min.js"></script>

</head>
<style>
    /* PRODUCTS PROPERTIES */
    .product .item .wrappicture {
        height: 280px;
    }
</style>

<body>
    <!-- HEADER -->
    <header>
        <!-- TOP HEADER -->
        <div class="top_header">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <ul>
                            <li>
                                <i class="fas fa-phone-alt"></i> <a href="#">0123-88-99-0999</a>
                            </li>
                            <li class="bd_left">
                                <i class="fas fa-envelope"></i> <a href="#">contact@organici.com </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <form method="get" action="search?q=">
                            <div class="form-row align-items-center my-2">
                                <div class="col-auto">
                                    <label class="sr-only" for="inlineFormInputGroup">Product name</label>
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control" name="q" id="inlineFormInputGroup" placeholder="Type product name here.">
                                    </div>
                                </div>

                                <div class="col-auto">
                                    <button type="submit" class="btn btn-success mb-2"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-4">
                        <ul>
                            <li>
                                <?php
                                if (!isset($_SESSION["account"])) {
                                ?>
                                    <i class="fas fa-share"></i> <a href="themelogin">Log in</a>
                                <?php
                                } else {
                                ?>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <?= $_SESSION["account"] ?>
                                        </button>
                                        <div class="dropdown-menu">
                                            <!-- CHỨC NĂNG Chỉnh sửa thông tin cá nhân -->
                                            <a class="dropdown-item" href="index.php">
                                                <form action="index.php" method="post">
                                                    <button class="btn btn-success btn-block" type="submit">My account</button>
                                                </form>
                                            </a>

                                            <!-- CHỨC NĂNG Xem bill -->
                                            <a class="dropdown-item" href="index.php">
                                                <form action="billsUser.php" method="post">
                                                    <button class="btn btn-success btn-block" type="submit">My bills</button>
                                                </form>
                                            </a>

                                            <!-- CHỨC NĂNG LOG OUT -->
                                            <a class="dropdown-item" href="index.php">
                                                <form action="index.php" method="post">
                                                    <button class="btn btn-success btn-block" type="submit" name="logOut">Log Out</button>
                                                </form>
                                            </a>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>

                            </li>

                            <li class="bd_left">
                                <a href="cart.php"><i class="fas fa-shopping-cart"></i> Your cart</a>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- NAV BAR -->
        <!-- HEADER -->
        <!-- Thanh Navbar -->
        <!-- Chỉnh sửa bởi
        Hồ Sĩ Hùng - 26/11/2020 -->
        <div class="container">
            <nav class="navbar navbar-expand-sm navbar-light bg-white pad_navbar">

                <a class="navbar-brand" href="index.php"><img src="public/images/header_v2_logo.webp" alt="logo"></a>
                <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#collapsibleNavId" aria-controls="collapsibleNavId" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="collapsibleNavId">
                    <ul class="navbar-nav ml-auto mt-2 mt-lg-0">

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="dropdownId" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">CATEGORIES</a>
                            <div class="dropdown-menu" aria-labelledby="dropdownId">
                                <?php
                                foreach ($categoriesList as $item) {
                                ?>
                                    <a class="dropdown-item" href="productCategory?idCategory=<?= $item['category_id'] ?>"><?= $item['category_name'] ?></a>
                                <?php
                                }
                                ?>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">OUR STORY</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">SHOP</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">BLOG</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">CONTACT</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">WISHLIST</a>
                        </li>
                    </ul>

                </div>
            </nav>
        </div>

        <!-- BANNER -->
        <div class="banner">
            <div class="banner_background">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-12 px-6 hidden-xs">
                            <div class="left">
                                <div class="left_picture">
                                    <img src="public/images/interactive_image_v1_1.webp" alt="orange">
                                    <div class="item1_container">
                                        <img src="public/images/interactive_icon_v1_1.webp" class="img-fluid" alt="interactive_icon_v1_1">
                                    </div>
                                    <div class="item2_container">
                                        <img src="public/images/interactive_icon_v1_2.webp" class="img-fluid" alt="interactive_icon_v1_2">
                                    </div>
                                    <div class="item3_container">
                                        <img src="public/images/interactive_icon_v1_3.webp" class="img-fluid" alt="interactive_icon_v1_3">
                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="col-md-6 col-12">
                            <div class="right">
                                <div class="content_banner">
                                    <h2>Special Fruits</h2>
                                    <h1>ORGANICI STORE</h1>
                                    <div class="price">
                                        <p>- Only -<br> <span> $ 99.00 </span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- CONTENT -->

    <div class="content">
        <!-- CONTENT 1 -->
        <div class="content1">
            <div class="container">
                <h1>Our New Products</h1>
                <p id="grey">
                    Maecenas tristique gravida odio, et sagi ttis justo interdum porta. Duis et lacus mattis, tincidunt
                    eronec dictum non nulla.
                </p>
                <!-- list COLLECTION-->
                <!-- Hồ Sĩ Hùng
                Chọn sản phẩm theo Category
                29/11/2020
                 -->
                <div class="collection">
                    <ul>
                        <li>
                            <a href="productCategory?idCategory=<?= $categoriesList[0]['category_id'] ?>"><img src="public/images/collection_icon_v1_1.webp" alt="products"><br>

                                <p style="color: #61c29b;">ORGANIC VEGETABLE</p>
                            </a>
                        </li>
                        <li>
                            <a href="productCategory?idCategory=<?= $categoriesList[1]['category_id'] ?>"><img src="public/images/collection_icon_v1_2.webp" alt="products"><br>
                                <p style="color: #FF6633;">FRUITS</p>
                            </a>
                        </li>
                        <li>
                            <a href="productCategory?idCategory=<?= $categoriesList[2]['category_id'] ?>"><img src="public/images/collection_icon_v1_3.webp" alt="products"><br>
                                <p style="color: #009966;">VEGETABLE</p>

                            </a>
                        </li>
                        <li>
                            <a href="productCategory?idCategory=<?= $categoriesList[3]['category_id'] ?>"><img src="public/images/collection_icon_v1_4.webp" alt="products"><br>
                                <p style="color: #FF9933;">MEAT, SEAFOOD</p>
                            </a>
                        </li>
                        <li>
                            <a href="productCategory?idCategory=<?= $categoriesList[4]['category_id'] ?>"><img src="public/images/collection_icon_v1_5.webp" alt="products"><br>
                                <p style="color: #00CCFF;">ORTHERS</p>
                            </a>
                        </li>
                    </ul>
                </div>


                <!-- CHỌN TIÊU CHÍ ĐÁNH GIÁ, CHỌN SỐ SẢN PHẨM HIỂN THỊ MỖI TRANG -->
                <!-- Hồ Sĩ Hùng
                02/12/2020 -->
                <div class="container">
                    <div class="wrap_LocSP_TieuChi_SoSP" style="background: rgb(30,36,0);
background: linear-gradient(90deg, rgba(30,36,0,0.981127485173757) 0%, rgba(238,236,10,1) 0%, rgba(0,255,119,1) 100%); padding: 10px 0">

                        <!-- CHỌN SỐ SẢN PHẨM HIỂN THỊ MỖI TRANG -->
                        <div class="row rows-cols-2">
                            <div class="col-md-4 d-flex justify-content-center">
                                <div class="dropdown">
                                    <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Number of Items
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                        <a class="dropdown-item" href="index.php">
                                            <form action="index.php" method="get">
                                                <input type="hidden" name="itemsPerPage" value="16">
                                                <input type="hidden" name="sortType" value=<?= $sortType ?>>
                                                <button class="btn btn-success btn-block" type="submit">16</button>
                                            </form>
                                        </a>
                                        <a class="dropdown-item" href="index.php">
                                            <form action="index.php" method="get">
                                                <input type="hidden" name="itemsPerPage" value="24">
                                                <input type="hidden" name="sortType" value=<?= $sortType ?>>
                                                <button class="btn btn-success btn-block" type="submit">24</button>
                                            </form>
                                        </a>
                                        <a class="dropdown-item" href="index.php">
                                            <form action="index.php" method="get">
                                                <input type="hidden" name="itemsPerPage" value="32">
                                                <input type="hidden" name="sortType" value=<?= $sortType ?>>
                                                <button class="btn btn-success btn-block" type="submit">32</button>
                                            </form>
                                        </a>
                                        <a class="dropdown-item" href="index.php">
                                            <form action="index.php" method="get">
                                                <input type="hidden" name="itemsPerPage" value="40">
                                                <input type="hidden" name="sortType" value=<?= $sortType ?>>
                                                <button class="btn btn-success btn-block" type="submit">40</button>
                                            </form>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">

                            </div>

                            <!-- CHỌN TIÊU CHÍ SẮP XẾP -->
                            <div class="col-md-4 d-flex justify-content-center">
                                <div class="dropdown">
                                    <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Order by
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="index.php">
                                            <form action="index.php" method="get">
                                                <input type="hidden" name="sortType" value="default">
                                                <input type="hidden" name="itemsPerPage" value=<?= $itemsPerPage ?>>
                                                <button class="btn btn-success btn-block" type="submit">Default</button>
                                            </form>
                                        </a>
                                        <a class="dropdown-item" href="index.php">
                                            <form action="index.php" method="get">
                                                <input type="hidden" name="sortType" value="customerReview">
                                                <input type="hidden" name="itemsPerPage" value=<?= $itemsPerPage ?>>
                                                <button class="btn btn-success btn-block" type="submit">Customer review</button>
                                            </form>
                                        </a>

                                        <a class="dropdown-item" href="index.php">
                                            <form action="index.php" method="get">
                                                <input type="hidden" name="sortType" value="price_asc">
                                                <input type="hidden" name="itemsPerPage" value=<?= $itemsPerPage ?>>
                                                <button class="btn btn-success btn-block" type="submit">Price : Low to High</button>
                                            </form>
                                        </a>
                                        <a class="dropdown-item" href="index.php">
                                            <form action="index.php" method="get">
                                                <input type="hidden" name="sortType" value="price_des">
                                                <input type="hidden" name="itemsPerPage" value=<?= $itemsPerPage ?>>
                                                <button class="btn btn-success btn-block" type="submit">Price : High to Low</button>
                                            </form>
                                        </a>
                                        <a class="dropdown-item" href="index.php">
                                            <form action="index.php" method="get">
                                                <input type="hidden" name="sortType" value="name_asc">
                                                <input type="hidden" name="itemsPerPage" value=<?= $itemsPerPage ?>>
                                                <button class="btn btn-success btn-block" type="submit">Name : A to Z</button>
                                            </form>
                                        </a>
                                        <a class="dropdown-item" href="index.php">
                                            <form action="index.php" method="get">
                                                <input type="hidden" name="sortType" value="name_des">
                                                <input type="hidden" name="itemsPerPage" value=<?= $itemsPerPage ?>>
                                                <button class="btn btn-success btn-block" type="submit">Name : Z to A</button>
                                            </form>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PRODUCT -->
                <!-- Menu Hiển thị danh sách sản phẩm
        Thực hiện:
        Hồ Sĩ Hùng 26/11/2020 -->
                <div class="product">
                    <div class="container">
                        <div class="row">
                            <?php
                            foreach ($productsList as $item) {
                            ?>
                                <div class="col-md-3 my-3">
                                    <div class="item">
                                        <div class="wrappicture">
                                            <div class="picture">
                                                <a href="productDetail?idProduct=<?= $item['product_id'] ?>">
                                                    <img src="./public/images/products/<?= $item['product_photo'] ?>" style="position:relative" class="img-fluid">

                                                    <?php
                                                    if ($item['product_ispopular'] == 1) {
                                                        echo '<img src="./public/images/bestSeller_trans.png" 
                                                        style="position:absolute; width: 60px; top:0; left:20px;" alt="best_Seller">';
                                                    }
                                                    ?>
                                                </a>

                                            </div>
                                        </div>

                                        <p>
                                            <!-- Hiển thị số sao đánh giá -->
                                            <span class="star">
                                                <?php
                                                for ($i = 1; $i <= $item['product_quality']; $i++) {
                                                ?>
                                                    <i class="fas fa-star"></i>
                                                <?php
                                                }
                                                ?>

                                                <?php
                                                for ($i = 1; $i <= 5 - $item['product_quality']; $i++) {
                                                ?>
                                                    <i class="far fa-star"></i>
                                                <?php
                                                }
                                                ?>
                                            </span>
                                            <br><br>
                                            <a class="name_product" href="productDetail?idProduct=<?= $item['product_id'] ?>"><?= $item['product_name'] ?></a>
                                        </p>

                                        <p class="price">
                                            <?= number_format($item['product_price']) ?> VNĐ
                                        </p>

                                        <!-- GALLERY PHOTOS -->
                                        <div class="gallery">
                                            <div class="row">
                                                <?php
                                                $arrPhoto = explode("#", $item['product_more_photo']);
                                                if (count($arrPhoto) > 0 && $item['product_more_photo'] != "") {
                                                    foreach ($arrPhoto as  $photo) {
                                                        echo '<div class="col-md-3 my-1">'
                                                            . '<img src="./public/images/products/' . $photo . '"class="img-fluid img-thumbnail" alt="">'
                                                            . '</div>';
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <br>

                                        <!-- SELECT OPTION -->
                                        <div class="addtocart">
                                            <a href="productDetail?idProduct=<?= $item['product_id'] ?>" class="btn
                                            <?php
                                            if ($item['product_state'] === 0) {
                                                echo "disabled";
                                            }
                                            ?>
                                            ">
                                                <?php
                                                if ($item['product_state'] === 0) {
                                                    echo "OUT OF STOCK";
                                                } else {
                                                    echo "<i class=\"fas fa-shopping-cart\"></i>" . "ADD TO CART";
                                                }
                                                ?>
                                            </a>
                                        </div>

                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PAGINATION
                HỒ SĨ HÙNG - LÊ TUẤN LIÊM
                29/11/2020 -->
        <div class="wrap_pagination">
            <?php

            $pagination = new pagination();
            $pagination->createPagesInHomePage($productsModel->countAllProducts(), $itemsPerPage, $page, $sortType);
            ?>
        </div>


        <!-- CONTENT 2 -->
        <div class="content2">
            <div class="container">
                <div class="content_ctn2">
                    <h1>Featured Products</h1>
                    <p>
                        Maecenas tristique gravida odio, et sagi ttis justo interdum porta
                    </p>
                </div>
            </div>

            <!-- FEATURE -->
            <div class="container-fluid">
                <div class="row px-0">
                    <div class="col-md-4 mx-0 px-0">
                        <img src="public/images/feature_image_v1_1.webp" class="img-fluid" alt="feature_image_v1_1">
                        <img src="public/images/feature_image_v1_4.webp" class="img-fluid" alt="feature_image_v1_4">
                    </div>
                    <div class="col-md-4 mx-0 px-0">
                        <img src="public/images/feature_image_v1_2.webp" class="img-fluid" alt="feature_image_v1_2">
                        <img src="public/images/feature_image_v1_5.webp" class="img-fluid" alt="feature_image_v1_5">
                        <img src="public/images/feature_image_v1_6.webp" class="img-fluid" alt="feature_image_v1_6">
                    </div>
                    <div class="col-md-4 mx-0 px-0">
                        <img src="public/images/feature_image_v1_3.webp" class="img-fluid" alt="feature_image_v1_3">
                        <img src="public/images/feature_image_v1_7.webp" class="img-fluid" alt="feature_image_v1_7">
                    </div>
                </div>
            </div>
        </div>

        <!-- CONTENT 3 -->
        <div class="content3">
            <div class="container">
                <div class="farmer_title">
                    <h2>
                        Our Farmer
                    </h2>
                    <p>
                        Fusce sem enim, rhoncus volutpat condimentum ac, placerat semper ligula. Suspendisse in
                        viverra justo ipsum dolor sit amet, consectetur adipiscing elit.
                    </p>
                </div>
                <div class="row">

                    <!-- FARMER 1 -->
                    <div class="col-md-4 col-sm-6">
                        <div class="frame_farmer">
                            <div class="farmer_img">
                                <img src="public/images/out_team_image_1.webp" class="img-fluid" alt="out_team_image_1">
                            </div>
                            <div class="farmer_content">
                                <h4>
                                    Tristique
                                </h4>
                                <p>
                                    Fusce sem enim, rhoncus volutpat condimentum ac, placerat semper ligula. Suspendisse
                                    in viverra justo ipsum dolor sit amet, consectetur adipiscing elit.
                                </p>
                                <span>
                                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                                    <a href="#"><i class="fab fa-twitter-square"></i></a>
                                    <a href="#"><i class="fab fa-google"></i></a>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- FARMER 2 -->
                    <div class="col-md-4 col-sm-6">
                        <div class="frame_farmer">
                            <div class="farmer_img">
                                <img src="public/images/out_team_image_2.webp" class="img-fluid" alt="out_team_image_2">
                            </div>
                            <div class="farmer_content">
                                <h4>
                                    Alyssa Hiyama
                                </h4>
                                <p>
                                    Fusce sem enim, rhoncus volutpat condimentum ac, placerat semper ligula. Suspendisse
                                    in viverra justo ipsum dolor sit amet, consectetur adipiscing elit.
                                </p>
                                <span>
                                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                                    <a href="#"><i class="fab fa-twitter-square"></i></a>
                                    <a href="#"><i class="fab fa-instagram-square"></i></a>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- FARMER 3 -->
                    <div class="col-md-4 col-sm-6">
                        <div class="frame_farmer">
                            <div class="farmer_img">
                                <img src="public/images/out_team_image_3.webp" class="img-fluid" alt="out_team_image_3">
                            </div>
                            <div class="farmer_content">
                                <h4>
                                    Alberto Trombin
                                </h4>
                                <p>
                                    Fusce sem enim, rhoncus volutpat condimentum ac, placerat semper ligula. Suspendisse
                                    in viverra justo ipsum dolor sit amet, consectetur adipiscing elit.
                                </p>
                                <span>
                                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                                    <a href="#"><i class="fab fa-twitter-square"></i></a>
                                    <a href="#"><i class="fab fa-pinterest"></i></a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CONTENT 4 -->
        <div class="content4">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="content4_left">
                            <img width="194" height="66" src="public/images/call_to_action_image.webp" class="img-fluid" alt="call_to_action_image">
                            <h2>
                                Organic Products!
                            </h2>
                            <p>
                                Maecenas tristique gravida odio, et sagittis justo interdum porta. Duislacus mattis,
                                tincidunt eros ac, consequat tortor.

                            </p>

                            <a href="#">SHOP NOW</a>

                        </div>
                    </div>
                    <div class="col-sm-6">

                    </div>
                </div>
            </div>
        </div>

        <!-- CONTENT 5 -->
        <div class="content5">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <!-- TITLE -->
                        <div class="title">
                            <h2>
                                Blog Updates
                            </h2>
                            <p>
                                Maecenas tristique gravida, odio et sagi ttis justo. Susp endisse ultricies nisi vel
                                quam suscipit, et rutrum odio porttitor. Donec...
                            </p>
                        </div>
                        <!-- CATALOG -->
                        <div class="catalog">
                            <div class="row">
                                <!-- ITEM 1 -->
                                <div class="col-md-4 col-sm-6">

                                    <div class="item">
                                        <div class="picture">
                                            <a href="#"><img src="public/images/blog_1_1024x1024.webp" class="img-fluid" alt="blog_1"></a>
                                        </div>
                                        <div class="item_content">
                                            <span class="cat">
                                                <a href="#">ORGANIC FOOD</a> /
                                                <a href="#">TIPS & GUIDES</a>
                                            </span>

                                            <h3>
                                                <a href="#">Tips For Ripening Your Fruit</a>
                                            </h3>

                                            <p>
                                                As more and more people are turning to more organic lifestyles and
                                                trying to improve their health through adopting better...
                                            </p>

                                            <a class="viewmore" href="#">VIEW MORE</a>
                                        </div>
                                    </div>
                                </div>

                                <!-- ITEM 2 -->
                                <div class="col-md-4 col-sm-6">
                                    <div class="item">
                                        <div class="picture">
                                            <a href="#"><img src="public/images/blog_2_1024x1024.webp" class="img-fluid" alt="blog_2"></a>
                                        </div>
                                        <div class="item_content">
                                            <span class="cat">
                                                <a href="#">GENERAL</a> /
                                                <a href="#">RECIPES</a>
                                            </span>

                                            <h3>
                                                <a href="#">Feeding Kids Organic Food</a>
                                            </h3>

                                            <p>
                                                Maecenas tristique gravida, odio et sagi ttis justo. Susp endisse
                                                ultricies nisi vel quam suscipit, et rutrum odio porttitor. Donec dictum
                                                non nulla ut lobortis....
                                            </p>

                                            <a class="viewmore" href="#">VIEW MORE</a>
                                        </div>
                                    </div>
                                </div>

                                <!-- ITEM 3 -->
                                <div class="col-md-4 col-sm-6">
                                    <div class="item">
                                        <div class="picture">
                                            <a href="#"><img src="public/images/blog_3_91bed834-9d83-49d4-b534-ddc1219cc5a7_1024x1024.webp" class="img-fluid" alt="blog_3"></a>
                                        </div>
                                        <div class="item_content">
                                            <span class="cat">
                                                <a href="#">DIET</a> /
                                                <a href="#">ORGANIC FOOD</a>
                                            </span>

                                            <h3>
                                                <a href="#">Health Benefits Of A Raw Food</a>
                                            </h3>

                                            <p>
                                                Maecenas tristique gravida, odio et sagi ttis justo. Susp endisse
                                                ultricies nisi vel quam suscipit, et rutrum odio porttitor. Donec dictum
                                                non nulla ut lobortis....
                                            </p>

                                            <a class="viewmore" href="#">VIEW MORE</a>
                                        </div>
                                    </div>
                                </div>


                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CONTENT 6 -->
        <div class="content6">
            <div class="layout">
                <div class="wrap">
                    <img width="328" height="851" src="public/images/testimonial_v1_image_left.webp" class="img-left" alt="left">

                    <img width="329" height="789" src="public/images/testimonial_v1_image_right.webp" class="img-right" alt="right">

                    <div class="container">
                        <div class="row">
                            <div class="col-2"></div>
                            <div class="col-8">
                                <div class="character">
                                    <h2>
                                        Ashley Simpsons
                                    </h2>
                                    <div class="quote">
                                        <i class="fas fa-quote-left "></i>
                                        <p>
                                            Maecenas tristique gravida odio, et sagi ttis justo interdum porta. Duis et
                                            lacus mattis, tincidunt ero. Donec dictum non nulla ut tris tique gravida
                                            odio lobortis tristique gravida. Aliquam erat volutpat. Pellentesque auctor,
                                            arcu id tristique.
                                        </p>
                                        <i class="fas fa-quote-right right"></i>
                                    </div>
                                </div>

                                <!-- CAROUSEL -->
                                <div class="carousel_content">
                                    <div id="carouselId" class="carousel slide" data-ride="carousel">
                                        <ol class="carousel-indicators">
                                            <li data-target="#carouselId" data-slide-to="0" class="active"></li>
                                            <li data-target="#carouselId" data-slide-to="1"></li>
                                            <li data-target="#carouselId" data-slide-to="2"></li>
                                        </ol>
                                        <div class="carousel-inner set_height" role="listbox">
                                            <div class="carousel-item active">
                                                <img src="public/images/testimonial_v1_image_2.webp" alt="First slide">
                                                <div class="carousel-caption set_position d-none d-md-block">
                                                    <h3>Ashley</h3>
                                                    <p>( Developer )</p>
                                                </div>
                                            </div>
                                            <div class="carousel-item">
                                                <img src="public/images/testimonial_v1_image_3.webp" alt="Second slide">
                                                <div class="carousel-caption set_position d-none d-md-block">
                                                    <h3>Olivia</h3>
                                                    <p>( Web Desinger )</p>
                                                </div>
                                            </div>
                                            <div class="carousel-item">
                                                <img src="public/images/testimonial_v1_image_4.webp" alt="Third slide">
                                                <div class="carousel-caption set_position d-none d-md-block">
                                                    <h3>Tyrion</h3>
                                                    <p>( CEO )</p>
                                                </div>
                                            </div>
                                        </div>
                                        <a class="carousel-control-prev" href="#carouselId" role="button" data-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <a class="carousel-control-next" href="#carouselId" role="button" data-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">

                            </div>

                        </div>

                    </div>
                </div>
                <div class="wrap_subscribe">
                    <div class="subscribe">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-5 col-12">
                                    <h3>
                                        Subscribe To Us!
                                    </h3>
                                    <p>
                                        Enter Your email address for our mailing list to keep yourself update.
                                    </p>
                                </div>
                                <div class="col-md-7 col-12">
                                    <form action="#" method="post" name="subscribe-form">
                                        <div class="newsletter-form">
                                            <input type="email" value="" placeholder="email@example.com" name="EMAIL" id="mail">
                                            <input type="submit" name="subscribe" id="subscribe" value="submit">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- FOOTER -->
    <footer>
        <div class="container">
            <div class="row">
                <!-- COL 1 -->
                <div class="col-md-3 col-sm-6">
                    <div class="left_content">
                        <!-- TOP -->
                        <div class="top">
                            <a href="#"><img src="public/images/footer_v1_logo.webp" alt="footer_v1_logo"></a>
                            <p>
                                Maecenas tristique gravida, odio et sagi ttis justo. Suspendisse ultricies nisi veafn.
                                onec dictum non nulla ut lobortis tellus.
                            </p>
                        </div>

                        <!-- MID -->
                        <div class="mid">
                            <div class="social_icon">
                                <a class="icon" href="#"><i class="fab fa-facebook-f"></i></a>
                                <a class="icon" href="#"><i class="fab fa-google-plus-g"></i></a>
                                <a class="icon" href="#"><i class="fab fa-twitter"></i></a>
                                <a class="icon" href="#"><i class="fab fa-pinterest-p"></i></a>
                                <a class="icon" href="#"><i class="fab fa-flickr"></i></a>
                            </div>
                        </div>

                        <!-- BOTTOM -->
                        <div class="bottom">
                            <div class="copyright">
                                2016 Oganici.<br class="br"> Designed with <i class="fa fa-heart-o"></i> by TK-Themes.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- COL 2 -->
                <div class="col-md-3 col-sm-6">
                    <div class="widget_text">
                        <h4 class="widget-title">Contact</h4>
                        <div class="textwidget">
                            <h5 id="first_h5">Address</h5>
                            <p>No 13, Sky Tower Street, New York, USA</p>
                            <h5>Hotline</h5>
                            <p>
                                <a href="#">(+844) 123 456 78</a><br> <a href="#">(+844) 888 97989</a>
                            </p>
                            <h5>Email</h5>
                            <p>
                                <a href="#">
                                    contact@organicistore.com
                                </a>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- COL 3 -->
                <div class="col-md-3 col-sm-6">
                    <div class="widget_flickr">
                        <h4 class="widget-title">PHOTO IN FLICKR</h4>
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-4 py-2 px-0 col-3">
                                    <a href="#"><img src="public/images/49971120798_969fd05cb8_s.jpg" alt="1"></a>
                                </div>
                                <div class="col-md-4 py-2 px-0 col-3">
                                    <a href="#"><img src="public/images/49971636826_902d3d19b5_s.jpg" alt="2"></a>
                                </div>
                                <div class="col-md-4 py-2 px-0 col-3">
                                    <a href="#"><img src="public/images/49971636906_9c1bc91e4a_s.jpg" alt="3"></a>
                                </div>
                                <div class="col-md-4 py-2 px-0 col-3">
                                    <a href="#"><img src="public/images/49971121318_284e730dea_s.jpg" alt="4"></a>
                                </div>
                                <div class="col-md-4 py-2 px-0 col-3">
                                    <a href="#"><img src="public/images/49971121403_1cb43aa23b_s.jpg" alt="5"></a>
                                </div>
                                <div class="col-md-4 py-2 px-0 col-3">
                                    <a href="#"><img src="public/images/49971899867_945a724c1e_s.jpg" alt="6"></a>
                                </div>
                                <div class="col-md-4 py-2 px-0 col-3">
                                    <a href="#"><img src="public/images/49971900372_64fafbf84d_s.jpg" alt="7"></a>
                                </div>
                                <div class="col-md-4 py-2 px-0 col-3">
                                    <a href="#"><img src="public/images/49971900442_20a98a60ab_s.jpg" alt="8"></a>
                                </div>
                                <div class="col-md-4 py-2 px-0 col-3">
                                    <a href="#"><img src="public/images/49971900497_1eba3bbc21_s.jpg" alt="9"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- COL 4 -->
                <div class="col-md-3 col-sm-6">
                    <div class="widget_last">
                        <h4 class="widget-title">WORKING TIME</h4>
                        <ul class="openhours">
                            <li>
                                <span>Monday to Friday: </span>
                                <span>08:00am - 08:00pm </span>
                            </li>
                            <li>
                                <span>Saturday &amp; Sunday: </span>
                                <span>10:00am - 06:00pm </span>
                            </li>
                        </ul>
                    </div>
                    <div class="widget_happyhours">
                        <h4>Happy Hours</h4>
                        <ul class="happyhours">
                            <li>
                                <div>Enjoy discount baked goods. </div>
                                <div>06:00 am - 08:00 pm daily </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </footer>

    <!-- BACK TO HOME -->
    <a class="back-to-home" href="#"><i class="fas fa-caret-square-up"></i></a>
    <!-- SCRIPT WHEN SELECTING IMAGES -->
    <script>
        const items = document.querySelectorAll(".item");
        const galleries = document.querySelectorAll(".gallery");

        for (let index = 0; index < items.length; index++) {

            let displayPhoto = items[index].querySelector(".wrappicture .picture img");
            let morePhotos = galleries[index].querySelectorAll("img");

            for (let i = 0; i < morePhotos.length; i++) {
                morePhotos[i].addEventListener("mouseover", function() {
                    displayPhoto.setAttribute("src", morePhotos[i].getAttribute("src"));
                });
            }

            items[index].addEventListener("mouseout", function() {
                displayPhoto.setAttribute("src", morePhotos[0].getAttribute("src"));
            })
        }
    </script>
</body>

</html>