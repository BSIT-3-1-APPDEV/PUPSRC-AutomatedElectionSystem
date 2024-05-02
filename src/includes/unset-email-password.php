<?php
// Unset session variable of email and passwrd
function unsetSessionVar() {
    unset($_SESSION['email']);
    unset($_SESSION['password']);
}
