<?php
session_start(); // open de bestaande sessie
session_unset(); // maak alle sessie-variabelen leeg
session_destroy(); // vernietig de sessie helemaal
header('Location: index.php'); // stuur terug naar de homepagina
exit; // stop de rest van het script
