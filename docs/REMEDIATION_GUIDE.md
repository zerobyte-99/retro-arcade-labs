# Retro Arcade Labs - Remediation Guide

## SQL Injection

### Vulnerable Code (VULN-SQLI-001)
```php
$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
$result = mysqli_query($conn, $sql);
```

### Secure Fix
```php
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ? AND password = ?");
mysqli_stmt_bind_param($stmt, "ss", $username, $password);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
```

## XSS

### Vulnerable Code (VULN-XSS-004)
```php
echo $comment['body']; // Raw output
```

### Secure Fix
```php
echo htmlspecialchars($comment['body'], ENT_QUOTES, 'UTF-8');
```

## SSRF

### Vulnerable Code (VULN-SSRF-001)
```php
$content = file_get_contents($url);
```

### Secure Fix
```php
// Validate URL is in allowed list
$allowed_hosts = ['trusted-site.com'];
$parsed = parse_url($url);
if (!in_array($parsed['host'], $allowed_hosts)) {
    die("URL not allowed");
}
// Block internal IPs
$ip = gethostbyname($parsed['host']);
if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
    die("Internal IPs not allowed");
}
$content = file_get_contents($url);
```

## Command Injection

### Vulnerable Code (VULN-RCE-001)
```php
$output = shell_exec($cmd);
```

### Secure Fix
```php
// Use allowlist
$allowed_commands = ['whoami', 'ls', 'date'];
if (!in_array($cmd, $allowed_commands)) {
    die("Command not allowed");
}
$output = shell_exec(escapeshellcmd($cmd));
```

## Mass Assignment

### Vulnerable Code (VULN-MASS-001)
```php
$updates[] = "$key = '" . escape($value) . "'";
```

### Secure Fix
```php
$allowed_fields = ['email', 'display_name'];
foreach ($input as $key => $value) {
    if (in_array($key, $allowed_fields)) {
        $updates[] = "$key = '" . escape($value) . "'";
    }
}
```

## CSRF

### Vulnerable Code (VULN-CSRF-001)
```php
// No validation
```

### Secure Fix
```php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF validation failed");
    }
}
```

## File Upload

### Vulnerable Code (VULN-UPLOAD-002)
```php
$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
if (in_array($extension, ['jpg', 'png'])) { /* allow */ }
```

### Secure Fix
```php
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);
$allowed_mimes = ['image/jpeg', 'image/png', 'image/gif'];
if (!in_array($mime, $allowed_mimes)) {
    die("Invalid file type");
}
// Generate random filename
$filename = bin2hex(random_bytes(16)) . '.' . $extension;
```
