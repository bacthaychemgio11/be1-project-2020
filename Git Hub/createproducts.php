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

//Tạo biến Model
$productsModel = new productsModel();
$categoriesModel = new categoriesModel();

//Lấy dữ liệu
$categoriesList = $categoriesModel->getCategories();
$productList = $productsModel->getProducts();

//XỬ LÝ THÊM ẢNH SẢN PHẨM
//HỒ SĨ HÙNG
//24/12/2020
$photoname = "";
$photoString = "";
if (isset($_FILES["product_photo"]) && isset($_SESSION["admin"])) {
    for ($i = 0; $i < count($_FILES["product_photo"]["tmp_name"]); $i++) {
        if (is_uploaded_file($_FILES["product_photo"]["tmp_name"][$i])) {
            $photoname = time() . $_FILES["product_photo"]["name"][$i];

            $path = "./public/images/products/" . $photoname;
            move_uploaded_file($_FILES["product_photo"]["tmp_name"][$i], $path);

            //Xử lý chuổi String tất cả các ảnh
            $photoString .= $photoname;
            if ($i < count($_FILES["product_photo"]["tmp_name"]) - 1) {
                $photoString .= "#";
            }
        }
    }
}

//XỬ LÝ THÊM SẢN PHẨM
// HỒ SĨ HÙNG
// 23/12/2020
$message = "";
if (
    isset($_POST["product_name"]) && isset($_POST["product_description"])
    && isset($_POST["product_description"])
    && isset($_POST["product_price"])
    && isset($_POST["product_quality"])
    && isset($_POST["product_state"])
    && isset($_POST["product_ispopular"])
    && isset($_FILES["product_photo"])
    && isset($_POST["product_category"])
    && isset($_SESSION["admin"])
) {
    $productsModel->createProducts(
        $_POST["product_name"],
        $_POST["product_description"],
        $_POST["product_price"],
        $_POST["product_quality"],
        $_POST["product_state"],
        $_POST["product_ispopular"],
        $photoString,
        $_POST["product_category"]
    );

    //Cập nhập lại productList
    $productList = $productsModel->getProducts();

    // Thông báo thêm sản phẩm thành công!
    $message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>
    </button>
    <div class="row">
        <div class="col-md-6">
            <strong>Your new product was created!</strong>
        </div>
        <div class="col-md-6" style="text-align: right;">
            <a href="manageProductDetail?idProduct=' . $productList[count($productList) - 1]["product_id"] . '">
                Click here to see your new product!
            </a>
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

</head>
<style>
    /* PRODUCTS PROPERTIES */
    .product .item .wrappicture {
        height: 280px;
    }

    .frame_form {
        background-image: linear-gradient(to bottom right, #2980B9, #6DD5FA);
        border-radius: 15px;
    }

    .frame_form label {
        color: white;
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

    <!-- CONTENT -->

    <div class="content">
        <!-- CONTENT 1 -->
        <div class="content1">
            <div class="container">
                <h1 style="font-family: Arial, Helvetica, sans-serif; color:red;">CREATE PRODUCTS</h1>

                <!-- HIỂN THỊ THÔNG BÁO ADD SẢN PHẨM THÀNH CÔNG -->
                <div class="message" style="padding: 10px;">
                    <?= $message ?>
                </div>

                <!-- HIỂN THỊ FORM ĐIỀN THÔNG TIN SẢN PHẨM -->
                <!-- Hồ Sĩ Hùng
                23/12/2020 -->

                <div class="frame_form">
                    <div class="row">
                        <!-- Images -->
                        <div class="col-md-6">
                            <div style="padding: 50px 10px 50px 30px;">
                                <img src="./public/images/frame_create_product_form.jpg" class="img-fluid img-thumbnail" alt="">
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div style="padding: 50px;">
                                <div style="padding: 50px; box-shadow: 1px 1px 10px 4px #ffff1c">
                                    <form action="createproducts.php" method="post" enctype="multipart/form-data">
                                        <!-- NAME -->
                                        <div class="form-group">
                                            <label for="name">Product name:</label>
                                            <input type="text" class="form-control" name="product_name" id="name" placeholder="Product name">
                                        </div>

                                        <!-- DESCRIPTION -->
                                        <div class="form-group">
                                            <label for="description">Product description:</label>
                                            <textarea class="form-control" name="product_description" id="description" rows="4" placeholder="Product description"></textarea>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <!-- PRICE -->
                                                <div class="form-group">
                                                    <label for="price">Product price:</label>
                                                    <input type="number" class="form-control" name="product_price" id="price" placeholder="Product price" min="0" value="1000">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <!-- QUALITY -->
                                                <div class="form-group">
                                                    <label for="quality">Product quality:</label>
                                                    <input type="number" class="form-control" name="product_quality" id="quality" min="0" max="5" value="5">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <!-- STATE -->
                                                <div class="form-group">
                                                    <label for="state">State</label>
                                                    <select class="form-control" name="product_state" id="state">
                                                        <option value="1">In Stock</option>
                                                        <option value="0">Out of Stock</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <!-- IS POPULAR -->
                                                <div class="form-group">
                                                    <label for="state">Is popular</label>
                                                    <select class="form-control" name="product_ispopular" id="state">
                                                        <option value="1">Popular</option>
                                                        <option value="0">Common</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- PHOTO -->
                                        <div class="form-group">
                                            <label for="photo">Product photo:</label>
                                            <input type="file" multiple="multiple" class="form-control-file" name="product_photo[]" id="photo">
                                        </div>

                                        <!-- CATEGORY -->
                                        <div class="form-group">
                                            <label for="category">Category:</label>
                                            <select class="form-control" name="product_category" id="category">
                                                <!-- Hiển thị danh mục category -->
                                                <?php
                                                foreach ($categoriesList as $itemCategory) {
                                                ?>
                                                    <option value="<?= $itemCategory["category_id"] ?>">
                                                        <?= $itemCategory["category_name"] ?>
                                                    </option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div><br>

                                        <!-- SUBMIT -->
                                        <div style="text-align: center;">
                                            <button type="submit" class="btn btn-success" style="width: 150px; 
                                            box-shadow: 0px 0px 4px 1px #ffff1c">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <br>

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