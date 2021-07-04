<?php
//SESSION
session_start();

if (isset($_POST["logOut"])) {
    session_destroy();
    header("Location: http://localhost:82/Git%20Hub/index.php");
}

//  Load Files
require_once("./public/config/database.php");

spl_autoload_register(function ($class_name) {
    require "./public/app/models/" . $class_name . '.php';
});

//Tạo biến Model
$productsModel = new productsModel();
$categoriesModel = new categoriesModel();

//Lấy dữ liệu
$categoriesList = $categoriesModel->getCategories();

//CHỨC NĂNG SHOPPING CART
//Lấy thông tin các sản phẩm có trong cookieData
$idAccount = -1;
if (isset($_SESSION["ID_account"])) {
    $idAccount = $_SESSION["ID_account"];
}

if (isset($_COOKIE["cart"])) {
    $cart = stripslashes($_COOKIE["cart"]);
    $cookieData = json_decode($cart, true);

    $shoppingList = array();
    for ($i = 0; $i < count($cookieData[$idAccount]); $i++) {
        $product = $productsModel->getProductByID($cookieData[$idAccount][$i]['product_id']);
        $shoppingList[] = array(
            'product_id' => $product['product_id'],
            'product_photo' => $product['product_photo'],
            'product_name' => $product['product_name'],
            'product_price' => $product['product_price'],
            'quantity' => $cookieData[$idAccount][$i]['quantity']
        );
    }
}

//CHỨC NĂNG XÓA SẢN PHẨM RA KHỎI $shoppingList
if (isset($_GET["idRemove"])) {
    $key = 0;
    for ($i = 0; $i < count($cookieData[$idAccount]); $i++) {
        if ($cookieData[$idAccount][$i]["product_id"] == $_GET["idRemove"]) {
            $key = $i;
        }
    }

    //Xóa và cập nhập lại mảng $cookieData
    array_splice($cookieData[$idAccount], $key, 1);


    //Nếu cart không còn tài khoản nào mua hàng, xóa cookie
    if (count($cookieData) > 0) {
        //Cập nhập lại cookie     
        $cookieData = json_encode($cookieData);
        setcookie('cart', $cookieData, time() + 600);
        header("Location: http://localhost:82/Git%20Hub/cart.php?success=1");
    } else {
        //Xóa cookie
        $cookieData = json_encode($cookieData);
        setcookie('cart', $cookieData, time() - 3600);
        header("Location: http://localhost:82/Git%20Hub/cart.php");
    }
}

//Hiển thị thông báo xóa item thành công
$message = "";
if (isset($_GET["success"])) {
    $message = '<div class="col-md-6 offset-md-3">
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            <span class="sr-only">Close</span>
        </button>
        <strong>Item removed from cart!</strong>
    </div>
    </div>';
}

