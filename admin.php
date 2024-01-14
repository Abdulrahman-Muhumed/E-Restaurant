<?php

    session_start();    

    require_once("./php/database.php");

    $database = new mydatabase("items");
    
    $username = "";

    if (isset($_SESSION['users'])) 
    {
        $userId = $_SESSION['users'];
        $sql = "SELECT First_Name,is_admin FROM users WHERE id= '$userId' ";
        $result = $database->getData_sql($sql);

        while ($row = mysqli_fetch_assoc($result)) 
        {
            $username = $row['First_Name'];

            
            if($row['is_admin'] == 1)
            {
                $username = $row['First_Name'];
            }
            else
            {
                header("location: ./php/Error403.php") ;
                exit() ;
            }
           
        }  
    }
    else
    {
        header("location: ./php/Error403.php") ;
        exit() ;
    }
    
    if(isset($_POST['addproduct']))
    { 
        $mname = $_POST['mealname'];
        $mcategory = $_POST['mcategory'];
        $mimg = $_POST['mealimg'];
        $mprice = $_POST['mealprice'];
        $mKcal = $_POST['mealKcal'];
        $mqty = $_POST['mealqty'];
        $mdes = $_POST['mdescription'];

        
        $sql = "INSERT INTO erestaurant.items (name,description,price,category,Meal_img,Kcal,Quantity) values ('$mname' , '$mdes' , '$mprice' , '$mcategory' , ' $mimg' , '$mKcal' , '$mqty')" ;

        $result = $database->StoreData($sql) ;
        
        if($result)
        {
            echo "
                <script> 
                    alert ('Item has been added'); 
                    window.location.replace('./admin.php');
                </script> ;
                
            " ;
            
        }
    }
    
    $itemId = array();
    $sqls = "SELECT id from items" ;
    $results = $database->getData_sql($sqls) ;
    while ($row = mysqli_fetch_assoc($results)) 
    {
        $itemId[] = $row;
    }

    if(isset($_POST['removeitem']))
    {
        $meal_id = $_POST['Meal_ID'] ;
        
        $getname = "SELECT * FROM items WHERE id = $meal_id" ;
        $getnamesql = $database->Run_query($getname) ;

        while($ele = mysqli_fetch_assoc($getnamesql))
        {
            $mealname = $ele['name'] ;
        }
        
        
        $removeItem = "DELETE FROM items WHERE id = $meal_id" ;
        $res = $database->Run_query($removeItem) ;

        if($res)
        {
            echo "
            <script> 
                alert ('$mealname has been removed'); 
                window.location.replace('./admin.php');
            </script>" ;
        }
        
    }

    

    
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Admin</title>
    <link rel="icon" type="image/x-icon" href="images/MainLogo.png" />
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <!-- Core theme CSS (includes Bootstrap)-->

    <link rel="stylesheet" href="css/style.css">

    <script>
        function SortItems(str) 
        {
            if (str == "") 
            {
                return;
            } 
            else 
            {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("txtHint").innerHTML = this.responseText;
                }
                };
                xmlhttp.open("GET","./php/sortItems.php?q="+str,true);
                xmlhttp.send();
                document.getElementById('mytables').style.display = 'none' ;
            }
        }

        
    </script>
</head>

