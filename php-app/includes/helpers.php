<?php
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

function sanitize($input) {
    if (is_array($input)) {
        return array_map('sanitize', $input);
    }
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

function redirect($url) {
    if (headers_sent()) {
        echo "<script>window.location.href='" . addslashes($url) . "';</script>";
        exit;
    }
    header("Location: $url");
    exit;
}

function json_response($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function render($template, $data = []) {
    extract($data);
    include __DIR__ . '/../pages/' . $template . '.php';
}

function paginate($page, $per_page, $total) {
    $total_pages = ceil($total / $per_page);
    $page = max(1, min($page, $total_pages));
    $offset = ($page - 1) * $per_page;
    return [
        'page' => $page,
        'per_page' => $per_page,
        'total' => $total,
        'total_pages' => $total_pages,
        'offset' => $offset
    ];
}

function is_logged_in() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function get_current_user_data() {
    if (!is_logged_in()) {
        return null;
    }
    global $db;
    $user_id = (int)$_SESSION['user_id'];
    $result = query("SELECT * FROM users WHERE id = $user_id");
    return fetch_assoc($result);
}

function require_login() {
    if (!is_logged_in()) {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        redirect('/pages/auth/login.php');
    }
}

function has_role($role) {
    $user = get_current_user_data();
    if (!$user) return false;
    return strtolower($user['role']) === strtolower($role);
}

function is_admin() {
    return has_role('admin');
}

function is_moderator() {
    return has_role('moderator') || is_admin();
}
?>