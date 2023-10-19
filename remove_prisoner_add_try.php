<?php
session_start();

if (isset($_SESSION['prisoner_add_try'])) {
    unset($_SESSION['prisoner_add_try']);
}
?>