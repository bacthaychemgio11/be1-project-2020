<!-- CONNECT PHP FILES -->
<?php

//SESSION
session_start();

if (isset($_POST["logOut"])) {
    session_destroy();
    header("Location: http://localhost:82/Git%20Hub/themelogin.php");
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

    /* PRODUCTS PROPERTIES */
    .content .wrap_productDetail {
        margin: 45px;
    }

    .content .wrap_productDetail .wrap_picture {
        width: 80%;
    }

    /* HOVER IMAGE */
    .content .wrap_picture .picture:hover img {
        border: 1px solid #61c29b;
        box-shadow: 5px 5px 8px #E6E6E6;
    }

    .content .wrap_picture .picture:hover img {
        transform: scale(1.2);
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
                    </div>

                    <div class="col-md-4">
                        <ul>
                            <li>
                                <?php
                                if (!isset($_SESSION["admin"])) {
                                ?>
                                    <i class="fas fa-share"></i> <a href="themelogin">Click here to log in!</a>
                                <?php
                                }
                                ?>
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

                <a class="navbar-brand" href="admin.php"><img src="public/images/header_v2_logo.webp" alt="logo"></a>
                <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#collapsibleNavId" aria-controls="collapsibleNavId" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="collapsibleNavId">
                    <ul class="navbar-nav ml-auto mt-2 mt-lg-0">

                        <li class="nav-item">
                            <?php
                            if (isset($_SESSION["admin"])) {
                            ?>
                                <button type="button" class="btn btn-success">
                                    Hi <?= $_SESSION["admin"] ?>
                                </button>
                            <?php
                            }
                            ?>

                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="dropdownId" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">CATEGORIES</a>
                            <div class="dropdown-menu" aria-labelledby="dropdownId">
                                <?php
                                foreach ($categoriesList as $item) {
                                ?>
                                    <a class="dropdown-item" href="manageCategoryDetail?idCategory=<?= $item['category_id'] ?>"><?= $item['category_name'] ?></a>
                                <?php
                                }
                                ?>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="manageproducts.php">PRODUCTS</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">ACCOUNTS</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="bills.php">BILLS</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="managecategories">CATEGORIES</a>
                        </li>

                        <li class="nav-item">
                            <!-- CHỨC NĂNG LOG OUT -->
                            <?php
                            if (isset($_SESSION["admin"])) {
                            ?>
                                <form action="admin.php" method="post">
                                    <button class="btn btn-info btn-block" type="submit" name="logOut">Log Out</button>
                                </form>
                            <?php
                            }
                            ?>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>

        <!-- BANNER -->
        <div class="banner">
            <div class="container">
                <div class="row">
                    <div class="col-12 px-6 hidden-xs">
                        <img src="./public/images/admin_background.jpg" alt="background" class="img-fluid">
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
                                        <a class="dropdown-item" href="admin.php">
                                            <form action="admin.php" method="get">
                                                <input type="hidden" name="itemsPerPage" value="15">
                                                <input type="hidden" name="sortType" value=<?= $sortType ?>>
                                                <button class="btn btn-success btn-block" type="submit">15</button>
                                            </form>
                                        </a>
                                        <a class="dropdown-item" href="admin.php">
                                            <form action="admin.php" method="get">
                                                <input type="hidden" name="itemsPerPage" value="25">
                                                <input type="hidden" name="sortType" value=<?= $sortType ?>>
                                                <button class="btn btn-success btn-block" type="submit">25</button>
                                            </form>
                                        </a>
                                        <a class="dropdown-item" href="admin.php">
                                            <form action="admin.php" method="get">
                                                <input type="hidden" name="itemsPerPage" value="35">
                                                <input type="hidden" name="sortType" value=<?= $sortType ?>>
                                                <button class="btn btn-success btn-block" type="submit">35</button>
                                            </form>
                                        </a>
                                        <a class="dropdown-item" href="admin.php">
                                            <form action="admin.php" method="get">
                                                <input type="hidden" name="itemsPerPage" value="45">
                                                <input type="hidden" name="sortType" value=<?= $sortType ?>>
                                                <button class="btn btn-success btn-block" type="submit">45</button>
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
                                        <a class="dropdown-item" href="admin.php">
                                            <form action="admin.php" method="get">
                                                <input type="hidden" name="sortType" value="default">
                                                <input type="hidden" name="itemsPerPage" value=<?= $itemsPerPage ?>>
                                                <button class="btn btn-success btn-block" type="submit">Default</button>
                                            </form>
                                        </a>
                                        <a class="dropdown-item" href="admin.php">
                                            <form action="admin.php" method="get">
                                                <input type="hidden" name="sortType" value="customerReview">
                                                <input type="hidden" name="itemsPerPage" value=<?= $itemsPerPage ?>>
                                                <button class="btn btn-success btn-block" type="submit">Customer review</button>
                                            </form>
                                        </a>

                                        <a class="dropdown-item" href="admin.php">
                                            <form action="admin.php" method="get">
                                                <input type="hidden" name="sortType" value="price_asc">
                                                <input type="hidden" name="itemsPerPage" value=<?= $itemsPerPage ?>>
                                                <button class="btn btn-success btn-block" type="submit">Price : Low to High</button>
                                            </form>
                                        </a>
                                        <a class="dropdown-item" href="admin.php">
                                            <form action="admin.php" method="get">
                                                <input type="hidden" name="sortType" value="price_des">
                                                <input type="hidden" name="itemsPerPage" value=<?= $itemsPerPage ?>>
                                                <button class="btn btn-success btn-block" type="submit">Price : High to Low</button>
                                            </form>
                                        </a>
                                        <a class="dropdown-item" href="admin.php">
                                            <form action="admin.php" method="get">
                                                <input type="hidden" name="sortType" value="name_asc">
                                                <input type="hidden" name="itemsPerPage" value=<?= $itemsPerPage ?>>
                                                <button class="btn btn-success btn-block" type="submit">Name : A to Z</button>
                                            </form>
                                        </a>
                                        <a class="dropdown-item" href="admin.php">
                                            <form action="admin.php" method="get">
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
                                                <a href="manageproductDetail?idProduct=<?= $item['product_id'] ?>">
                                                    <img src="./public/images/products/<?= $item['product_photo'] ?>" style="display: relative;" class="img-fluid">

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
                                            <a class="name_product" href="manageproductDetail?idProduct=<?= $item['product_id'] ?>"><?= $item['product_name'] ?></a>
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
                                            <a href="manageproductDetail?idProduct=<?= $item['product_id'] ?>" class="btn
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
            $pagination->createPagesInManageProductsPage($productsModel->countAllProducts(), $itemsPerPage, $page, $sortType);
            ?>
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