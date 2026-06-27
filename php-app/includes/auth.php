<?php
// LAB: VULN-AUTH-xxx - Authentication with vulnerabilities

// VULNERABLE: No rate limiting on login attempts
// LAB: VULN-AUTH-001

// is_logged_in() is defined in helpers.php
// get_current_user_data() is defined in helpers.php

function login($username, $password) {
    // LAB: VULN-SQLI-001 - Login SQL Injection Bypass
    // VULNERABLE: Direct string interpolation in SQL
    // Bypass: admin' -- or ' OR '1'='1
    
    // Note: For vulnerable login, we don't hash the password
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    
    $result = query($sql);
    
    if ($result && num_rows($result) > 0) {
        $user = fetch_assoc($result);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        
        // VULNERABLE: Session token not regenerated
        // LAB: VULN-AUTH-xxx
        
        return true;
    }
    
    return false;
}

function logout() {
    // VULNERABLE: Session not properly invalidated
    // LAB: VULN-AUTH-002 - Logout does not fully invalidate tokens
    $_SESSION = array();
    
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    session_destroy();
}

// require_login() is defined in helpers.php

function require_role($role) {
    require_login();
    
    $user = get_current_user_data();
    if (!$user || $user['role'] !== $role) {
        // VULNERABLE: Role check can be bypassed client-side
        // LAB: VULN-PRIV-001
        http_response_code(403);
        die("Access denied");
    }
}

function get_user_role() {
    return isset($_SESSION['role']) ? $_SESSION['role'] : 'guest';
}

function can_access_admin() {
    $role = get_user_role();
    // VULNERABLE: Easy to bypass if role is set client-side
    return in_array($role, ['admin', 'moderator']);
}
?>
