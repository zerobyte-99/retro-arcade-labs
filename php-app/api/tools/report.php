<?php
// LAB: VULN-RCE-001 - Command Injection in Report Generator
header('Content-Type: text/plain');
require_once __DIR__ . '/../../includes/common.php';

require_role('admin');

$type = isset($_GET['type']) ? $_GET['type'] : 'summary';
$param = isset($_GET['param']) ? $_GET['param'] : '';

// VULNERABLE: Command injection via parameters
// LAB: VULN-RCE-001
// Payloads:
// - ?type=test;cat /etc/passwd
// - ?type=test%26%26whoami
// - ?type=test%0Acat%20/etc/passwd

// VULNERABLE: No input sanitization, direct command execution
$cmd = "echo 'Report: $type'; echo 'Parameter: $param'; date;";

// Execute and display output
$output = shell_exec($cmd);

echo "=== Retro Arcade Labs Report ===\n";
echo "Type: $type\n";
echo "Parameter: $param\n";
echo "Generated: " . date('Y-m-d H:i:s') . "\n";
echo "================================\n\n";
echo "Command Output:\n";
echo $output;
?>
