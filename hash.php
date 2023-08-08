<?php

$pw = password_hash('test1', PASSWORD_DEFAULT);
echo $pw;
echo "<br>";
var_dump(password_verify('test1', $pw));

?>

