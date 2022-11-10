<!DOCTYPE html>
<html lang="en">


<head>
    <title>Mentor - Bootstrap 4 Admin Dashboard Template</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="Admin template that can be used to build dashboards for CRM, CMS, etc." />
    <meta name="author" content="Potenza Global Solutions" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- app favicon -->
    <link rel="shortcut icon" href="assets/img/favicon.ico">
    <!-- google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <!-- plugin stylesheets -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/admin/assets/css/vendors.css')}}" />
    <!-- app style -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/admin/assets/css/style.css')}}" />
</head>

<body class="bg-white">
    <!-- begin app -->
    <div class="app">
        <!-- begin app-wrap -->
        <div class="app-wrap">
            <!-- begin pre-loader -->
            <div class="loader">
                <div class="h-100 d-flex justify-content-center">
                    <div class="align-self-center">
                        <img src="{{asset('assets/admin/assets/img/loader/loader.svg')}}" alt="loader">
                    </div>
                </div>
            </div>
            <!-- end pre-loader -->

            <!--start login contant-->
            <div class="app-contant">
                <div class="bg-white">
                    <div class="container-fluid p-0">
                        <div class="row no-gutters">
                            <div class="col-sm-6 col-lg-5 col-xxl-3  align-self-center order-2 order-sm-1">
                                <div class="d-flex align-items-center h-100-vh">
                                    <div class="login p-50">
                                        <h1 class="mb-2">Admin</h1>
                                        <p>Welcome back, please login to your account.</p>
                                        <form class="row g-1 p-3 p-md-4" action="{{route('admin.login')}}" method="post">
                                            @csrf
                                            <div class="row">
                                                <div class="col-12">
                                                    @include('admin.inc.validation_message')
                                                    @include('admin.inc.auth_message')
                                                    <div class="form-group">
                                                        <label class="control-label">Email*</label>
                                                        <input type="email" name="email" class="form-control" placeholder="Username" />
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Password*</label>
                                                        <input type="password" name="password" class="form-control" placeholder="Password" />
                                                    </div>
                                                </div>                                                
                                                <div class="col-12 mt-3">
                                                    <button class="btn btn-primary text-uppercase">Sign In</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xxl-9 col-lg-7 bg-gradient o-hidden order-1 order-sm-2">
                                <div class="row align-items-center h-100">
                                    <div class="col-7 mx-auto ">
                                        <img class="img-fluid" src="{{asset('assets/admin/assets/img/bg/login.svg')}}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end login contant-->
        </div>
        <!-- end app-wrap -->
    </div>
    <!-- end app -->

    <!-- plugins -->
    <script src="{{asset('assets/admin/assets/js/vendors.js')}}"></script>
    <!-- custom app -->
    <script src="{{asset('assets/admin/assets/js/app.js')}}"></script>
</body>
</html>