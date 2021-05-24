<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?php echo csrf_token(); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="<?php echo asset('css/app.css') ?>" rel="stylesheet" type="text/css">
    <title><?php echo config("app.name"); ?></title>
</head>
<body class="login-page">
<div id="app" class="login-wrapper">
    <div class="login-box">
        <div class="logo-main">
            <a href="<?php echo base_url(); ?>"><img src="<?php echo asset("imges/logo-login.png"); ?>" alt="Logo"></a>
        </div>
        <form action="" id="login-form" method="post">
            <?php echo csrf_field() ?>
            <div class="form-group">
                <input type="email" class="form-control form-control-danger" placeholder="Enter email" name="email">
            </div>
            <div class="form-group">
                <input type="password" class="form-control form-control-danger" placeholder="Enter Password" name="password">
            </div>
            <div class="other-actions row">
                <div class="col-6">
                    <div class="checkbox">
                        <label class="c-input c-checkbox">
                            <input type="checkbox" name="remember">
                            <span class="c-indicator"></span>
                            Remember Me
                        </label>
                    </div>
                </div>
                <div class="col-6 text-right">
                    <a href="<?php echo route("reset-password"); ?>" class="forgot-link">Forgot Password?</a>
                </div>
            </div>
            <button class="btn btn-theme btn-block">Login</button>
        </form>
    </div>
</div>
<scrip src="<?php echo asset('js/vendor/bootstrap.js') ?>"></scrip>
</body>
</html>
