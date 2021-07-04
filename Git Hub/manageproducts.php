<!-- MANAGE PRODUCTS -->
<!-- HO SI HUNG 20/12/2020 -->
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
$itemsPerPage = 25;
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

//CHỨC NĂNG XÓA DỮ LIỆU
//HỒ SĨ HÙNG 24/12/2020
if (isset($_POST["idDelete"]) && isset($_SESSION["admin"])) {
    if ($productsModel->deleteProducts($_POST["idDelete"]) > 0) {
        echo '<script>alert("Your product was deleted!")</script>';
        
        //Cập nhập lại productsList
        $productsList = $productsModel->getProductsByPage($page, $itemsPerPage, $sortType);
    }
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
                            <a class="nav-link" href="#">PRODUCTS</a>
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

        </div>
    </header>

    <!-- CONTENT -->

    <div class="content">
        <!-- CONTENT 1 -->
        <div class="content1">
            <div class="container">
                <h1 style="font-family: Arial, Helvetica, sans-serif; color:red;">Products List</h1>

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
                                        <a class="dropdown-item" href="manageproducts.php">
                                            <form action="manageproducts.php" method="get">
                                                <input type="hidden" name="itemsPerPage" value="15">
                                                <input type="hidden" name="sortType" value=<?= $sortType ?>>
                                                <button class="btn btn-success btn-block" type="submit">15</button>
                                            </form>
                                        </a>
                                        <a class="dropdown-item" href="manageproducts.php">
                                            <form action="manageproducts.php" method="get">
                                                <input type="hidden" name="itemsPerPage" value="25">
                                                <input type="hidden" name="sortType" value=<?= $sortType ?>>
                                                <button class="btn btn-success btn-block" type="submit">25</button>
                                            </form>
                                        </a>
                                        <a class="dropdown-item" href="manageproducts.php">
                                            <form action="manageproducts.php" method="get">
                                                <input type="hidden" name="itemsPerPage" value="35">
                                                <input type="hidden" name="sortType" value=<?= $sortType ?>>
                                                <button class="btn btn-success btn-block" type="submit">35</button>
                                            </form>
                                        </a>
                                        <a class="dropdown-item" href="manageproducts.php">
                                            <form action="manageproducts.php" method="get">
                                                <input type="hidden" name="itemsPerPage" value="45">
                                                <input type="hidden" name="sortType" value=<?= $sortType ?>>
                                                <button class="btn btn-success btn-block" type="submit">45</button>
                                            </form>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- CHỨC NĂNG THÊM SẢN PHẨM -->
                            <div class="col-md-4" style="text-align: center;">
                                <a href="createproducts.php">
                                    <button class="btn btn-info">Create Products</button>
                                </a>
                            </div>

                            <!-- CHỌN TIÊU CHÍ SẮP XẾP -->
                            <div class="col-md-4 d-flex justify-content-center">
                                <div class="dropdown">
                                    <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Order by
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="manageproducts.php">
                                            <form action="manageproducts.php" method="get">
                                                <input type="hidden" name="sortType" value="default">
                                                <input type="hidden" name="itemsPerPage" value=<?= $itemsPerPage ?>>
                                                <button class="btn btn-success btn-block" type="submit">Default</button>
                                            </form>
                                        </a>
                                        <a class="dropdown-item" href="manageproducts.php">
                                            <form action="manageproducts.php" method="get">
                                                <input type="hidden" name="sortType" value="customerReview">
                                                <input type="hidden" name="itemsPerPage" value=<?= $itemsPerPage ?>>
                                                <button class="btn btn-success btn-block" type="submit">Customer review</button>
                                            </form>
                                        </a>

                                        <a class="dropdown-item" href="manageproducts.php">
                                            <form action="manageproducts.php" method="get">
                                                <input type="hidden" name="sortType" value="price_asc">
                                                <input type="hidden" name="itemsPerPage" value=<?= $itemsPerPage ?>>
                                                <button class="btn btn-success btn-block" type="submit">Price : Low to High</button>
                                            </form>
                                        </a>
                                        <a class="dropdown-item" href="manageproducts.php">
                                            <form action="manageproducts.php" method="get">
                                                <input type="hidden" name="sortType" value="price_des">
                                                <input type="hidden" name="itemsPerPage" value=<?= $itemsPerPage ?>>
                                                <button class="btn btn-success btn-block" type="submit">Price : High to Low</button>
                                            </form>
                                        </a>
                                        <a class="dropdown-item" href="manageproducts.php">
                                            <form action="manageproducts.php" method="get">
                                                <input type="hidden" name="sortType" value="name_asc">
                                                <input type="hidden" name="itemsPerPage" value=<?= $itemsPerPage ?>>
                                                <button class="btn btn-success btn-block" type="submit">Name : A to Z</button>
                                            </form>
                                        </a>
                                        <a class="dropdown-item" href="manageproducts.php">
                                            <form action="manageproducts.php" method="get">
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
                <br>

                <!-- PRODUCT -->
                <!-- Menu Hiển thị danh sách sản phẩm
        Thực hiện:
        Hồ Sĩ Hùng 20/12/2020 -->
                <div class="product">
                    <div class="container">
                        <div class="row">
                            <table class="table table-striped">
                                <tr class="table-success">
                                    <td width="5%" style="font-weight: bold; text-align:center;">ID</td>
                                    <td width="10%" style="font-weight: bold; text-align:center;">Picture</td>
                                    <td width="20%" style="font-weight: bold; text-align:center;">Name</td>
                                    <td width="5%" style="font-weight: bold; text-align:center;">Price</td>
                                    <td width="5%" style="font-weight: bold; text-align:center;">Out Of Stock</td>
                                    <td width="5%" style="font-weight: bold; text-align:center;">Is Popular</td>
                                    <td width="5%" style="font-weight: bold; text-align:center;">Quality</td>
                                    <td width="15%" style="font-weight: bold; text-align:center;">Action</td>
                                </tr>
                                <?php
                                foreach ($productsList as $item) {
                                ?>
                                    <tr class="table-info">
                                        <td width="5%" style="font-weight: bold;  text-align:center;"><?= $item["product_id"] ?></td>

                                        <!-- ẢNH SẢN PHẨM -->
                                        <td width="10%" style="font-weight: bold;">
                                            <img src="./public/images/products/<?= $item["product_photo"] ?>" style="width: 100px" alt="">
                                        </td>

                                        <td width="20%" style="font-weight: bold;  text-align:center;"><?= $item["product_name"] ?></td>
                                        <td width="5%" style="font-weight: bold;  text-align:center;"><?= number_format($item["product_price"]) ?></td>

                                        <!-- TÌNH TRẠNG SẢN PHẨM -->
                                        <td width="5%" style="font-weight: bold;  text-align:center;">
                                            <?php
                                            if ($item["product_state"] == 1) {
                                                echo "No";
                                            } else {
                                                echo "Yes";
                                            } ?>
                                        </td>

                                        <!-- CÓ PHẢI LÀ SẢN PHẨM PHỔ BIẾN -->
                                        <td width="5%" style="font-weight: bold;  text-align:center;">
                                            <?php
                                            if ($item["product_ispopular"] == 0) {
                                                echo "No";
                                            } else {
                                                echo "Yes";
                                            }  ?>
                                        </td>
                                        <td width="5%" style="font-weight: bold;  text-align:center;"><?= $item["product_quality"] ?></td>

                                        <!-- CHỨC NĂNG UPDATE VÀ DELETE SẢN PHẨM -->
                                        <td width="15%" style="font-weight: bold;">
                                            <!-- CHỨC NĂNG UPDATE -->
                                            <form action="updateProducts.php" method="get" style="display: inline;">
                                                <input type="hidden" name="idUpdate" value="<?= $item["product_id"] ?>">
                                                <button type="submit" class="btn btn-warning">Update</button>
                                            </form>

                                            <!-- CHỨC NĂNG DELETE -->
                                            <form action="manageproducts.php" method="post" onsubmit="return confirm('Bạn có muốn xóa sản phẩm?');" style="display: inline;">
                                                <input type="hidden" name="idDelete" value="<?= $item["product_id"] ?>">
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </table>
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

</body>

</html>