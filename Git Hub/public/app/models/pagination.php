<?php
class pagination
{
    //CREATE PAGES FOR HOMEPAGE
    public function createPagesInHomePage($totalRows, $itemsPerPage, $currentPage, $sortType)
    {
        $previous = $currentPage;
        $next = $currentPage;
        $disablePrevious = '';
        $disableNext = '';

        if ($previous > 1) {
            $previous--;
            $disablePrevious = '';
        } else {
            $disablePrevious = "disabled";
        }

        //Find number of pages
        $pages = ceil($totalRows / $itemsPerPage);

        if ($next < $pages) {
            $next++;
            $disableNext = '';
        } else {
            $disableNext = "disabled";
        }
        //Create Pagination using bootstrap
?>

        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= $disablePrevious ?>">
                    <a class="page-link" href="index.php?page=<?= $previous ?>&sortType=<?= $sortType ?>&itemsPerPage=<?= $itemsPerPage ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>

                <?php
                for ($i = 1; $i <=  $pages; $i++) {
                ?>

                    <li class="page-item 
                    <?=
                        ($i == $currentPage) ? "active" : "";
                    ?>
                    "><a class="page-link" href="index.php?page=<?= $i ?>&sortType=<?= $sortType ?>&itemsPerPage=<?= $itemsPerPage ?>"><?= $i ?></a></li>

                <?php
                }
                ?>

                <li class="page-item <?= $disableNext ?>">
                    <a class="page-link" href="index.php?page=<?= $next ?>&sortType=<?= $sortType ?>&itemsPerPage=<?= $itemsPerPage ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    <?php
    }

    //CREATE PAGES FOR PRODUCT DETAIL PAGES
    public function createPagesInDetailPages($totalRows, $itemsPerPage, $currentPage, $idPass, $sortType)
    {
        $previous = $currentPage;
        $next = $currentPage;
        $disablePrevious = '';
        $disableNext = '';

        if ($previous > 1) {
            $previous--;
            $disablePrevious = '';
        } else {
            $disablePrevious = "disabled";
        }

        //Find number of pages
        $pages = ceil($totalRows / $itemsPerPage);

        if ($next < $pages) {
            $next++;
            $disableNext = '';
        } else {
            $disableNext = "disabled";
        }
        //Create Pagination using bootstrap
    ?>

        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= $disablePrevious ?>">
                    <a class="page-link" href="productDetail.php?page=<?= $previous ?>&idProduct=<?= $idPass ?>&sortType=<?= $sortType ?>&itemsPerPage=<?= $itemsPerPage ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>

                <?php
                for ($i = 1; $i <=  $pages; $i++) {
                ?>

                    <li class="page-item 
                    <?=
                        ($i == $currentPage) ? "active" : "";
                    ?>
                    "><a class="page-link" href="productDetail.php?page=<?= $i ?>&idProduct=<?= $idPass ?>&sortType=<?= $sortType ?>&itemsPerPage=<?= $itemsPerPage ?>"><?= $i ?></a></li>

                <?php
                }
                ?>

                <li class="page-item <?= $disableNext ?>">
                    <a class="page-link" href="productDetail.php?page=<?= $next ?>&idProduct=<?= $idPass ?>&sortType=<?= $sortType ?>&itemsPerPage=<?= $itemsPerPage ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    <?php
    }

    //CREATE PAGES FOR PRODUCT CATEGORY PAGES
    public function createPagesInCategoryPages($totalRows, $itemsPerPage, $currentPage, $idPass, $sortType)
    {
        $previous = $currentPage;
        $next = $currentPage;
        $disablePrevious = '';
        $disableNext = '';

        if ($previous > 1) {
            $previous--;
            $disablePrevious = '';
        } else {
            $disablePrevious = "disabled";
        }

        //Find number of pages
        $pages = ceil($totalRows / $itemsPerPage);

        if ($next < $pages) {
            $next++;
            $disableNext = '';
        } else {
            $disableNext = "disabled";
        }
        //Create Pagination using bootstrap
    ?>

        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= $disablePrevious ?>">
                    <a class="page-link" href="productCategory.php?page=<?= $previous ?>&idCategory=<?= $idPass ?>&sortType=<?= $sortType ?>&itemsPerPage=<?= $itemsPerPage ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>

                <?php
                for ($i = 1; $i <=  $pages; $i++) {
                ?>

                    <li class="page-item 
                    <?=
                        ($i == $currentPage) ? "active" : "";
                    ?>
                    "><a class="page-link" href="productCategory.php?page=<?= $i ?>&idCategory=<?= $idPass ?>&sortType=<?= $sortType ?>&itemsPerPage=<?= $itemsPerPage ?>"><?= $i ?></a></li>

                <?php
                }
                ?>

                <li class="page-item <?= $disableNext ?>">
                    <a class="page-link" href="productCategory.php?page=<?= $next ?>&idCategory=<?= $idPass ?>&sortType=<?= $sortType ?>&itemsPerPage=<?= $itemsPerPage ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    <?php
    }

