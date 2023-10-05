<?php
    function my_autoloader($classe)
    {
        include 'class/'.$classe.'.class.php';
    }
    spl_autoload_register('my_autoloader');
?>