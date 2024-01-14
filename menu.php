<?php

    session_start();
   

    require_once("./php/database.php");
    require_once("./php/Elements.php");
    $database = new mydatabase('items');

    $username = "" ;
    $remv = "dropdown-toggle d-none";
    $setdnone = "";
    $link = "./index.php" ;
    $display = "d-none" ;
    $alert = false ;
    if(isset($_SESSION['users']))
    {
        $userId = $_SESSION['users'] ;
        $alert = true ;
        $sql = "SELECT * FROM erestaurant.users WHERE id= '$userId' " ;
        $result = $database->getData_sql($sql) ;

        while($row = mysqli_fetch_assoc($result))
        {
            $username = $row['First_Name'] ;
            $isadmin = $row['is_admin'] ;
        }
        if($isadmin == 1)
        {
            $link = "./admin.php" ;
            $text = "ADMIN PAGE" ;
            $display = "" ;
        }
        $remv = "dropdown-toggle";
        $setdnone = "d-none";
    }

    if(isset($_POST['cardItem']))
    {
        $itemID = $_POST['item_id'] ;
        
        $_SESSION['clickedItem'] = $itemID ;

        header("location: item.php") ;
        //echo "<script> alert (' $cartItem') </script>" ;
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Menu</title>
    <link rel="icon" type="image/x-icon" href="images/MainLogo.png" />
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link rel="stylesheet" href="css/style.css">

</head>


<body id="page-top">


    <!-- header -->
    <header>
        <!--1st top nav-->
        <div class="nav ">
            <div class="container-fluid  " id="Topnavbar">

                <div class="row pt-2 justify-content-center ">
                    <div class="col-md-5 p-0 pb-3 " id="WebLogo">
                        <?php echo "<a href=\" $link\">" ; ?>
                            <img src="./images/MainLogo.png" alt="" class="LogoTop">
                        </a>
                        <h4 class="font-weight-bold text-dark LogoHeading pl-2">
                            <span class="text-primary">E</span>-Resturant
                        </h4>

                    </div>
                    <div class="col-md-5  pt-3 ">
                        <div class="row text-right">
                            <div class="col">
                                <form class="example" action="">
                                    <input type="text" placeholder="Search.." name="search" class="mysearch">
                                    <button type="submit" class="searchBut"><i class="fa fa-search"></i></button>
                                </form>
                            </div>
                        </div>
                        <div class="row pt-3 ">

                            <div class="col-md-12 text-right ">

                                <ul class="navbar nav d-inline-flex ml-auto">

                                    <li class="dropdown no-arrow mr-5 pr-2">
                                        <?php

                                        echo
                                        "
                                                    <a class=\"$remv\" href=\"#\" id=\"userDropdown\" role=\"button\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\" style=\"text-decoration: none;\" id=\"userLoggedin\">
                                                    <img class=\"img-profile rounded-circle\" src=\"images/MainLogo.png\" style=\"position: relative; top: 5px; width: 35px;\">
                                                    <span class=\"mr-1 d-none d-lg-inline text-gray-600 small\">Welcome <strong>$username </strong>

                                                    </span>
                                                    <div style=\"position: absolute; top: 19px; left: 45px;\">
                                                        <span class=\"font-weight-bold mr-2 d-none d-lg-inline small\">MY ACCOUNT</span>
                                                    </div>
                                                    </a>


                                                    <a class=\"$setdnone\" href=\"./login.php\" role=\"button\" style=\"text-decoration: none;\">
                                                        <img class=\"img-profile rounded-circle\" src=\"images/MainLogo.png\" style=\"position: relative; top: 5px; width: 35px;\">
                                                        <span class=\"mr-1 d-none d-lg-inline text-gray-600 small\">Welcome, Sign-in

                                                        </span>
                                                        <div style=\"position: absolute; top: 19px; left: 45px;\">
                                                            <span class=\"font-weight-bold mr-2 d-none d-lg-inline small\">MY ACCOUNT</span>
                                                        </div>

                                                    </a>
                                                ";


                                        ?>


                                        <!-- Dropdown - User Information -->
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in my-3 " aria-labelledby="userDropdown">
                                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#UserProfile">
                                                <i class="fas fa-user fa-sm fa-fw mr-2 text-primary"></i>
                                                Profile
                                            </a>
                                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#UserPPurchase">
                                                <i class="fas fa-history fa-sm fa-fw mr-2 text-primary"></i>
                                                Past Purchase
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-primary"></i>
                                                Logout
                                            </a>
                                        </div>
                                    </li>

                                    <li class="dropdown no-arrow ">
                                        <a class="nav-link dropdown-toggle" href="#" id="cartDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <img src="./images/outline_shopping_bag_black_24dp.png" alt="" style="position: absolute; width: 35px; left: -20px; background-color: rgba(173, 216, 230, 0.726); border-radius: 30px; padding: 5px;">
                                            <!-- Counter - Cart Items -->
                                            <?php
                                            $sql = "SELECT COUNT(item_id) AS NumberOfItems FROM erestaurant.cart";
                                            $result = $database->getData_sql($sql);
                                            if (mysqli_num_rows($result) === 0) 
                                            {
                                                echo "<span class=\"badge badge-danger badge-counter\" style=\"position: relative; left: -14px; top: -5px;\">0</span>";
                                            } 
                                            else if($alert == false) 
                                            {
                                                echo "<span class=\"badge badge-danger badge-counter\" style=\"position: relative; left: -14px; top: -5px;\">0</span>";
                                            }
                                            else 
                                            {
                                                $row = mysqli_fetch_assoc($result);
                                                echo "<span class=\"badge badge-danger badge-counter\" style=\"position: relative; left: -14px; top: -5px;\">" .
                                                    $row['NumberOfItems'] . "</span>";
                                            }

                                            ?>


                                        </a>
                                        <!-- Dropdown - Cart -->
                                        <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in my-3" aria-labelledby="cartDropdown" id="TopCart">
                                            <h6 class="dropdown-header bg-primary text-center">
                                                Shopping Cart
                                            </h6>
                                            <?php
                                            $emptyCart = "d-none";
                                            $cartisEmpty = "";
                                            $sql = "SELECT * FROM cart";
                                            $getcartid = $database->getData_sql($sql);

                                            $Subtotal = 0.0;
                                            $TotalPriceEach = 0.0 ;
                                            if (mysqli_num_rows($getcartid) === 0) 
                                            {
                                                $emptyCart = "";
                                                $cartisEmpty = "d-none";
                                            }
                                            else if($alert == false) 
                                            {
                                                $emptyCart = "";
                                                $cartisEmpty = "d-none";
                                            }
                                            else 
                                            {

                                                while ($row = mysqli_fetch_assoc($getcartid)) {
                                                    $cartId = $row['item_id'];
                                                    $cartqty = $row['quantity'];

                                                    $sqll = "SELECT * FROM items WHERE id = '$cartId' ";
                                                    $getitemid = $database->getData_sql($sqll);

                                                    while ($rows = mysqli_fetch_assoc($getitemid)) {
                                                        $img = $rows['Meal_Img'];
                                                        $name = $rows['name'];
                                                        $type = $rows['category'];
                                                        $price = $rows['price'];

                                                       
                                                        $TotalPriceEach = $price * $cartqty ; 
                                                        $Subtotal += $TotalPriceEach;
                                                        echo " <div class=\"dropdown-item d-flex align-items-center p-2\" href=\"#\">
                                                                    <div class=\"mr-3 \">
                                                                        <div class=\"circle\">
                                                                            <img src=\"images/$img\" style=\"width: 100%; max-width: 80px;\" alt=\"\">
                                                                        </div>
                                                                    </div>
                                                                    <div class=\"ml-3\">
                                                                        <span class=\"font-weight-bold\">$name</span>
                                                                        <div class=\"text-gray-900\">
                                                                           <span class='text-primary'>$type</span>
                                                                           <span>Qty:$cartqty</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class=\"text-right w-100\">
                                                                        <span class=\"font-weight-bold\">$ $TotalPriceEach</span>
                                                                    </div>
                                                                    <form action=\"\" method=\"POST\" novalidate onkeydown=\"return event.key != 'Enter';\">
                                                                        <button type=\"submit\" style=\"background:none; border:none; color:red; padding-left:20px;\" name=\"deleteitem\" >
                                                                            <i class=\"fas fa-times-circle fa-lg\"></i>
                                                                        </button>

                                                                        <input type=\"hidden\" name=\"itemidDel\" value=\"$cartId\">
                                                                    </form>
                                                                    
                                                                   
                                                                </div>
                                                                
                                                            "
                                                                    ;
                                                    }
                                                }
                                            }

                                            echo
                                            "
                                                        <hr>

                                                        <div class=\"text-center $emptyCart\">
                                                            <h6 class=\" text-dark text-center\">Nothing in the cart</h6>
                                                            <a class=\"text-dark  font-weight-bold\" href=\"./menu.php\">
                                                                <button class=\"btn btn-primary\">
                                                                    Start ordering
                                                                </button>
                                                            </a>
                                                        </div>
    
                                                        <div class=\"$cartisEmpty\">
                                                            <div class=\"row text-gray-800 pb-4\">
                                                                <div class=\"col-md-5 text-center\">
                                                                    <span class=\"text-dark\"> Sub total:</span>
                                                                </div>
                                                                <span class=\"col-md-5 text-right text-dark font-weight-bold\">$ $Subtotal</span>
                                                            </div>
                                                            <div class=\"ml-5 mr-5\">
                                                                <a class=\"btn btn-primary w-100 p-1\" href=\"./cart.php\">View Cart</a>
    
                                                                <a class=\"btn btn-primary w-100 my-2 p-1\" href=\"./checkout.php\">CheckOut</a>
                                                            </div>
                                                        </div>
                                                    ";


                                            ?>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </div>

        <!-- Navigation-->

        <div class="container-fluid ">

            <div class="row justify-content-center">
                <div class="col-md-11">


                    <nav class="navbar navbar-expand bg-white topbar" id="mainNav">

                        <!-- Sidebar Toggle (Topbar) -->
                        <button id="sidebutton" class="sidebarLink btn btn-link rounded-circle mr-3" onclick="openNav()">
                            <i class="fa fa-bars"></i>
                        </button>

                        <!-- Topbar Nav List -->
                        <div class="navbar" id="navbarLists">
                            <ul class="navbar-nav text-uppercase pl-4" id="MainNavList">
                                <li class="nav-item listsmall"><a class="font-weight-bold text-dark nav-link js-scroll-trigger" href="./index.php" id="registerWord">Home</a></li>
                                <li class="nav-item listsmall"><a class="font-weight-bold text-dark nav-link js-scroll-trigger" href="./about.php" id="registerWord">About</a></li>
                                <li class="nav-item listsmall"><a class="font-weight-bold text-dark nav-link js-scroll-trigger" href="./menu.php" id="registerWord">Menu</a></li>
                                <li class="nav-item listsmall"><a class="font-weight-bold text-dark nav-link js-scroll-trigger" href="./contact.php" id="registerWord">Contact</a></li>
                                </a></li>
                                <?php echo "<li class=\"nav-item listsmall $display\"><a class=\"font-weight-bold text-dark nav-link js-scroll-trigger\" href=\"./admin.php\" id=\"registerWord\">Admin</a></li>
                                </a></li>" ;?>
                            </ul>
                        </div>

                        <!-- Sidebar Nav List -->
                        <div id="mySidepanel" class="sidepanel">
                            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()"></a>
                            <a href="./index.php">Home</a>
                            <a href="./about.php">About</a>
                            <a href="./menu.php">Menu</a>
                            <a href="./contact.php">Contact</a>
                        </div>
                        <!-- Topbar Navbar -->
                        <ul class="navbar-nav ml-auto " id="NavbarRightElement">

                            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                            <li class="nav-item dropdown no-arrow d-block">
                                <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-search fa-fw"></i>
                                </a>
                                <!-- Dropdown - Messages -->
                                <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown" id="myinputSearch">
                                    <form class="form-inline mr-auto w-100 navbar-search">
                                        <div class="input-group">
                                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button">
                                                    <i class="fas fa-search fa-sm"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </li>

                            <!-- Nav Item - User Information -->
                            <li class="nav-item dropdown no-arrow" id="UserDropDown">
                                <a class="nav-link dropdown-toggle text-dark" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-user"></i>
                                </a>
                                <!-- Dropdown - User Information -->
                                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#UserProfile">
                                        <i class="fas fa-user fa-sm fa-fw mr-2 text-primary"></i>
                                        Profile
                                    </a>
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#UserPPurchase">
                                        <i class="fas fa-history fa-sm fa-fw mr-2 text-primary"></i>
                                        Past Purchase
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-primary"></i>
                                        Logout
                                    </a>
                                </div>
                            </li>


                            <!-- Nav Item - Cart -->

                            <li class="nav-item dropdown no-arrow mx-1 ">
                                <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <img src="./images/outline_shopping_bag_black_24dp.png" alt="" class="CartIcon" style="width: 30px;">
                                    <!-- Counter - Cart Items -->
                                    <?php
                                    $sql = "SELECT COUNT(item_id) AS NumberOfItems FROM erestaurant.cart";
                                    $result = $database->getData_sql($sql);
                                    if (mysqli_num_rows($result) === 0) {
                                        echo "<span class=\"badge badge-danger badge-counter\" style=\"position: relative; left: -14px; top: -5px;\">0</span>";
                                    } else {
                                        $row = mysqli_fetch_assoc($result);
                                        echo "<span class=\"badge badge-danger badge-counter\" style=\"position: relative; left: -14px; top: -5px;\">" .
                                            $row['NumberOfItems'] . "</span>";
                                    }

                                    ?>


                                </a>
                                <!-- Dropdown - Cart -->
                                <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in my-3" aria-labelledby="cartDropdown" id="TopCart">
                                            <h4 class="dropdown-header bg-primary text-center p-2">
                                                Shopping Cart
                                            </h4>
                                            <?php
                                            $emptyCart = "d-none";
                                            $cartisEmpty = "";
                                            $sql = "SELECT * FROM cart";
                                            $getcartid = $database->getData_sql($sql);

                                            $totalPrice = 0.0;
                                            if (mysqli_num_rows($getcartid) === 0) 
                                            {
                                                $emptyCart = "";
                                                $cartisEmpty = "d-none";
                                            } 
                                            else 
                                            {

                                                while ($row = mysqli_fetch_assoc($getcartid)) {
                                                    $cartId = $row['item_id'];
                                                    $cartqty = $row['quantity'];

                                                    $sqll = "SELECT * FROM items WHERE id = '$cartId' ";
                                                    $getitemid = $database->getData_sql($sqll);

                                                    while ($rows = mysqli_fetch_assoc($getitemid)) {
                                                        $img = $rows['Meal_Img'];
                                                        $name = $rows['name'];
                                                        $type = $rows['category'];
                                                        $price = $rows['price'];

                                                       
                                                        echo " <div class=\"dropdown-item d-flex align-items-center p-2\" href=\"#\">
                                                                    <div class=\"mr-3 \">
                                                                        <div class=\"circle\">
                                                                            <img src=\"images/$img\" style=\"width: 100%; max-width: 80px;\" alt=\"\">
                                                                        </div>
                                                                    </div>
                                                                    <div class=\"ml-1 w-100\">
                                                                        <span class=\"font-weight-bold\">$name</span>
                                                                        <div class=\"text-gray-900\">
                                                                            <span class='text-primary'>$type</span>
                                                                            <span>Qty: $cartqty</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class=\"text-right w-100\">
                                                                        <span class=\"font-weight-bold\">$ $TotalPriceEach</span>
                                                                    </div>
                                                                    <form action=\"\" method=\"POST\" novalidate onkeydown=\"return event.key != 'Enter';\">
                                                                        <button type=\"submit\" style=\"background:none; border:none; color:red; padding-left:20px;\" name=\"deleteitem\" >
                                                                            <i class=\"fas fa-times-circle fa-lg\"></i>
                                                                        </button>

                                                                        <input type=\"hidden\" name=\"itemidDel\" value=\"$cartId\">
                                                                    </form>
                                                                    
                                                                   
                                                                </div>
                                                                
                                                            "
                                                                    ;
                                                    }
                                                }
                                            }

                                            echo
                                            "
                                                        <hr>

                                                        <div class=\"text-center $emptyCart\">
                                                            <h6 class=\" text-dark text-center\">Nothing in the cart</h6>
                                                            <a class=\"text-dark  font-weight-bold\" href=\"./menu.php\">
                                                                <button class=\"btn btn-primary\">
                                                                    Start ordering
                                                                </button>
                                                            </a>
                                                        </div>
    
                                                        <div class=\"$cartisEmpty\">
                                                            <div class=\"row text-gray-800 pb-4\">
                                                                <div class=\"col-md-5 text-center\">
                                                                    <span class=\"text-dark\"> Sub total:</span>
                                                                </div>
                                                                <span class=\"col-md-5 text-right text-dark font-weight-bold\">$ $Subtotal</span>
                                                            </div>
                                                            <div class=\"ml-5 mr-5\">
                                                                <a class=\"btn btn-primary w-100 p-1\" href=\"./cart.php\">View Cart</a>
    
                                                                <a class=\"btn btn-primary w-100 my-2 p-1\" href=\"./checkout.php\">CheckOut</a>
                                                            </div>
                                                        </div>
                                                    ";


                                            ?>
                                        </div>
                            </li>




                        </ul>

                    </nav>
                </div>
            </div>

        </div>
    </header>

    <!-- Menu -->
    <section class="page-section">
        <div class="row ">
            <div class="col ">
                <h4 class="text-center font-weight-bold text-dark">OUR MENU</h4>
            </div>
        </div>
        <div class="row justify-content-center my-4">
            <ul class="nav-item d-flex ">
                <li class="nav-link">
                    <button class="MenuType-btn active" onclick="openContent(event, 'all')"> ALL </button>
                </li>
                <li class="nav-link">
                    <button class="MenuType-btn" onclick="openContent(event, 'breakfast')"> BREAKFAST </button>
                </li>
                <li class="nav-link">
                    <button class="MenuType-btn" onclick="openContent(event, 'lunch')"> LUNCH </button>

                </li>
                <li class="nav-link">
                    <button class="MenuType-btn" onclick="openContent(event, 'dinner')"> DINNER </button>

                </li>
                <li class="nav-link">
                    <button class="MenuType-btn" onclick="openContent(event, 'drinks')"> DRINKS </button>
                    
                </li>
            </ul>
        </div>
        <div class="container my-4 " id="all">

            <div class="row justify-content-center">
                <?php
                $result = $database->getData();

                while ($row = mysqli_fetch_assoc($result)) {
                    elements($row['id'], $row['name'],  $row['category'], $row['price'], $row['Kcal'], $row['Meal_Img'], $row['description']);
                }
                ?>
            </div>
        </div>
        <div class="container my-4 tabContent" id="breakfast">
            <div class="row justify-content-center">
                <?php

                $sql = "SELECT * FROM items WHERE category='Breakfast'";
                $result = $database->getData_sql($sql);

                while ($row = mysqli_fetch_assoc($result)) {
                    elements($row['id'], $row['name'],  $row['category'], $row['price'], $row['Kcal'], $row['Meal_Img'],$row['description']);
                }
                ?>
            </div>
        </div>
        <div class="container my-4 tabContent" id="lunch">
            <div class="row justify-content-center">
                <?php

                $sql = "SELECT * FROM items WHERE category='Lunch'";
                $result = $database->getData_sql($sql);

                while ($row = mysqli_fetch_assoc($result)) {
                    elements($row['id'], $row['name'],  $row['category'], $row['price'], $row['Kcal'], $row['Meal_Img'],$row['description']);
                }
                ?>

            </div>
        </div>
        <div class="container my-4 tabContent" id="dinner">
            <div class="row justify-content-center">
                <?php

                $sql = "SELECT * FROM items WHERE category='Dinner'";
                $result = $database->getData_sql($sql);

                while ($row = mysqli_fetch_assoc($result)) {
                    elements($row['id'], $row['name'],  $row['category'], $row['price'], $row['Kcal'], $row['Meal_Img'],$row['description']);
                }
                ?>

            </div>
        </div>
        <div class="container my-4 tabContent" id="drinks">
            <div class="row justify-content-center">
                <?php

                $sql = "SELECT * FROM items WHERE category='Drinks'";
                $result = $database->getData_sql($sql);

                while ($row = mysqli_fetch_assoc($result)) {
                    elements($row['id'], $row['name'],  $row['category'], $row['price'], $row['Kcal'], $row['Meal_Img'],$row['description']);
                }
                ?>

            </div>
        </div>
    </section>

    <!-- User Profile model-->  
    <div class="modal fade" id="UserProfile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary " style="position: relative; left: -0.5px;">
                    <h5 class="modal-title text-light" id="exampleModalLabel">Profile</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">×</span>
                    </button>
                </div>
                <div class="modal-body">
                <?php
                    echo 
                    "
                        <div class=\"text-center\">
                            <img src=\"./images/MainLogo.png\" alt=\"User Photo\" style=\"width: 70px;\">
                            <h6 class=\"text-dark my-2\">Welcome <strong>$username </strong></h6>
                        </div>
                        
                    " ;
                ?>
                <h5 class="text-dark text-center my-4">To be added...</h5>
                </div>
                <!-- <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="./php/logout.php">Logout</a>
                </div> -->
            </div>
        </div>
    </div>

    <!-- Footer-->
    <footer class="sticky-footer bg-blue">

        <div class="container my-auto">
            <div class="row align-items-center ">
                <div class="col-lg-3  my-auto my-lg-0 text-center ">
                    <span class="text-light">LINKS</span>
                    <div style="display: grid;" class="pt-2">
                        <a href="./index.php" class="footerIcons pt-1">Home</a>
                        <a href="./about.php" class="footerIcons pt-1">About</a>
                        <a href="./contact.php" class="footerIcons pt-1">Contact</a>
                        <a href="./login.php" class="footerIcons pt-1">Login</a>
                    </div>
                </div>
                <div class="col-lg-3  my-auto my-lg-0 text-center ">
                    <span class="text-light">MENU</span>
                    <div style="display: grid;" class="pt-2">
                        <a href="./menu.php" class="footerIcons pt-1">View our menu</a>
                    </div>
                </div>
                <div class="col-lg-3  my-auto my-lg-0 text-center ">
                    <span class="text-light ">CUSTOMER SERVICE</span>
                    <div style="display: grid;" class="pt-2">
                        <a href="./contact.php" class="footerIcons pt-1">Contact US</a>


                    </div>
                </div>
                <div class="col-lg-3  my-auto my-lg-0 text-center ">
                    <span class="text-light ">FOLLOW US ON</span>
                    <div class="pt-2">
                        <a class="btn btn-blue btn-social " href="#!"><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-blue btn-social " href="#!"><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-blue btn-social " href="#!"><i class="fab fa-linkedin-in"></i></a>

                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-12">
                    <hr style="background-color: rgba(255, 255, 255, 0.445);">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 text-lg-left pt-3">
                    <span style="color: rgba(255, 255, 255);">
                        Copyright © E-Resturant 2021 All right reserved
                    </span>
                </div>
                <div class="col-lg-4 my-3 my-lg-0 " align="center">
                    <a href="./index.php">
                        <img src="./images/MainLogo.png" alt="" class="LogoFooter">
                    </a>
                </div>
                <div class="col-lg-4 pt-3 text-lg-right">
                    <a class="mr-3" href="#!" style="color: rgba(255, 255, 255);">Privacy Policy</a>
                    <a href="#!" style="color: rgb(255, 255, 255);">Terms of Use</a>
                </div>
            </div>
        </div>
    </footer>

     <!-- Logout model-->
     <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready leave.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="./php/logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- User Profile model-->
    <div class="modal fade" id="UserProfile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary " style="position: relative; left: -0.5px;">
                    <h5 class="modal-title text-light" id="exampleModalLabel">Profile</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php
                    echo
                    "
                        <div class=\"text-center\">
                            <img src=\"./images/MainLogo.png\" alt=\"User Photo\" style=\"width: 70px;\">
                            <h6 class=\"text-dark my-2\">Welcome <strong>$username </strong></h6>
                        </div>
                        
                    ";
                    ?>
                    <h5 class="text-dark text-center my-4">To be added...</h5>
                </div>
                <!-- <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="./php/logout.php">Logout</a>
                </div> -->
            </div>
        </div>
    </div>

    <!-- User Past-Purchase model-->
    <div class="modal fade " id="UserPPurchase" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary " style="position: relative; left: -0.5px;">
                    <h5 class="modal-title text-light" id="exampleModalLabel">Past Purchases</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6 class="text-dark font-weight-bold">Here You can find your prev brought items</h6>
                    
                    <table class="table table-hover p-0 my-3" id="mytables">
                        <thead class="shadow">
                            <tr>
                                <th scope="col">Item</th>
                                <th scope="col"></th>
                                <th scope="col">Date</th>
                                <th scope="col">Status</th>
                                <th scope="col">Address</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $sql = "SELECT * FROM orders WHERE User_id = $userId" ;
                                $Results = $database->getData_sql($sql) ;

                                while($elem = mysqli_fetch_assoc($Results))
                                {
                                    $itemid = $elem['order_item'] ;
                                    $order_date = $elem['order_date'] ;
                                    $order_status = $elem['Order_Status'] ;
                                    $address = $elem['Order_Address'] ;
                                    $sqll = "SELECT * FROM items WHERE id=$itemid" ;
                                    $Result_item = $database->getData_sql($sqll) ;

                                    while($item_row = mysqli_fetch_assoc($Result_item))
                                    {
                                        $img =  $item_row['Meal_Img'] ;
                                        $name = $item_row['name'] ;
                                        $categoty = $item_row['category'] ;
                                        $price = $item_row['price'] ;
                                            echo "<tr>";
                                                echo "<th scope='row'> <img src=\"./images/$img\" alt=\"\" width=\"40px\"> </th>" ;
                                                echo "<th scope='row' class='text-dark'>  $name <br> <small>$categoty</small> <small class='text-primary'>$$price</small> </th>" ;
                                                echo "<th scope='row'> $order_date </th>" ;
                                                echo "<th scope='row' class='text-success'> $order_status </th>" ;
                                                echo "<th scope='row' class='text-dark'> $address </th>" ;
                                            echo "</tr>";
                                    }
                                   
                                }
                            ?>
                                    
                        </tbody>
                    </table>
                    <?php

                    ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scroll to top -->
    <a class="box js-scroll-trigger" id="topbutton" href="#page-top">

        <span class="animatespan">
            <i class="fas fa-chevron-up fa-lg"></i>
        </span>
        <span class="animatespan">
            <i class="fas fa-chevron-up fa-lg"></i>
        </span>
        <span class="animatespan">
            <i class="fas fa-chevron-up fa-lg"></i>
        </span>

    </a>
    <!-- Bootstrap core JS-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Third party plugin JS-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
    <!-- Contact form JS-->
    <script src="assets/mail/jqBootstrapValidation.js"></script>
    <script src="assets/mail/contact_me.js"></script>
    <!-- Core theme JS-->
    <script src="js/script.js"></script>

    <!--Toggle between tabs-->
    <script>
        function openContent(evt, ContName) {
            var i, tabcontent, tablinks;
            var allEle = document.getElementById('all');
            tabcontent = document.getElementsByClassName("tabContent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
                allEle.style.display = "none";

            }
            tablinks = document.getElementsByClassName("MenuType-btn");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(ContName).style.display = "block";
            evt.currentTarget.className += " active";
        }

        //.............................................
        function openNav() {
            document.getElementById("mySidepanel").style.width = "250px";
        }

        function closeNav() {
            document.getElementById("mySidepanel").style.width = "0";
        }



        function inSize() {
            document.body.style.fontSize = "1.2rem";

        }

        function deSize() {
            document.body.style.fontSize = "1rem";

        }

        var sidecont = document.getElementById('sideControl');

        function ExpandSett() {


            if (sidecont.style.transform === "translateX(0px)") {
                sidecont.style.transform = "translateX(-230px)";
            } else {
                sidecont.style.transform = "translateX(0px)";
            }

        }

        function Menu_open()
        {
            document.getElementById("MenuFirst").style.transition = "translateX(-230px)" ;
        }
    </script>

   

    <script>
        window.onload = function() {
            history.replaceState("", "", "./menu.php");
        }
    </script>
    
</body>



</html>