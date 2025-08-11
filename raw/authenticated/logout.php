<?php
session_start();

session_destroy();

redirectToLoginPage();

function redirectToLoginPage(): void
{
    header('Location: login.php');
    exit;
}
