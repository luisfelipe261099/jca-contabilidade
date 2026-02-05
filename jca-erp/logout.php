<?php
/**
 * JCA ERP - Logout
 * 
 * @package JCA_ERP
 * @version 1.0.0
 */

session_start();
session_destroy();

header("Location: index.php");
exit();

