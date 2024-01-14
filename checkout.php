<?php

    session_start();


    require_once("./php/database.php");
    require_once("./php/Elements.php");
    $database = new mydatabase();

    $username = "";
    $remv = "dropdown-toggle d-none";
    $setdnone = "";
    $link = "./index.php" ;
    $display = "d-none" ;
    $alert = false ;
    $Checksql = "SELECT * FROM cart";
    
    $check = $database->getData_sql($Checksql);
    if (mysqli_num_rows($check) === 0) 
    {
        echo "after" ;
        header("location: ./php/Error403.php") ;
        
    } 
                                        
                                            
                            


    if (isset($_SESSION['users'])) 
    {

        $userId = $_SESSION['users'];
        $alert = true ;
        $sql = "SELECT * FROM erestaurant.users WHERE id= '$userId' ";
        $result_users = $database->getData_sql($sql);

        while ($row = mysqli_fetch_assoc($result_users)) 
        {
            $username = $row['First_Name'];
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

        //.................................................................
    } else 
    {
        $userId = 0;
        header("location: ./php/Error403.php") ;
        exit() ;
    }

    
    if(isset($_POST['PlaceOrder']))
    {
        $orderedOn = date("Y/m/d") ;
        $Statuss = "Paid" ;
        $cont = $_POST['country'] ;
        $city = $_POST['city'] ;
        $address = $cont."_".$city ;
        //............................................
        $cardtype = $_POST['payment'] ;
        $nameoncard = $_POST['cardname'] ;
        $cardnum = $_POST['cardnumber'] ;
        $cvv = $_POST['cvv'] ;
        $ccexp = $_POST['ccexp'] ;
        $cctype = $_POST['payment'] ;

        $hash_card = password_hash($cardnum , PASSWORD_DEFAULT) ;
        $hash_Cvv = password_hash($cvv , PASSWORD_DEFAULT) ;
        
        $sql_payment = "INSERT INTO payment (name,cvv,expDate,type,Number,user_id) VALUES ('$nameoncard','$hash_Cvv','$ccexp','$cctype','$hash_card',$userId)" ;
        $store_pay = $database->Store($sql_payment) ;

        //$Sql_Order = "INSERT INTO orders (order_item, order_date, Address, Status, User_id ) VALUES ()" ;

        $sql_CartItem = "SELECT * FROM cart WHERE user_id = $userId" ;
        $cartItem = $database->getData_sql($sql_CartItem) ;
       
        while($row = mysqli_fetch_assoc($cartItem))
        {
            $item_id = $row['item_id'] ;
            $qty = $row['quantity'] ;

            $Sql_Order = "INSERT INTO orders (order_item, order_date, Order_Address, Order_Status, User_id ) VALUES ($item_id, '$orderedOn', '$address','$Statuss', $userId )" ;

            $Store_Order = $database->Store($Sql_Order) ;

            $DeleteFromCart = "DELETE FROM cart WHERE item_id = $item_id" ;
            $delete = $database->Run_query($DeleteFromCart) ;

            $newqty = "UPDATE items SET Quantity = Quantity - $qty WHERE id = $item_id" ;
            $database->Run_query($newqty) ;
            
        }

        if($Store_Order)
        {
            echo "<script> 
                    alert ('Order places, Thank You'); 
                    window.location.href = './menu.php';
                </script>" ;
        }

            
       
       
        
    }
    
    if(isset($_POST['deleteitem']))
    {
        $delItemid = $_POST['itemidDel'] ;
        $delsql = "DELETE FROM erestaurant.cart WHERE item_id = $delItemid" ;

        $database->getData_sql($delsql) ;
    }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>CheckOut</title>
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

    <!-- Body Content -->
    <section class="page-section pt-5 my-3">
        <div class="container">
            <div class="pb-4">
                <h2 class="text-dark font-weight-bold">Checkout </h2>
            </div>
            <div class="row">
                <!--  Cart items -->
                <div class="col-md-4 order-md-2 mb-4">
                    <h5 class="d-flex justify-content-between align-items-center mb-3 step p-2 bg-primary">
                        <span class="text-light">Your cart</span>

                        <?php
                            $sql = "SELECT COUNT(item_id) AS NumberOfItems FROM erestaurant.cart";
                            $count = $database->getData_sql($sql);
                            if (mysqli_num_rows($count) === 0) {
                                echo "<span class=\"badge badge-dark badge-pill\">0</span>";
                            } else {
                                $row = mysqli_fetch_assoc($count);
                                $cartCount = $row['NumberOfItems'] ;
                                echo "<span class=\"badge badge-dark badge-pill\"> $cartCount </span>";
                            }

                        ?>
                    </h5>
                    <ul class="list-group mb-3 " style="overflow-y: auto; height: 100%; max-height: 400px;">
                        

                            <?php

                            $totalPrice = 0.0;
                            $subTotal = 0.0 ;
                            $delivery = 2;
                            $setBg = "btn-primary" ;
                            $sql = "SELECT * FROM cart WHERE user_id = $userId";
                            $getcartid = $database->getData_sql($sql);

                            if (mysqli_num_rows($getcartid) === 0) 
                            {
                                echo "<span class=\"bg-primary text-light p-2 text-center w-100\"> Nothing in cart </span>";
                                
                                $setBg = "d-none" ;
                            } 
                            else 
                            {
                                while ($row = mysqli_fetch_assoc($getcartid)) 
                                {
                                    $itemid = $row['item_id'];
                                    $itemqty = $row['quantity'];

                                    $getitem = "SELECT * FROM items WHERE id = $itemid";

                               
                                    $result_items = $database->getData_sql($getitem); 
                                    $numofrow = mysqli_num_rows($result_items) ;

                                    
                                    while ($row = mysqli_fetch_assoc($result_items)) 
                                    {
                                    
                                        $mealImg = $row['Meal_Img'];
                                        $mealname = $row['name'];
                                        //$mealType = $row['category'] ;
                                        $mealPrice = $row['price'];

                                        $mealTotalEach = $mealPrice * $itemqty ;
                                        $subTotal += $mealTotalEach ;

                                        $totalPrice = $subTotal + $delivery;

                                        echo
                                        "   <li class=\"list-group-item d-flex justify-content-between lh-condensed\">

                                                <img src=\"images/$mealImg\" alt=\"\" style=\"width: 100%; max-width: 50px;\">
                                                <div class=\"ml-2   w-100\">
                                                    <h6 class=\"my-0 text-primary\">$mealname</h6>
                                                    <small class=\"text-dark\">Qty: $itemqty</small>
                                                </div>
                                                <span class=\"text-dark w-100 ml-5 pl-5\">$ $mealTotalEach</span> 

                                                <form action=\"\" method=\"POST\" novalidate onkeydown=\"return event.key != 'Enter';\">
                                                    <button type=\"submit\" style=\"background:none; border:none; color:red; \" name=\"deleteitem\" >
                                                        <i class=\"fas fa-times-circle fa-lg\"></i>
                                                    </button>

                                                    <input type=\"hidden\" name=\"itemidDel\" value=\"$cartId\">
                                                </form>
                                            </li>
                                            ";

                                            
                                        
                                    }
                                }

                                
                            }


                            ?>

                        

                        <?php

                        echo "
                                <li class=\"list-group-item d-flex justify-content-between lh-condensed\">
                                    <div>
                                        <h6 class=\"my-0  text-dark\">Delivery</h6>
                                    </div>
                                    <span class=\"text-dark\">$ 2</span>
                                </li>
    
                                <li class=\"list-group-item d-flex justify-content-between text-dark\">
                                    <span>Total (USD)</span>
                                    <strong>$$totalPrice</strong>
                                </li>
                                ";

                        ?>
                    </ul>

                </div>


                <!--  Checkout -->
                <div class="col-md-8 order-md-1">

                    <div class="row text-center pb-3">
                        <div class="col-md-4">
                            <span class="step w-100 shadow " id="bill"> Billing address </span>
                        </div>
                        <div class="col-md-4">
                            <span class="step w-100 shadow bg-dark" id="pay">Payment</span>
                        </div>
                        <div class="col-md-4">
                            <span class="step w-100 shadow bg-dark" id="finish">Finish</span>
                        </div>
                    </div>

                    <form id="form" method="POST" action="./checkout.php" autocomplete="off" novalidate onkeydown="return event.key != 'Enter';">

                        <!-- Billing address -->
                        <div id="billAddredd" class="">

                            <div class="p-3 CheckOut-bg shadow">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="firstName">First name</label>
                                        <input type="text" id="firstname" name="B_fname" placeholder="First Name" class="form-control" />
                                        <small class="invalid-feedback">

                                        </small>

                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="lastName">Last name</label>
                                        <input type="text" class="form-control" id="lastName" name="B_lname" placeholder="Last Name" value="" required="">
                                        <small class="invalid-feedback">

                                        </small>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="address">Address</label>
                                    <input type="text" class="form-control" id="address" placeholder="1234 Main St" required="">
                                    <small class="invalid-feedback">

                                    </small>
                                </div>
                                <div class="mb-3">
                                    <label for="address2">Address 2 <span class="text-muted">(Optional)</span></label>
                                    <input type="text" class="form-control" id="address2" placeholder="Apartment or suite">
                                </div>
                                <div class="row">
                                    <div class="col-md-5 mb-3">
                                        <label for="country">Country</label>
                                        <select class="custom-select " id="country" name="country">
                                            <option value="Saudi Arabia">Saudi Arabia</option>
                                        </select>
                                        <small class="invalid-feedback">

                                        </small>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="state">City</label>
                                        <select class="custom-select d-block w-100 " id="city" name="city">
                                            <option value="">Choose...</option>
                                            <option>Khobar</option>
                                            <option>Dammam</option>
                                        </select>
                                        <small class="invalid-feedback">

                                        </small>
                                    </div>

                                </div>
                                <div class="row justify-content-center pt-4">
                                    <div class="col-md-5">
                                        <input type="button" value="Next" class="btn <?php echo $setBg;?> w-100" onclick="changePanel()">
                                    </div>
                                </div>
                            </div>


                        </div>
                        <!-- Payment -->
                        <div id="Payment" class="d-none">

                            <div class="p-3 CheckOut-bg shadow">

                                <div class="d-flex my-3 payment-method">
                                    <label for="discover" class="method card mr-2">
                                        <div class="card-logos">
                                            <img src="images/icons8-discover-48.png" />
                                        </div>

                                        <div class="">
                                            <input id="discover" type="radio" name="payment" value="Discover">
                                                Discover
                                        </div>

                                    </label>
                                    <label for="paypal" class="method card mr-2">
                                        <div class="card-logos">
                                            <img src="images/icons8-mastercard-48.png" />
                                        </div>

                                        <div class="">
                                            <input id="paypal" type="radio" name="payment" value="paypal" checked>
                                                Mastercard
                                        </div>
                                    </label>
                                    <label for="credit" class="method card">
                                        <div class="card-logos">
                                            <img src="images/icons8-visa-48.png" />
                                        </div>

                                        <div class="">
                                            <input id="credit" type="radio" name="payment" value="Visa Card">
                                                Visa Card
                                        </div>
                                    </label>



                                </div>
                                <div class="row my-1">
                                    <div class="col-md-6 mb-3 form-group">
                                        <label for="cc-name">Name on card</label>
                                        <input type="text" class="form-control" name="cardname" id="cc-name" placeholder="" style="transition: .5s;">
                                        <small class="invalid-feedback">

                                        </small>

                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="cc-number">card number</label>
                                        <input type="text" class="form-control" name="cardnumber" id="cc-number" placeholder="" onkeypress="return digitOnly(event)">
                                        <small class="invalid-feedback">

                                        </small>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label for="cc-expiration">Expiration</label>
                                            <input type="month" name="ccexp" class="custom-select p-2" id="ccexp">
                                        <!-- <div class="d-none">
                                            <select name="" id="Select_month" class="custom-select">
                                                <option value=''>MM</option>
                                                <option value='01' selected>January</option>
                                                <option value='02'>February</option>
                                                <option value='03'>March</option>
                                                <option value='04'>April</option>
                                                <option value='05'>May</option>
                                                <option value='06'>June</option>
                                                <option value='07'>July</option>
                                                <option value='08'>August</option>
                                                <option value='09'>September</option>
                                                <option value='10'>October</option>
                                                <option value='11'>November</option>
                                                <option value='12'>December</option>
                                            </select>
                                            <select name="" id="years" class="custom-select ">
                                                <option value=''>YY</option>
                                                <option value='21'>2021</option>
                                                <option value='22' selected>2022</option>
                                                <option value='23'>2023</option>
                                                <option value='24'>2024</option>
                                                <option value='55'>2025</option>
                                                <option value='26'>2026</option>
                                                <option value='27'>2027</option>
                                            </select>

                                        </div> -->
                                        <small class="invalid-feedback">

                                        </small>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="cvv">CVV</label>
                                        <input type="password" class="form-control" name="cvv" id="cvv" placeholder="" onkeypress="return digitOnly(event)">
                                        <small class="invalid-feedback">

                                        </small>
                                    </div>
                                </div>
                                <div class="row justify-content-center pt-4">
                                    <div class="col-md-5">
                                        <input type="button" value="Back" class="btn btn-primary w-100" onclick="PrevPanel()">
                                    </div>
                                    <div class="col-md-5">
                                        <input type="button" value="Next" class="btn btn-primary w-100" onclick="LastPanel()">
                                    </div>
                                </div>
                            </div>



                        </div>

                        <div id="PlaceOrder" class="d-none">

                            <div class="bg-light shadow">
                                <div class="card-body">
                                    <?php
                                        $countOrder = "SELECT COUNT(order_id) AS NumberOfOrders FROM erestaurant.orders" ;
                                        $result = $database->getData_sql($countOrder) ;

                                        $row = mysqli_fetch_assoc($result) ;

                                        $NewOrder = $row['NumberOfOrders'] + 1 ;
                                        $OrderDate = date("Y/m/d") ;
                                        $status = "Pending" ;
                                        echo 
                                        "
                                        <div class=\"row justify-content-center\">
                                            <div class=\"col-md-8\">
                                                <div>
                                                    <span class=\"text-dark\">Customer name: </span>
                                                </div>
                                                <div class=\"my-1\">
                                                    <span class=\"text-dark\">City, cont</span>
                                                </div>
                                                <div class=\"my-1\">
                                                    <span class=\"text-dark\">Phone number: </span>
                                                </div>
                                            </div>
                                            <div class=\"col-md-3 pl-1\">
                                                <div class=\"my-1\">
                                                    <span class=\"text-dark\">Order #: $NewOrder</span>
                                                </div>
                                                <div class=\"my-1\">
                                                    <span class=\"text-dark\">Date: $OrderDate</span>
                                                </div>
                                                <div class=\"my-1\">
                                                    <span class=\"text-dark\">Status: $status</span>
                                                </div>
                                            </div>
                                        </div> 
                                        " ;
                                    ?>
                                    <div class="table-responsive-sm my-3" style="height: 100%; max-height: 300px; overflow-y: auto;">
                                        <table class="table table-hover" >
                                            <thead class="shadow">
                                                <tr class="text-dark">
                                                    <th class="center">#</th>
                                                    <th>Item</th>

                                                    <th class="right">Price</th>
                                                    <th class="center">Qty</th>
                                                    <th class="right">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    if (mysqli_num_rows($getcartid) === 0) 
                                                    {
                                                        echo "<tr class=\"bg-primary text-light p-2 text-center \"> <td colspan=\"5\">Nothing in cart </td> </tr>";
                                                    } 
                                                    else 
                                                    {
                                                        $sql = "SELECT * FROM cart WHERE user_id = $userId";
                                                        $getcartid = $database->getData_sql($sql);

                                                        while ($row = mysqli_fetch_assoc($getcartid)) 
                                                        {

                                                            $itemid = $row['item_id'];
                                                            $itemqty = $row['quantity'];
                                                            $getitem = "SELECT * FROM items WHERE id = $itemid";
                                                            $result_items = $database->getData_sql($getitem); 

                                                            while ($rows= mysqli_fetch_assoc($result_items))
                                                            {
                                                                $mealImg = $rows['Meal_Img'];
                                                                $mealname = $rows['name'];
                                                                //$mealType = $row['category'] ;
                                                                $mealPrice = $rows['price'];
                                                                $TableTotal = $mealPrice * $itemqty ;
                                                                
                                                                echo 
                                                                "
                                                                <tr>
                                                                    <td class=\"center\">1</td>
                                                                    <td class=\"left strong\">$mealname</td>
                                                                    <td class=\"right\">$ $mealPrice</td>
                                                                    <td class=\"center\">$itemqty</td>
                                                                    <td class=\"right\">$ $TableTotal</td>
                                                                </tr>
                                                                " ;
                                                            }
                                                        }
                                                    }
                                                ?>
                                                
                                               
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-5 col-sm-5 ml-auto my-5">
                                            <input type="submit" value="Place Order" name="PlaceOrder" class="btn btn-primary w-100">
                                            
                                            <a class="dropdown-item w-100" href="#" data-toggle="modal" data-target="#cancelOrder">
                                                <button type="button" class="btn btn-warning w-100 my-3"> Cancel Order</button>
                                            </a>
                                        </div>
                                        <div class="col-lg-4 col-sm-5 ml-auto">
                                            <table class="table table-clear">
                                                <tbody>
                                                    <tr>
                                                        <td class="left">
                                                            <strong>Subtotal</strong>
                                                        </td>
                                                        <?php echo "<td class=\"right\">$ $subTotal</td>" ; ?>
                                                        
                                                    </tr>
                                                    <tr>
                                                        <td class="left">
                                                        
                                                            <strong>Delivery </strong>
                                                        </td>
                                                        <?php echo "<td class=\"right\">$ $delivery</td>" ; ?>
                                                        
                                                    </tr>
                                                    <tr>
                                                        <td class="left text-dark">
                                                            <strong>Total</strong>
                                                        </td>
                                                        <td class="right text-dark">
                                                        <?php
                                                            echo "<strong>$ $totalPrice</strong>" ;
                                                        ?>
                                                            
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                        </div>

                                    </div>

                                </div>
                            </div>
                            
                        </div>



                    </form>
                </div>
            </div>

        </div>

    </section>


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

     <!-- Cancel order model-->
     <div class="modal fade" id="cancelOrder" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Are you sure you want to cancel the order ?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Cancel order" below if you want to cancel your order.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="./menu.php">Cancel Order</a>
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



         
    <!-- Form validation -->
    <script>
        var form = document.getElementById('form');

        var ccname = document.getElementById('cc-name');
        var ccnum = document.getElementById('cc-number');
        var cvv = document.getElementById('cvv');
        var ccexp = document.getElementById('ccexp') ;
        //var Select_month = document.getElementById('Select_month') ;
        //var Select_year = document.getElementById('years') ;
    
        //...........................................

        var fname = document.getElementById('firstname');
        var lname = document.getElementById('lastName');
        var address = document.getElementById('address');
        var country = document.getElementById('country');
        var city = document.getElementById('city');

        var elements = [fname, lname, address, country, city, ccname, ccnum, cvv];

        form.addEventListener('submit', e => {

            // e.preventDefault();

            checkInputs();
            checkInputs2();

            let result = elements.every(function(e) {
                if (e.className === 'form-control is-valid' || e.className === 'custom-select is-valid') {
                    return true;
                }

            });

            if (!result) {
                e.preventDefault();
                e.stopPropagation();
            }

        }, false);


        function checkInputs() {

            //var Select_monthval = Select_month.value.trim() ;
            //var Select_yearVal = Select_year.value.trim() ;

            //.........................................

            var fnameValue = fname.value.trim();
            var lnameValue = lname.value.trim();
            var addressValue = address.value.trim();
            var countryValue = country.value.trim();
            var cityValue = city.value.trim();


            if (countryValue === '') {
                var formControl = country.parentElement;
                var small = formControl.querySelector('small');

                country.className = 'custom-select is-invalid';
                small.innerText = 'Field are required';

            } else {
                var formControl = country.parentElement;
                var small = formControl.querySelector('small');

                country.className = 'custom-select is-valid';
                small.innerText = '';
            }
            //.....................
            if (cityValue === '') {
                var formControl = city.parentElement;
                var small = formControl.querySelector('small');

                city.className = 'custom-select is-invalid';
                small.innerText = 'Field are required';

            } else {
                var formControl = city.parentElement;
                var small = formControl.querySelector('small');

                city.className = 'custom-select is-valid';
                small.innerText = '';
            }
            //.....................
            if (fnameValue === '') {
                setErrorFor(fname, 'First name is required');
            } else {
                setSuccessFor(fname);
            }
            //.....................
            if (lnameValue === '') {
                setErrorFor(lname, 'Last name is required');
            } else {
                setSuccessFor(lname);
            }
            //......................
            if (addressValue === '') {
                setErrorFor(address, 'Address is required');
            } else {
                setSuccessFor(address);
            }

            return true;

        }

        function checkInputs2() {
            var ccnameValue = ccname.value.trim();
            var ccnumValue = ccnum.value.trim();
            var cvvValue = cvv.value.trim();
            var ccexpval = ccexp.value.trim() ;

            if (ccnameValue === '') {
                setErrorFor(ccname, 'Name on card is required');
            } else {
                setSuccessFor(ccname);
            }
            //.............................................
            if (ccnumValue != '') {
                if (ccnumValue.length < 13 || ccnumValue.length > 16) {
                    setErrorFor(ccnum, 'Please Provide proper card number');
                } else {
                    setSuccessFor(ccnum);
                }
            } else {
                setErrorFor(ccnum, 'Card number is required');
            }
            //..............................................
            if (cvvValue != '') {
                if (cvvValue.length != 3) {
                    setErrorFor(cvv, 'Plase provide proper security code');
                } else {
                    setSuccessFor(cvv);
                }
            } else {
                setErrorFor(cvv, 'Security code is required');
            }
            //...............................................
            if(ccexpval === '')
            {
                var formControl = ccexp.parentElement;
                var small = formControl.querySelector('small');

                ccexp.className = 'custom-select p-2 is-invalid';
                small.innerText = "Field are required";
            }
            else
            {
                var formControl = ccexp.parentElement;
                var small = formControl.querySelector('small');

                ccexp.className = 'custom-select p-2 is-valid';
                small.innerText = "";
            }
        }

        function setErrorFor(input, message) {
            var formControl = input.parentElement;
            var small = formControl.querySelector('small');

            input.className = 'form-control is-invalid';
            small.innerText = message;
        }

        function setSuccessFor(input) {
            var formControl = input.parentElement;
            var small = formControl.querySelector('small');

            small.innerText = "";

            input.className = 'form-control is-valid';
        }

        //..........................
        function digitOnly(e) {
            var keyCode = e.keyCode == 0 ? e.charCode : e.keyCode;
            var value = Number(e.target.value + e.key) || 0;

            if ((keyCode >= 37 && keyCode <= 40) || (keyCode == 8 || keyCode == 9 || keyCode == 13) || (keyCode >= 48 && keyCode <= 57)) {
                return true;
            }
            return false;
        }


        function changePanel() {
            checkInputs();

            var ele = [fname, lname, address, country, city];

            let result = ele.every(function(e) {
                if (e.className === 'form-control is-valid' || e.className === 'custom-select is-valid') {
                    return true;
                }

            });

            if (result) {
                document.getElementById('billAddredd').className = 'd-none';
                document.getElementById('bill').className = 'step w-100 shadow bg-dark';
                document.getElementById('pay').className = 'step w-100 shadow';
                document.getElementById('Payment').className = '';
            }


        }

        function PrevPanel() {
            document.getElementById('bill').className = 'step w-100 shadow';
            document.getElementById('pay').className = 'step w-100 shadow bg-dark';
            document.getElementById('Payment').className = 'd-none';
            document.getElementById('billAddredd').className = '';
        }

        function LastPanel() {
            checkInputs2();

            var ele = [ccname, ccnum, cvv, ccexp];

            let result = ele.every(function(e) {
                if (e.className === 'form-control is-valid' || e.className === 'custom-select p-2 is-valid') {
                    return true;
                }

            });

            if (result) {
                document.getElementById('pay').className = 'step w-100 shadow bg-dark';
                document.getElementById('finish').className = 'step w-100 shadow';
                document.getElementById('Payment').className = 'd-none';
                document.getElementById('PlaceOrder').className = '';
            }
        }


        // Non-from validation script 
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

       
    </script>
    
    <script>
        window.onload = function() {
            history.replaceState("", "", "./checkout.php");
        }
    </script>
</body>



</html>




<div id="push"></div>