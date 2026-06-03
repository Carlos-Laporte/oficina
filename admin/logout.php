<?php
// admin/logout.php

session_start();
session_destroy();
echo 'Logout realizado.';
