<?php
echo 'Not logged in';
echo '<br/>';
echo 'user/login';
echo '<br/>';
?>
<form method="post" action="<?php echo base_url();?>user/check_login">
    Email: <input type="text" name="email"><br/>
    Password: <input type="password" name="password"><br/>
    <button type="reset">Reset</button> &nbsp;
    <button type="submit">Login</button><br/>
</form>