<body id="page-top">

    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center my-3" href="./admin.php">
                <div class="sidebar-brand-icon ">
                    <img src="./images/MainLogo.png" alt="E-Restaurant" width="60px">
                    <br>

                </div>
                <div class="sidebar-brand-text">Admin</div>
            </a>


            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Interface
            </div>

            <!-- Nav Item - Interface -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Components</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Components:</h6>
                        <a class="collapse-item" href="#">Side Bar</a>
                        <a class="collapse-item" href="#">Cards</a>
                    </div>
                </div>
            </li>



            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Services
            </div>

            <!-- Nav Item - Services / Meals -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-utensils"></i>
                    <span>Meals</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Choose a service:</h6>
                        <a class="collapse-item" href="#service">Add Meal</a>
                        <a class="collapse-item" href="#service">Remove Meal</a>
                        <a class="collapse-item" href="#service">Edit Meal</a>
                        <a class="collapse-item" href="#service">Display Meals</a>
                    </div>
                </div>
            </li>
            <!-- Nav Item - Pages Collapse Menu -->
           



            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle">

                </button>
            </div>

            <div class="text-center d-none d-md-inline my-4">
                <a href="./index.php">
                    <img src="./images/MainLogo.png" alt="E-Restaurant" width="60px">
                    <br>
                    <span class="text-light">Navigate as a customer</span>
                    <br>
                    <span class="text-light small">CLick the icon in the nav bar to come back here</span>
                </a>  
            </div>



        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top ">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
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

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="text-primary fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter">3+</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    Alerts Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-file-alt text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 12, 2019</div>
                                        <span class="font-weight-bold">A new monthly report is ready to download!</span>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-success">
                                            <i class="fas fa-donate text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 7, 2019</div>
                                        $290.29 has been deposited into your account!
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-warning">
                                            <i class="fas fa-exclamation-triangle text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 2, 2019</div>
                                        Spending Alert: We've noticed unusually high spending for your account.
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                            </div>
                        </li>

                        <!-- Nav Item - Messages -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="text-primary fas fa-envelope fa-fw"></i>
                                <!-- Counter - Messages -->
                                <span class="badge badge-danger badge-counter">7</span>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">
                                    Message Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_1.svg" alt="">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div class="font-weight-bold">
                                        <div class="text-truncate">Hi there! I am wondering if you can help me with a
                                            problem I've been having.</div>
                                        <div class="small text-gray-500">Emily Fowler · 58m</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_2.svg" alt="">
                                        <div class="status-indicator"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">I have the photos that you ordered last month, how
                                            would you like them sent to you?</div>
                                        <div class="small text-gray-500">Jae Chun · 1d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_3.svg" alt="">
                                        <div class="status-indicator bg-warning"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Last month's report looks great, I am very happy with
                                            the progress so far, keep up the good work!</div>
                                        <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60" alt="">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Am I a good boy? The reason I ask is because someone
                                            told me that people say this to all dogs, even if they aren't good...</div>
                                        <div class="small text-gray-500">Chicken the Dog · 2w</div>
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                welcome, 
                                <?php echo "<span class=\"font-weight-bold mr-2  text-gray-600 small\">$username</span>" ; ?>
                                
                                <img class="img-profile rounded-circle" src="images/MainLogo.png">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#UserProfile">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="./php/logout.php" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    </div>
                    <!-- Begin Page Content 1st row-->
                    <div class="row ">

                        <!-- Earnings (Monthly) Card  -->
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Earnings (Monthly)</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">$40,000</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Annual) Card  -->
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Earnings (Annual)</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">$215,000</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>

                    <!-- 2nd row Earning & rev charts-->
                    <div class="row justify-content-center ">

                        <!-- Area Chart -->
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Earnings Overview</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Dropdown Header:</div>
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="myAreaChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Revenue Sources</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Dropdown Header:</div>
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-pie pt-4 pb-2">
                                        <canvas id="myPieChart"></canvas>
                                    </div>
                                    <div class="mt-4 text-center small">
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-primary"></i> Direct
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-success"></i> Social
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-info"></i> Referral
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Services header-->
                    <div class="row" id="service">
                        <div class="col-xl-8 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Services</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">Meals</div>
                                        </div>
                                        <div class="col-auto">

                                            <i class="fas fa-utensils fa-2x text-gray-600"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Add meal-->
                    <div class="row ">

                        <div class="col-xl-6 col-lg-7">
                            <div class="card  shadow mb-3 ">
                                <!-- Card Header  -->

                                <button class="btn btn-primary" id="AddMealBut">
                                    <div class="row no-gutters align-items-center p-3">
                                        <div class="col mr-2">

                                            <h6 class="text-light m-0 font-weight-bold text-primary">Add meal</h6>
                                        </div>
                                        <div class="col-auto">
                                            <i class="text-light fas fa-plus-circle fa-lg"></i>

                                        </div>
                                    </div>
                                </button>
                                <!-- Card Body -->
                                <div class="card-body my-auto d-none border-bottom-primary" id="AddMealPanel">

                                    <form action="./admin.php" method="POST" id="additemForm" name="additemFm">
                                        <h6 class="w100 text-center text-primary pb-4 font-weight-bold">Please Fill the following </h6>
                                        <div class="row ">
                                            <div class="col-md-4">
                                                <label for="meal">Meal name: </label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" id="mealname" placeholder="Meal name" class="services_input" name="mealname">
                                                <small id="errmsg">

                                                </small>
                                            </div>
                                        </div>
                                        <br>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="category">Category: </label>
                                            </div>
                                            <div class="col-md-8">
                                                <select name="mcategory" id="category" class="services_input">
                                                    <option value="">Choose...</option>
                                                    <option>Breakfast</option>
                                                    <option>Lunch</option>
                                                    <option>Dinner</option>
                                                    <option>Drinks</option>
                                                </select>
                                                <small id="errmsg">

                                                </small>
                                            </div>
                                        </div>
                                        <br>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="">Meal image: </label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="file" value="" class="services_input" name="mealimg" id="mealimg">
                                                <small id="errmsg" class="text-dark">

                                                </small>
                                            </div>

                                        </div>
                                        <br>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="price">Price:</label>
                                            </div>
                                            <div class="col-md-5">
                                                <input type="text" id="mealprice" placeholder="Price" class="services_input" name="mealprice" onkeyup="checkPrice(this);">
                                                <small id="errmsg">

                                                </small>
                                            </div>

                                        </div>
                                        <br>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="kcal">Kcal:</label>
                                            </div>
                                            <div class="col-md-5">
                                                <input type="text" id="mealkcal" placeholder="Kcal" class="services_input" name="mealKcal" onkeypress="return digitOnly(event)">
                                                <small id="errmsg" class="text-dark">

                                                </small>
                                            </div>

                                        </div>
                                        <br>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="qty">Quantity:</label>
                                            </div>
                                            <div class="col-md-5">
                                                <input type="text" id="qty" placeholder="Quantity" class="services_input" name="mealqty" onkeypress="return digitOnly(event)">
                                                <small id="errmsg">

                                                </small>
                                            </div>
                                        </div>
                                        <br>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Description</label>
                                            </div>

                                            <div class="col-md-12">
                                                <textarea name="mdescription" id="des" cols="30" rows="3" placeholder="Some description..." class="services_input" style="height: auto;" name="mealdes"></textarea>
                                                <small id="errmsg" class="text-dark">

                                                </small>
                                            </div>
                                        </div>
                                        <br>


                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="submit" value="Add meal" class="mybut" name="addproduct" id="addbtn" >
                                            </div>
                                            <div class="col-md-6">
                                                <input type="reset" value="Clear" class="mybut">
                                            </div>
                                        </div>


                                        <br>
                                    </form>

                                </div>

                            </div>
                        </div>


                    </div>

                    <!--remove meal-->
                    <div class="row ">

                        <div class="col-xl-6 col-lg-7">
                            <div class="card  shadow mb-3 ">
                                <!-- Card Header  -->
                                <button class="btn btn-danger" id="RemoveMealBut">
                                    <div class="row no-gutters align-items-center p-3 ">
                                        <div class="col mr-2">
                                            <h6 class="text-light m-0 font-weight-bold text-primary">Remove meal</h6>
                                        </div>
                                        <div class="col-auto">
                                            <i class="text-light fas fa-minus-circle fa-lg"></i>

                                        </div>
                                    </div>
                                </button>
                                <!-- Card Body -->
                                <div class="card-body my-auto d-none border-bottom-danger" id="RemoveMealPanel">
                                    <form action="./admin.php" method="POST" id="removeitemForm" >
                                        <h6 class="w100 text-center text-primary my-2 font-weight-bold">Enter Meal ID to which you want to Delete </h6>
          
                                        <div class="row my-4">
                                            <div class="col-md-4">
                                                <label for="Meal_ID">Meal ID:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" id="Meal_ID" placeholder="Meal ID" class="services_input" name="Meal_ID" onkeypress="return digitOnly(event)" autocomplete="off">
                                                <small id="errmsg">

                                                </small>
                                            </div>
                                        </div>



                                        <div class="row">
                                            <div class="col-md-6">
                                                <!-- <input type="submit" value="Search" class="mybut" name="removeproduct"> -->
                                                <button class="mybut" type="submit" name="removeitem" >Remove</button>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="reset" value="Clear" class="mybut">
                                            </div>
                                        </div>



                                    </form>
                                    <form action="./admin.php" method="POST" class="d-none">
                                        <table class="table table-hover p-0 ">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th scope="col">ID</th>
                                                    <th scope="col">Image</th>
                                                    <th scope="col">Quantity</th>
                                                    <th scope="col">Kcal</th>
                                                    <th scope="col">Price</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                            
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                                <!-- <div class="card-body my-auto border-bottom-success " style="overflow-y: auto;" id="ViewMealPanel_rem">
                                    <div class="dropdown pb-2">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Sort by
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="#">ID</a>
                                            <a class="dropdown-item" href="#">Name</a>
                                            <a class="dropdown-item" href="#">Quantity</a>
                                            <a class="dropdown-item" href="#">Price</a>
                                            <a class="dropdown-item" href="#">Kcal</a>
                                        </div>
                                    </div>
                                    <table class="table table-hover p-0 ">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th scope="col">ID</th>
                                                <th scope="col">Image</th>
                                                <th scope="col">Quantity</th>
                                                <th scope="col">Kcal</th>
                                                <th scope="col">Price</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                           
                                        </tbody>
                                    </table>

                                </div> -->
                            </div>
                        </div>


                    </div>

                    <!--Edit meal-->
                    <div class="row ">

                        <div class="col-xl-6 col-lg-7">
                            <div class="card  shadow mb-3 ">
                                <!-- Card Header  -->
                                <button class="btn btn-warning" id="EditMealBut">
                                    <div class="row no-gutters align-items-center p-3 ">
                                        <div class="col mr-2">

                                            <h6 class="text-light m-0 font-weight-bold text-primary">Edit meal</h6>
                                        </div>
                                        <div class="col-auto">
                                            <i class="text-light fas fa-edit fa-lg"></i>

                                        </div>
                                    </div>
                                </button>
                                <!-- Card Body -->
                                <div class="card-body my-auto border-bottom-warning d-none" id="EditMealPanel">
                                    <form action="./admin.php" method="POST">
                                        <h6 class="w100 text-center text-primary my-2 font-weight-bold">Enter Meal name or Meal ID to which you want to edit </h6>
                                        <div class="row my-4">
                                            <div class="col-md-4">
                                                <label for="meal">Meal name: </label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" id="meal" placeholder="Meal name" class="services_input" name="mealname">
                                            </div>
                                        </div>




                                        <div class="row my-4">
                                            <div class="col-md-4">
                                                <label for="Meal_ID">Meal ID:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="number" id="Meal_ID" min="0" placeholder="Meal ID" class="services_input" name="Meal_ID">
                                            </div>
                                        </div>



                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="submit" value="Search" class="mybut" name="Editproduct">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="reset" value="Clear" class="mybut">
                                            </div>
                                        </div>



                                    </form>

                                </div>

                            </div>
                        </div>


                    </div>

                    <!--View meal-->
                    <div class="row ">

                        <div class="col-xl-6 col-lg-7">
                            <div class="card border-left-success shadow mb-3 " id="viewmeal">
                                <!-- Card Header  -->
                                <button class="btn btn-success" id="ViewMealBut">
                                    <div class="row no-gutters align-items-center p-3 ">
                                        <div class="col mr-2">

                                            <h6 class="text-light m-0 font-weight-bold text-primary">View meal</h6>
                                        </div>
                                        <div class="col-auto">

                                            <i class="text-light fas fa-clipboard-list fa-lg"></i>

                                        </div>
                                    </div>
                                </button>
                                <!-- Card Body -->
                                <div class="card-body my-auto border-bottom-success d-none" style="overflow-y: auto;" id="ViewMealPanel">
                                    <div class="dropdown pb-2">
                                        <!-- <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Sort by
                                        </button> -->
                                        <select onclick="SortItems(this.value)">
                                            <option value="">Sort by:</option>
                                            <option value="1">ID</option>
                                            <option value="2">Price</option>
                                            <option value="3">Quantity</option>
                                            <option value="4">Kcal</option>
                                        </select>
                                        <div id="txtHint">  </div>
                                        
                                        <!-- <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="#">ID</a>
                                            <a class="dropdown-item" href="#">Name</a>
                                            <a class="dropdown-item" href="#">Quantity</a>
                                            <a class="dropdown-item" href="#">Price</a>
                                            <a class="dropdown-item" href="#">Kcal</a>
                                        </div> -->
                                    </div>
                                    <table class="table table-hover p-0 " id="mytables">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th scope="col">ID</th>
                                                <th scope="col">Image</th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Quantity</th>
                                                <th scope="col">Kcal</th>
                                                <th scope="col">Category</th>
                                                <th scope="col">Description</th>
                                                <th scope="col">Price</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            $sql = "SELECT * FROM items" ;
                                            $sresult = $database->getData_sql($sql) ;

                                            while($row = mysqli_fetch_assoc($sresult))
                                            {
                                                
                                                    echo "<tr>";
                                                        echo "<th scope='row'>". $row['id'] ."</th>" ;
                                                        echo "<td>  <img style='width: 60px' src=\"./images/".$row['Meal_Img']."\" alt=\"\"> </td>";
                                                        echo "<td>" . $row['name'] . "</td>";
                                                        echo "<td>" . $row['Quantity'] . "</td>";
                                                        echo "<td>" . $row['Kcal'] . "</td>";
                                                        echo "<td>" . $row['category'] . "</td>";
                                                        echo "<td>" . $row['description'] . "</td>";
                                                        echo "<td>" . $row['price'] . "</td>";
                                                    echo "</tr>";
                                                
                                            }
                                        ?>
                                        
                                        
                                        
                                        </tbody>
                                        
                                    </table>

                                </div>

                            </div>
                        </div>


                    </div>
                </div>


            </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->

    </div>

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
    <script src="js/Chart.js"></script>
    <script src="js/chart-area-demo.js"></script>
    <script src="js/chart-pie-demo.js"></script>
            
    <script>


        // Add item elements 
        
        var additemForm = document.getElementById('additemForm'); // Add item Form
        var mealname = document.getElementById('mealname');
        var mealprice = document.getElementById('mealprice');
        var quantity = document.getElementById('qty');
        var category = document.getElementById('category');
        var mkcal = document.getElementById('mealkcal');
        var mdes = document.getElementById('des');
        var mealimg = document.getElementById('mealimg');

        // arrayAdd is for the inputs the user must provide 
        var arrayAdd = [mealname, mealprice, quantity, category];

        // Remove Item elements 
        var removeitemForm = document.getElementById('removeitemForm') ; // remove meal form
        var mealid = document.getElementById('Meal_ID') ;   

        removeitemForm.addEventListener('submit' , function(e)
        {
            if(mealid.value.trim() === '')
            {
                setErrorFor(mealid , 'Field are required') ;
                event.preventDefault();
                event.stopPropagation();
            }
            else if(!checkID())
            {
                setErrorFor(mealid , 'No such ID found') ;
                event.preventDefault();
                event.stopPropagation();
            }
            else
            {
                setSuccessFor(mealid) ;
            }

        },false);
        
        // an event listner for the Add meal form
        additemForm.addEventListener('submit', e => {

            checkValues(); // pass to checkValues function 

            // loop between the arrayAdd array and check if all inputs have green border (isValid) 
            let result = arrayAdd.every(function(e) {
                if (e.className === 'services_input border-success' || e.className === 'services_input border-success') {
                    return true;
                }

            });

            // submit the form if all inputs are correct using the form
            if (!result) 
            {
                event.preventDefault();
                event.stopPropagation();
            }
           
        },false);
    
        
        // This function checks if the provided inputs are correct and forward the result to other functions
        function checkValues() 
        {
            var mealnameVal = mealname.value.trim();
            var mealpriceVal = mealprice.value.trim();
            var qtyVal = qty.value.trim();
            var categoryVal = category.value.trim();
            var mkcalVal = mkcal.value.trim();
            var mdesVal = mdes.value.trim();
            var mealimgVal = mealimg.value.trim();

            if (mealimgVal === '') 
            {
                var formControl = mealimg.parentElement;
                var small = formControl.querySelector('small');

                mealimg.className = 'services_input border-warning';


                small.innerText = 'You may want to provide a meal image';
            } 
            else 
            {
                setSuccessFor(mealimg);
            }

            if (mdesVal === '') {
                var formControl = mdes.parentElement;
                var small = formControl.querySelector('small');

                mdes.className = 'services_input border-warning';


                small.innerText = 'You may want to provide some description';
            } 
            else 
            {
                setSuccessFor(mdes);
            }

            if (mkcalVal === '') {
                var formControl = mkcal.parentElement;
                var small = formControl.querySelector('small');

                mkcal.className = 'services_input border-warning';


                small.innerText = 'You may want to provide a Kcal';
            } 
            else 
            {
                setSuccessFor(mkcal);
            }

            if (categoryVal === '') 
            {

                setErrorFor(category , 'Please choose a category') ;
            } 
            else 
            {
                setSuccessFor(category) ;
            }

            if (mealnameVal === '') {
                setErrorFor(mealname, 'Please provide meal name');
            } 
            else 
            {
                setSuccessFor(mealname);
            }

            if (mealpriceVal === '') {
                setErrorFor(mealprice, 'Please provide a price for the meal');
            } 
            else 
            {
                setSuccessFor(mealprice);
            }

            if (qtyVal === '') {
                setErrorFor(qty, 'Please provide a quantity')
            } 
            else 
            {
                setSuccessFor(qty);
            }

            
        }

        // This function changes the border to red and set the error message
        function setErrorFor(input, message) {

            var formControl = input.parentElement; // get the parent element for the input
            var small = formControl.querySelector('small'); // get the small element under the parent element

            input.className = 'services_input border-danger'; // change the input class name to red border.....


            small.innerText = message; // set the message passed form checkValues() 
        }

        // This function changes the border to green and removes the error message
        function setSuccessFor(input) 
        {
            input.className = 'services_input border-success';

            var formControl = input.parentElement;
            var small = formControl.querySelector('small');

            small.innerText = "";

        }


        // This function will force the user to provide int values only
        function digitOnly(e) 
        {
            var keyCode = e.keyCode == 0 ? e.charCode : e.keyCode;
            var value = Number(e.target.value + e.key) || 0;

            if ((keyCode >= 37 && keyCode <= 40) || (keyCode == 8 || keyCode == 9 || keyCode == 13) || (keyCode >= 48 && keyCode <= 57)) {
                return true;
            }
            return false;
        }

        // This function forces the user to provide integer values and a . for the prices
        function checkPrice(e) 
        {
            var ex = /^[0-9]+\.?[0-9]*$/;

            if (ex.test(e.value) == false) {
                e.value = e.value.substring(0, e.value.length - 1);
            }
        }

        
        function checkID()
        {
            var itemID = <?php echo json_encode($itemId); ?>;
            var lenth = itemID.length;

            for (var i = 0; i < lenth; i++) 
            {
                if (itemID[i].id === mealid.value.trim()) 
                {
                    return true ;
                }
                
            }

            return false ;
        }
       
    </script>

    <script>
        window.onload = function() 
        {
            history.replaceState("", "", "./admin.php");
        }
    </script>
    
</body>

</html>