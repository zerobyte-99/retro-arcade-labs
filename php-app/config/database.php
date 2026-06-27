<?php
// LAB: VULN-SQLI-xxx - Vulnerable database connection
// Using mysqli without prepared statements (intentionally vulnerable)

$db_host = getenv('DB_HOST') ?: 'localhost';
$db_user = getenv('DB_USERNAME') ?: 'retro_arcade';
$db_pass = getenv('DB_PASSWORD') ?: 'retro_password';
$db_name = getenv('DB_DATABASE') ?: 'retro_arcade';

// VULNERABLE: Direct mysqli connection
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$conn) {
    // VULNERABLE: Verbose error messages
    die("Database connection failed: " . mysqli_connect_error());
}

function query($sql) {
    global $conn;
    // VULNERABLE: Direct query without prepared statements
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        error_log("SQL Error: " . mysqli_error($conn) . " - SQL: " . $sql);
        return false;
    }
    return $result;
}

function fetch_array($result) {
    return mysqli_fetch_array($result);
}

function fetch_assoc($result) {
    return mysqli_fetch_assoc($result);
}

function num_rows($result) {
    return mysqli_num_rows($result);
}

function escape($str) {
    global $conn;
    // VULNERABLE: Using this creates false sense of security
    return mysqli_real_escape_string($conn, $str);
}

function get_last_id() {
    global $conn;
    return mysqli_insert_id($conn);
}
?>
