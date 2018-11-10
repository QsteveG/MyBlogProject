<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once './ExtraVals.php';

session_start();
session_destroy();

header("Location: ../index.php?logged_user=".ExtraVals::USER_LOGGED_OUT);
exit();

