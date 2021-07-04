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

//Lấy ID bill
$idBill = $_GET["idBill"];

//Tạo biến Model
$productsModel = new productsModel();
$categoriesModel = new categoriesModel();
$billsModel = new billsModel();
$billDetailModel = new billDetailModel();

//Sắp xếp sản phẩm
$sortType = "Default";

//Lấy dữ liệu
$categoriesList = $categoriesModel->getCategories();
$bills = $billDetailModel->getDataByIDBill($idBill);
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

        </div>
    </header>

    <!-- BILL LISTS -->
    <!-- Menu Hiển thị CHI TIẾT hóa đơn
        Thực hiện:
        hồ Sĩ Hùng 30/12/2020 -->
    <div class="content">
        <!-- CONTENT 1 -->
        <div class="content1">
            <div class="container">
                <h1 style="font-family: Arial, Helvetica, sans-serif; color:red;">BILL DETAIL</h1>
                <div class="row">
                    <table class="table table-striped">
                        <!-- title ROW -->
                        <tr class="table-success">
                            <td width="5%" style="font-weight: bold; text-align:center;">ID Bill</td>
                            <td width="5%" style="font-weight: bold; text-align:center;">Images</td>
                            <td width="10%" style="font-weight: bold; text-align:center;">Product name</td>
                            <td width="5%" style="font-weight: bold; text-align:center;">Price</td>
                            <td width="5%" style="font-weight: bold; text-align:center;">Quality</td>
                            <td width="5%" style="font-weight: bold; text-align:center;">Total</td>
                        </tr>
                        <?php
                        $Total = 0;
                        foreach ($bills as $bill) {
                        ?>
                            <tr class="table-info">
                                <!-- LẤY THÔNG TIN SẢN PHẨM ĐÃ MUA -->
                                <?php
                                $pickedProduct = $productsModel->getProductByID($bill["id_product"]);
                                ?>

                                <!-- HIỂN THỊ ID HÓA ĐƠN -->
                                <td width="5%" style="font-weight: bold;  text-align:center;"><?= $bill["id_bill"] ?></td>

                                <!-- HIỂN THỊ ẢNH SẢN PHẨM -->
                                <td width="5%" style="text-align:center;">
                                    <img src="./public/images/products/<?= $pickedProduct["product_photo"] ?>" style="width: 100px" alt="photo">
                                </td>

                                <!-- HIỂN THỊ TÊN SP -->
                                <td width="10%" style="text-align:center;">
                                    <?php
                                    echo $pickedProduct["product_name"];
                                    $Total = $Total + $pickedProduct["product_price"] * $bill["quantity"];
                                    ?>
                                </td>

                                <!-- HIỂN THỊ GIÁ SP -->
                                <td width="5%" style="font-weight: bold; text-align:center;">
                                    <?= number_format($pickedProduct["product_price"])  ?>
                                </td>
                                </td>

                                <!-- HIỂN THỊ SỐ LƯỢNG MUA -->
                                <td width="5%" style="font-weight: bold;  text-align:center;"><?= $bill["quantity"] ?></td>

                                <!-- HIỂN THỊ TỔNG TIỀN -->
                                <td width="5%" style="font-weight: bold; text-align:center;">
                                    <?= number_format($pickedProduct["product_price"] * $bill["quantity"])  ?>
                                </td>

                            </tr>
                        <?php
                        }
                        ?>

                        <!-- TOTAL ROW -->
                        <tr class="table-success">
                            <td width="5%" style="font-weight: bold; text-align:center;"></td>
                            <td width="5%" style="font-weight: bold; text-align:center;"></td>
                            <td width="10%" style="font-weight: bold; text-align:center;"> </td>
                            <td width="5%" style="font-weight: bold; text-align:center;"></td>
                            <td width="5%" style="font-weight: bold; text-align:center;"></td>

                            <!-- HIỂN THỊ TỔNG TIỀN -->
                            <td width="5%" style="font-weight: bold; text-align:center;">
                                <?= '<p style="color:red; font-weight: bold">' . number_format($Total)  . '</p> ' ?>
                            </td>

                        </tr>
                    </table>
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

</body>

</html>