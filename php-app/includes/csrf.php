<?php
// LAB: VULN-CSRF-001 - CSRF tokens generated but NOT validated

// Generate CSRF token
function csrf_generate_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// VULNERABLE: This function exists but is NEVER called to validate
// The form helper includes the token, but no validation happens
function csrf_validate($token) {
    // This SHOULD validate, but it doesn't get called!
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

// Include hidden token field in forms
function csrf_token_field() {
    $token = csrf_generate_token();
    // Token is generated and included in forms, but NEVER validated on submission
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

// VULNERABLE: No CSRF validation on any POST request
// To make this vulnerable, we simply never call csrf_validate()
?>
