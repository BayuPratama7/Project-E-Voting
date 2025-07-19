<!DOCTYPE html>
<html>
<head>
    <title>Halaman Login Client</title>
</head>
<body>
    <h1>Login ke REST Server</h1>

    <?php echo form_open('login/aksi_login');?>

    <p>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="admin" />
    </p>
    <p>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" value="1234" />
    </p>
    <p>
        <input type="submit" value="Login" />
    </p>

    <?php echo form_close();?>
</body>
</html>
