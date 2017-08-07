<!doctype html>
<html lang="en">
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="pPvK1uEtv1ha3NT9aE1q0thoZL1Q46KHS0jf07Y7">

    <title>Tinkerbell </title>

    <!-- Styles -->
    <link href="assets/css/app.css" rel="stylesheet">
</head>    <body>

    <header id="top" class="header">
        <div class="text-vertical-center">
            <div class="col-md-2 col-sm-offset-5">
                <form name="loginForm" method="post" action="login-submit.php">
                    <img src="assets/img/tinkerbell.png" class="tinkerbell-logo" alt="Shadow image of Tinkerbell" />
                    <h1>Tinkerbell</h1>
                    <br/>

                    <div class="form-group">
                        <input class="form-control" name="username" placeholder="username" />
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="password" name="password" placeholder="password" />
                    </div>
                    <br>
                    <a href="javascript:loginSubmit()" class="btn btn-dark btn-lg">Login</a>
                </form>
            </div>
        </div>
    </header>

    <script type="text/javascript">
    function loginSubmit() {
        document.loginForm.submit();
    }
    </script>
    </body>
</html>