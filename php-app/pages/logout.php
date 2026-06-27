<?php
// VULNERABLE: Session not fully invalidated
// LAB: VULN-AUTH-002
require_once __DIR__ . '/../includes/common.php';
logout();
redirect('/pages/index.php');
?>
