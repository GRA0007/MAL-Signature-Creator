<?php
setcookie("MALSIG_LOGIN", "nothing", 1, '/');
header("Location: ../");
die('You have been logged out, but something went wrong while trying to redirect you. <a href="../">Click here</a> to return home.');