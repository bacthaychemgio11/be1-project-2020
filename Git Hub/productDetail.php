<!-- CONNECT PHP FILES -->
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

//Lấy ID
$id = $_GET['idProduct'];

/*
    PAGINATON
*/
//Tạo biến $page cho Pagniation
$page = 1;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

//Mặc định item mỗi trang cho Pagination
$itemsPerPage = 8;
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
$categoriesList = $categoriesModel->getCategories();
$selectedItem = $productsModel->getProductByID($id);
$productsList = $productsModel->getProductsByPageForCategory($page, $itemsPerPage, $categoriesModel->getIDCategory_By_IDProduct($selectedItem['product_id']), $sortType);

/*28/11/2020
    Hồ Sĩ Hùng, Lê Tuấn Liêm
    Thiết kế carousel
*/
$popularProducts = $productsModel->get10PopularProducts();


// Đưa sản phẩm vào giỏ hàng
// Hồ Sĩ Hùng
// 17/12/2020
$message = '';

// idAccount = -1 khi người dùng chưa đăng nhập
$idAccount = -1;
if (isset($_SESSION["ID_account"])) {
    $idAccount = $_SESSION["ID_account"];
}

if (isset($_POST["add_to_cart"])) {
    $listCarts = array();

    //Thêm mảng con có key là id account vào dữ liệu lấy từ cookie
    // $listCarts[$idAccount] = array();

    //Nếu cookie đã tạo, lấy dữ liệu trong cookie
    if (isset($_COOKIE["cart"])) {
        $cookieData = stripslashes($_COOKIE["cart"]);
        $listCarts = json_decode($cookieData, true);
    }

    //Khởi tạo mảng để lưu trữ sản phẩm ng dùng đã mua, bao gồm 2 thông tin id và số lượng
    $itemToCart["product_id"] = $selectedItem["product_id"];

    $itemToCart["quantity"] = $_POST["quantity"];

    //Đưa item vào dữ liệu trong 1 mảng phụ có key là id account
    //Nếu sản phẩm đã tồn tại trong mảng, chỉ cập nhập lại số lượng chứ không thêm mới sản phẩm
    //Update ngày 19/12/2020
    //Hồ Sĩ Hùng
    $isItemExistInCart = false;

    for ($i = 0; $i < count($listCarts[$idAccount]); $i++) {
        if ($listCarts[$idAccount][$i]["product_id"] === $itemToCart["product_id"]) {
            $listCarts[$idAccount][$i]["quantity"] += $itemToCart["quantity"];
            $isItemExistInCart = true;
            break;
        }
    }

    if (!$isItemExistInCart) {
        $listCarts[$idAccount][] =  $itemToCart;
    }

    //Mã hóa dữ liệu và cập nhập lại cookie
    $cookie_data = json_encode($listCarts);
    setcookie('cart', $cookie_data, time() + 600);

    //Hiển thị thông báo khi thêm sản phẩm vào giỏ hàng thành công
    $message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>
    </button>
    <div class="row">
        <div class="col-md-9">
            <strong>Item added to your cart!</strong>

        </div>
        <div class="col-md-3">
            <a href="cart.php"><i class="fas fa-shopping-cart"></i> See your cart!</a>
        </div>
    </div>
    </div>';
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

    <!-- BOOTSTRAP 4.5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>
<style>
    /* SELECTED ITEM PROPERTIES */
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
    .content .wrap_picture img:hover {
        border: 1px solid #61c29b;
        box-shadow: 5px 5px 8px #E6E6E6;
    }

    .content .wrap_picture img:hover {
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
        <!-- <div class="banner">

        </div> -->

        <!-- Carousel -->
        <!-- Hồ Sĩ Hùng - Lê Tuấn Liêm
        28/11/2020
        Carousel 10 sản phẩm phổ biến ngẫu nhiên -->
        <div class="container">
            <div class="carousel_wrap" style="padding: 40px 0;">
                <div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">

                    <ol class="carousel-indicators">
                        <li data-target="#carouselExampleCaptions" data-slide-to="0" class="active"></li>
                        <?php
                        for ($i = 1; $i < count($popularProducts); $i++) {
                        ?>
                            <li data-target="#carouselExampleCaptions" data-slide-to="<?= $i ?>"></li>
                        <?php
                        }
                        ?>

                    </ol>
                    <div class="carousel-inner">
                        <div class="row">
                            <div class="col-md-8 offset-md-2">
                                <div class="carousel-item active " data-interval="2000">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <a href="productDetail?idProduct=<?= $popularProducts[0]['product_id'] ?>">
                                                <img src="./public/images/products/<?= $popularProducts[0]['product_photo'] ?>" class="d-block w-100">
                                            </a>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="wrap_HOT" style="text-align: center;">
                                                <span class="badge badge-pill badge-danger" style="font-size: 22px;">HOT</span>
                                            </div>

                                            <h2 style="text-align: center;"><?= $popularProducts[0]['product_name'] ?></h2>
                                            <br>
                                            <div class="wrap_PRICE" style="text-align: center;">
                                                <span style="font-weight: bold; font-size: 30px; "><?= number_format($popularProducts[0]['product_price']) ?> VNĐ</span>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <?php
                                for ($i = 1; $i < count($popularProducts); $i++) {
                                ?>
                                    <div class="carousel-item " data-interval="2000">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <a href="productDetail?idProduct=<?= $popularProducts[$i]['product_id'] ?>">
                                                    <img src="./public/images/products/<?= $popularProducts[$i]['product_photo'] ?>" class="d-block w-100">
                                                </a>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="wrap_HOT" style="text-align: center;">
                                                    <span class="badge badge-pill badge-danger" style="font-size: 22px;">HOT</span>
                                                </div>

                                                <h2 style="text-align: center;"><?= $popularProducts[$i]['product_name'] ?></h2>
                                                <br>
                                                <div class="wrap_PRICE" style="text-align: center;">
                                                    <span style="font-weight: bold; font-size: 30px; "><?= number_format($popularProducts[$i]['product_price']) ?> VNĐ</span>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                                <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>

                                <a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>

                            </div>

                        </div>

                    </div>
                </div>

            </div>
    </header>

    <!-- CONTENT -->

    <div class="content">

        <!-- HIỂN THỊ THÔNG TIN SẢN PHẨM -->
        <!-- Hồ Sĩ Hùng
        27/11/2020 -->
        <div class="wrap_productDetail">
            <div class="container">

                <div class="row">

                    <!-- Hiển thị ảnh -->
                    <div class="col-md-6">
                        <div class="wrap_picture">
                            <img src="./public/images/products/<?= $selectedItem['product_photo'] ?>" style="display: relative;" class="img-fluid img-thumbnail" alt="">

                            <?php
                            if ($selectedItem['product_ispopular'] == 1) {
                                echo '<img src="./public/images/bestSeller_trans.png" 
                                                        style="position:absolute; width: 100px; top:0; left:20px;" alt="best_Seller">';
                            }
                            ?>

                            <br><br><br>

                            <!-- GALLERY PHOTOS -->
                            <div class="gallery">
                                <div class="row">
                                    <?php
                                    $arrPhoto = explode("#", $selectedItem['product_more_photo']);
                                    if (count($arrPhoto) > 0 && $selectedItem['product_more_photo'] != "") {
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
                        </div>

                    </div>
                    <!-- Hiển thị thông tin -->
                    <div class="col-md-6">
                        <div class="wrap_Info" style="border: 4px solid #61c29b; padding: 20px;">
                            <h1 style="padding: 10px 0 10px 0; color: red;"><?= $selectedItem['product_name'] ?> </h1>

                            <p style="padding: 10px 0 10px 0;"><?= $selectedItem['product_description'] ?></p>

                            <h4>Giá : <span style="font-size: 30px; font-weight: bold;">
                                    <?= number_format($selectedItem['product_price'])  ?> VNĐ</span>
                            </h4>

                            <p style="padding: 10px 0 10px 0;">Tình trạng :
                                <?php
                                $canPurchase = ($selectedItem['product_state']);
                                if ($canPurchase === 1) {
                                    echo "Còn hàng.";
                                } else {
                                    echo "Hết hàng.";
                                }
                                ?>
                            </p>

                            <!-- FORM ĐỂ GỬI SỐ LƯỢNG MUỐN MUA CHO SHOPPING CART -->
                            <?php
                            if ($canPurchase === 1) {
                            ?>
                                <form class="form-inline" action="productDetail?idProduct=<?= $id ?>" method="post">
                                    <div class="form-group">
                                        <label for="quantity">Số lượng :</label>
                                        <input type="number" id="quantity" name="quantity" class="form-control mx-sm-3" value="1" min="1">
                                    </div>

                                    <button type="submit" class="btn btn-outline-success" name="add_to_cart">ADD TO CART</button>
                                </form>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <!-- 19/12/2020 -->
                <!-- MESSAGE BOX WHEN ADDED ITEM SUCCESSFULLY -->
                <br>
                <div class="wrap_message">
                    <?= $message ?>
                </div>
            </div>
        </div>

        <!-- CONTENT 1 -->
        <div class="content1">
            <div class="container">

                <!-- PRODUCT -->
                <!-- Menu Hiển thị danh sách sản phẩm
        Thực hiện:
        Hồ Sĩ Hùng 26/11/2020 -->
                <div class="product">
                    <!-- HIỂN THỊ TÊN DANH MỤC SẢN PHẨM -->
                    <div class="category_Name" style="text-align: center; background-color :#61c29b; ">
                        <h2 style="text-transform: uppercase; color: white; padding: 10px 0;"><?= $categoriesModel->getCategoryName_By_IDCategory($categoriesModel->getIDCategory_By_IDProduct($productsList[0]['product_id'])) ?></h2>
                    </div>

                    <!-- CHỌN TIÊU CHÍ ĐÁNH GIÁ, CHỌN SỐ SẢN PHẨM HIỂN THỊ MỖI TRANG -->
                    <!-- Hồ Sĩ Hùng
                02/12/2020 -->
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
                                        <a class="dropdown-item" href="productDetail?idProduct=<?= $id ?>>">
                                            <form action="productDetail?idProduct=<?= $id ?>" method="get">
                                                <input type="hidden" name="itemsPerPage" value="8">
                                                <input type="hidden" name="sortType" value=<?= $sortType ?>>
                                                <input type="hidden" name="idProduct" value=<?= $id ?>>
                                                <button class="btn btn-success btn-block" type="submit">8</button>
                                            </form>
                                        </a>
                                        <a class="dropdown-item" href="productDetail?idProduct=<?= $id ?>">
                                            <form action="productDetail?idProduct=<?= $id ?>" method="get">
                                                <input type="hidden" name="itemsPerPage" value="12">
                                                <input type="hidden" name="sortType" value=<?= $sortType ?>>
                                                <input type="hidden" name="idProduct" value=<?= $id ?>>
                                                <button class="btn btn-success btn-block" type="submit">12</button>
                                            </form>
                                        </a>
                                        <a class="dropdown-item" href="productDetail?idProduct=<?= $id ?>">
                                            <form action="productDetail?idProduct=<?= $id ?>" method="get">
                                                <input type="hidden" name="itemsPerPage" value="16">
                                                <input type="hidden" name="sortType" value=<?= $sortType ?>>
                                                <input type="hidden" name="idProduct" value=<?= $id ?>>
                                                <button class="btn btn-success btn-block" type="submit">16</button>
                                            </form>
                                        </a>
                                        <a class="dropdown-item" href="productDetail?idProduct=<?= $id ?>">
                                            <form action="productDetail?idProduct=<?= $id ?>" method="get">
                                                <input type="hidden" name="itemsPerPage" value="20">
                                                <input type="hidden" name="sortType" value=<?= $sortType ?>>
                                                <input type="hidden" name="idProduct" value=<?= $id ?>>
                                                <button class="btn btn-success btn-block" type="submit">20</button>
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
                                        <a class="dropdown-item" href="productDetail?idProduct=<?= $id ?>">
                                            <form action="productDetail?idProduct=<?= $id ?>" method="get">
                                                <input type="hidden" name="sortType" value="default">
                                                <input type="hidden" name="itemsPerPage" value=<?= $itemsPerPage ?>>
                                                <input type="hidden" name="idProduct" value=<?= $id ?>>
                                                <button class="btn btn-success btn-block" type="submit">Default</button>
                                            </form>
                                        </a>
                                        <a class="dropdown-item" href="productDetail?idProduct=<?= $id ?>">
                                            <form action="productDetail?idProduct=<?= $id ?>" method="get">
                                                <input type="hidden" name="sortType" value="customerReview">
                                                <input type="hidden" name="itemsPerPage" value=<?= $itemsPerPage ?>>
                                                <input type="hidden" name="idProduct" value=<?= $id ?>>
                                                <button class="btn btn-success btn-block" type="submit">Customer review</button>
                                            </form>
                                        </a>

                                        <a class="dropdown-item" href="productDetail?idProduct=<?= $id ?>">
                                            <form action="productDetail?idProduct=<?= $id ?>" method="get">
                                                <input type="hidden" name="sortType" value="price_asc">
                                                <input type="hidden" name="itemsPerPage" value=<?= $itemsPerPage ?>>
                                                <input type="hidden" name="idProduct" value=<?= $id ?>>
                                                <button class="btn btn-success btn-block" type="submit">Price : Low to High</button>
                                            </form>
                                        </a>
                                        <a class="dropdown-item" href="productDetail?idProduct=<?= $id ?>">
                                            <form action="productDetail?idProduct=<?= $id ?>" method="get">
                                                <input type="hidden" name="sortType" value="price_des">
                                                <input type="hidden" name="itemsPerPage" value=<?= $itemsPerPage ?>>
                                                <input type="hidden" name="idProduct" value=<?= $id ?>>
                                                <button class="btn btn-success btn-block" type="submit">Price : High to Low</button>
                                            </form>
                                        </a>
                                        <a class="dropdown-item" href="productDetail?idProduct=<?= $id ?>">
                                            <form action="productDetail?idProduct=<?= $id ?>" method="get">
                                                <input type="hidden" name="sortType" value="name_asc">
                                                <input type="hidden" name="itemsPerPage" value=<?= $itemsPerPage ?>>
                                                <input type="hidden" name="idProduct" value=<?= $id ?>>
                                                <button class="btn btn-success btn-block" type="submit">Name : A to Z</button>
                                            </form>
                                        </a>
                                        <a class="dropdown-item" href="productDetail?idProduct=<?= $id ?>">
                                            <form action="productDetail?idProduct=<?= $id ?>" method="get">
                                                <input type="hidden" name="sortType" value="name_des">
                                                <input type="hidden" name="itemsPerPage" value=<?= $itemsPerPage ?>>
                                                <input type="hidden" name="idProduct" value=<?= $id ?>>
                                                <button class="btn btn-success btn-block" type="submit">Name : Z to A</button>
                                            </form>
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <?php
                        foreach ($productsList as $item) {
                        ?>
                            <div class="col-md-3 my-3">
                                <div class="item">
                                    <div class="wrappicture">
                                        <div class="picture">
                                            <a href="productDetail?idProduct=<?= $item['product_id'] ?>">
                                                <img src="./public/images/products/<?= $item['product_photo'] ?>" style="position: relative" class="img-fluid">

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

        <!-- PAGINATION
                HỒ SĨ HÙNG - LÊ TUẤN LIÊM
                29/11/2020 -->
        <div class="wrap_pagination">
            <?php

            $pagination = new pagination();
            $pagination->createPagesInDetailPages($productsModel->countAllProductsHaveSameCateGory($categoriesModel->getIDCategory_By_IDProduct($selectedItem['product_id'])), $itemsPerPage, $page, $id, $sortType);
            ?>
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
        const items = document.querySelector(".wrap_picture");
        const galleries = document.querySelector(".gallery");


        let displayPhoto = items.querySelector("img");
        let morePhotos = galleries.querySelectorAll("img");

        for (let i = 0; i < morePhotos.length; i++) {
            morePhotos[i].style.opacity = "0.5";
            if (displayPhoto.getAttribute("src") == morePhotos[i].getAttribute("src")) {
                morePhotos[i].style.opacity = "1";
            }

            morePhotos[i].addEventListener("click", function() {
                displayPhoto.setAttribute("src", morePhotos[i].getAttribute("src"));
                for (let j = 0; j < morePhotos.length; j++) {
                    morePhotos[j].style.opacity = "0.5";
                    if (displayPhoto.getAttribute("src") == morePhotos[j].getAttribute("src")) {
                        morePhotos[j].style.opacity = "1";
                    }
                }
            });
        }
    </script>

</body>

</html>