//CHỨC NĂNG XÓA GIỎ HÀNG 
if (isset($_GET["removeCart"])) {

    //XÓA CÁC SẢN PHẨM TỒN TẠI TRONG TÀI KHOẢN
    for ($i = count($cookieData[$idAccount]) - 1; $i >= 0; $i--) {
        array_splice($cookieData[$idAccount], $i, 1);
    }

    //Đóng mã cookie, cập nhập và load lại trang
    $cookieData = json_encode($cookieData);
    setcookie('cart', $cookieData, time() + 3600);
    header("Location: http://localhost:82/Git%20Hub/cart.php");
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
                                                <form action="index.php" method="post">
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
                <div class="yourcart" style="text-align: center; margin: 20px 0;">
                    <h1 style="color: red;">YOUR CART</h1>
                </div>

                <!-- HIỂN THỊ THÔNG BÁO XÓA THÀNH CÔNG
                HO SI HUNG 19/12/2020 -->
                <div class="message">
                    <div class="row">
                        <?= $message ?>
                    </div>
                </div>

                <!-- HIỂN THỊ DANH MỤC SẢN PHẨM ĐÃ CHỌN -->
                <?php
                $noNumber = 0;
                $total = 0;
                if (isset($_COOKIE["cart"]) && count($shoppingList) > 0) {
                ?>
                    <table class="table table-striped">

                        <tr class="table-success">
                            <td width="5%" style="font-weight: bold; text-align:center;">No</td>
                            <td width="15%" style="font-weight: bold; text-align:center;">Picture</td>
                            <td width="30%" style="font-weight: bold; text-align:center;">Name</td>
                            <td width="5%" style="font-weight: bold; text-align:center;">Price</td>
                            <td width="5%" style="font-weight: bold; text-align:center;">Quantity</td>
                            <td width="10%" style="font-weight: bold; text-align:center;">Total</td>
                            <td width="10%" style="font-weight: bold; text-align:center;">Action</td>
                        </tr>
                        <?php
                        foreach ($shoppingList as $item) {
                            $noNumber++;
                            $total += $item["product_price"] * $item["quantity"];
                        ?>
                            <tr class="table-info">
                                <td width="5%"> <?= $noNumber ?> </td>
                                <td width="15%">
                                    <img src="./public/images/products/<?= $item["product_photo"] ?>" style="width: 100px;" alt="">

                                </td>
                                <td width="30%"><?= $item["product_name"] ?> </td>
                                <td width="5%" style="text-align:center;font-weight: bold;"><?= number_format($item["product_price"])  ?> </td>
                                <td width="5%" style="text-align:center;font-weight: bold;"><?= $item["quantity"] ?> </td>
                                <td width="10%" style="text-align:center;font-weight: bold;"><?= number_format($item["product_price"] * $item["quantity"])  ?></td>

                                <!-- CHỨC NĂNG XÓA SẢN PHẨM RA GIỎ HÀNG -->
                                <!-- 19/12/2020 Ho Si Hung -->
                                <td width="10%" style="font-weight: bold;">
                                    <form action="cart.php" method="get">
                                        <input type="hidden" name="idRemove" value=<?= $item["product_id"] ?>>
                                        <button class="btn btn-warning" type="submit">Remove</button>
                                    </form>
                                </td>
                            </tr>

                        <?php
                        }
                        ?>

                        <!-- HIỂN THỊ TỔNG SỐ TIỀN VÀ CHỨC NĂNG CLEAR CART -->
                        <tr class="table-success">
                            <td width="5%" style="font-weight: bold;"></td>
                            <td width="15%" style="font-weight: bold;"></td>
                            <td width="30%" style="font-weight: bold;"></td>
                            <td width="5%" style="font-weight: bold;"></td>
                            <td width="5%" style="font-weight: bold;"></td>
                            <td width="10%" style="text-align:center;font-weight: bold;"><?= number_format($total) ?></td>

                            <!-- CHỨC NĂNG CLEAR CART
                        HO SI HUNG 19/12/2020 -->
                            <td width="10%" style="font-weight: bold;">
                                <form action="cart.php" method="get">
                                    <input type="hidden" name="removeCart" value="yes" ?>
                                    <button class="btn btn-info" type="submit">Clear Cart</button>
                                </form>
                            </td>
                        </tr>
                    </table>

                    <!-- XÁC NHẬN THANH TOÁN, GỬI DỮ LIỆU ĐƠN HÀNG LÊN CSDL -->
                    <br>
                    <div class="row">
                        <div class="col-md-6 offset-md-3">
                            <div style="text-align: center;">
                                <?php
                                if ($idAccount == -1) {
                                ?>
                                    <button type="submit" class="btn btn-danger">
                                        LOG IN TO CHECK OUT
                                    </button>
                                <?php
                                } else {
                                ?>
                                    <form action="index.php" method="post">
                                        <!-- CHUYỂN DỮ LIỆU CART CỦA TÀI KHOẢN THÀNH CHUỖI -->
                                        <?php
                                        $cartData = array();
                                        if ($idAccount != -1) {
                                            for ($i = 0; $i < count($cookieData[$idAccount]); $i++) {
                                                $cartData[] = implode(",", $cookieData[$idAccount][$i]);
                                            }
                                            $cartData = implode("#", $cartData);
                                        }
                                        ?>
                                        <input type="hidden" name="cartData" value="<?= $cartData ?>">
                                        <button type="submit" class="btn btn-danger">
                                            CHECK OUT
                                        </button>
                                    </form>
                                <?php
                                }
                                ?>
                            </div>
                        </div>

                    </div>

                    <!-- NẾU GIỎ HÀNG RỖNG -->
                <?php
                } elseif (isset($_COOKIE["cart"]) and count($shoppingList) == 0) {
                ?>
                    <div class="emty_cart">
                        <div class="row">
                            <div class="col-md-6 offset-md-3" style="border: 1px solid red;">
                                <img src="./public/images/encourage_shopping.jpg" alt="" class="img-fluid">

                                <div class="alert alert-danger" role="alert" style="text-align: center;">
                                    <strong>Your cart is empty!</strong>
                                </div>
                                <div style="text-align: center;">
                                    <form action="index.php" method="post">
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-shopping-cart"></i> SHOPPING NOW
                                        </button>
                                    </form>
                                </div>
                                <br>
                            </div>
                        </div>
                    </div>

                <?php
                } else {
                ?>
                    <!-- NẾU CHƯA SET COOKIE -->
                    <div class="emty_cart">
                        <div class="row">
                            <div class="col-md-6 offset-md-3" style="border: 1px solid red;">
                                <img src="./public/images/encourage_shopping.jpg" alt="" class="img-fluid">

                                <div class="alert alert-danger" role="alert" style="text-align: center;">
                                    <strong>Your cart is empty!</strong>
                                </div>
                                <div style="text-align: center;">
                                    <form action="index.php" method="post">
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-shopping-cart"></i> SHOPPING NOW
                                        </button>
                                    </form>
                                </div>
                                <br>
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