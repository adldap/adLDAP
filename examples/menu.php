<?php
/**
 * @mainpage  Demonstration of Doxygen on the adLDAP sample project
 *
 *            This section is basically html code to place on the first (<i>main</i>) page.
 */

session_start();
?>
<html>
<head>
<title>adLDAP authentication</title>
</head>

<body>

<p>If you called authenticate.php and you are redirected to this page, you successfully authenticated against Active Directory with your credentials.</p>

User Details for <?php $_SESSION['username']; ?><br />
<pre>
<?php
print_r($_SESSION['userinfo']);
?>
</pre>

</body>
</html>
