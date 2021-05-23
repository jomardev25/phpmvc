<h1>Login</h1>

<form action="<?php echo route('login/post'); ?>" method="POST">
    <?php echo csrf_field(); ?>

    <input type="text" name="username"/>
    <input type="text" name="password" />
    <button type="submit">Login</button>
</form>