    //CREATE PAGES FOR SEARCH PAGES
    public function createPagesInSearchPages($totalRows, $itemsPerPage, $currentPage, $keyword, $sortType)
    {
        $previous = $currentPage;
        $next = $currentPage;
        $disablePrevious = '';
        $disableNext = '';

        if ($previous > 1) {
            $previous--;
            $disablePrevious = '';
        } else {
            $disablePrevious = "disabled";
        }

        //Find number of pages
        $pages = ceil($totalRows / $itemsPerPage);

        if ($next < $pages) {
            $next++;
            $disableNext = '';
        } else {
            $disableNext = "disabled";
        }
        //Create Pagination using bootstrap
    ?>

        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= $disablePrevious ?>">
                    <a class="page-link" href="?page=<?= $previous ?>&q=<?= $keyword ?>&sortType=<?= $sortType ?>&itemsPerPage=<?= $itemsPerPage ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>

                <?php
                for ($i = 1; $i <=  $pages; $i++) {
                ?>

                    <li class="page-item 
                    <?=
                        ($i == $currentPage) ? "active" : "";
                    ?>
                    "><a class="page-link" href="?page=<?= $i ?>&q=<?= $keyword ?>&sortType=<?= $sortType ?>&itemsPerPage=<?= $itemsPerPage ?>"><?= $i ?></a></li>

                <?php
                }
                ?>

                <li class="page-item <?= $disableNext ?>">
                    <a class="page-link" href="?page=<?= $next ?>&q=<?= $keyword ?>&sortType=<?= $sortType ?>&itemsPerPage=<?= $itemsPerPage ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    <?php
    }

    //CREATE PAGES FOR PRODUCT CATEGORY PAGES (ADMIN)
    public function createPagesInCategoryPages_Admin($totalRows, $itemsPerPage, $currentPage, $idPass, $sortType)
    {
        $previous = $currentPage;
        $next = $currentPage;
        $disablePrevious = '';
        $disableNext = '';

        if ($previous > 1) {
            $previous--;
            $disablePrevious = '';
        } else {
            $disablePrevious = "disabled";
        }

        //Find number of pages
        $pages = ceil($totalRows / $itemsPerPage);

        if ($next < $pages) {
            $next++;
            $disableNext = '';
        } else {
            $disableNext = "disabled";
        }
        //Create Pagination using bootstrap
    ?>

        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= $disablePrevious ?>">
                    <a class="page-link" href="manageCategoryDetail.php?page=<?= $previous ?>&idCategory=<?= $idPass ?>&sortType=<?= $sortType ?>&itemsPerPage=<?= $itemsPerPage ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>

                <?php
                for ($i = 1; $i <=  $pages; $i++) {
                ?>

                    <li class="page-item 
                    <?=
                        ($i == $currentPage) ? "active" : "";
                    ?>
                    "><a class="page-link" href="manageCategoryDetail.php?page=<?= $i ?>&idCategory=<?= $idPass ?>&sortType=<?= $sortType ?>&itemsPerPage=<?= $itemsPerPage ?>"><?= $i ?></a></li>

                <?php
                }
                ?>

                <li class="page-item <?= $disableNext ?>">
                    <a class="page-link" href="manageCategoryDetail.php?page=<?= $next ?>&idCategory=<?= $idPass ?>&sortType=<?= $sortType ?>&itemsPerPage=<?= $itemsPerPage ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    <?php
    }


    //CREATE PAGES FOR Manage Product PAGES
    public function createPagesInManageProductsPage($totalRows, $itemsPerPage, $currentPage, $sortType)
    {
        $previous = $currentPage;
        $next = $currentPage;
        $disablePrevious = '';
        $disableNext = '';

        if ($previous > 1) {
            $previous--;
            $disablePrevious = '';
        } else {
            $disablePrevious = "disabled";
        }

        //Find number of pages
        $pages = ceil($totalRows / $itemsPerPage);

        if ($next < $pages) {
            $next++;
            $disableNext = '';
        } else {
            $disableNext = "disabled";
        }
        //Create Pagination using bootstrap
    ?>
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= $disablePrevious ?>">
                    <a class="page-link" href="?page=<?= $previous ?>&sortType=<?= $sortType ?>&itemsPerPage=<?= $itemsPerPage ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>

                <?php
                for ($i = 1; $i <=  $pages; $i++) {
                ?>

                    <li class="page-item 
                    <?=
                        ($i == $currentPage) ? "active" : "";
                    ?>
                    "><a class="page-link" href="?page=<?= $i ?>&sortType=<?= $sortType ?>&itemsPerPage=<?= $itemsPerPage ?>"><?= $i ?></a></li>

                <?php
                }
                ?>

                <li class="page-item <?= $disableNext ?>">
                    <a class="page-link" href="?page=<?= $next ?>&sortType=<?= $sortType ?>&itemsPerPage=<?= $itemsPerPage ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
<?php
    }
}
?>