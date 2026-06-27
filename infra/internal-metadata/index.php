<?php
error_reporting(0);

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

header('Content-Type: application/json');
header('X-Internal-Service: metadata-mock');

function json_response($data, $status = 200) {
    http_response_code($status);
    echo json_encode($data);
    exit;
}

function get_mock_aws_metadata() {
    return [
        'Arn' => 'arn:aws:ec2:us-east-1:123456789012:instance/i-0abcdef1234567890',
        'InstanceId' => 'i-0abcdef1234567890',
        'InstanceType' => 't2.micro',
        'Region' => 'us-east-1',
        'AvailabilityZone' => 'us-east-1a',
        'PrivateIp' => '172.28.0.100',
        'PublicIp' => '203.0.113.50',
        'AccountId' => '123456789012',
        'Hostname' => 'ip-172-28-0-100.ec2.internal',
        'LocalHostname' => 'ip-172-28-0-100.ec2.internal',
        'Mac' => '02:42:ac:1c:00:0a',
        'NetworkInterfaces' => [
            [
                'Mac' => '02:42:ac:1c:00:0a',
                'OwnerId' => '123456789012',
                'SubnetId' => 'subnet-0a1b2c3d4e5f6g7h8',
                'VpcId' => 'vpc-9h8g7f6e5d4c3b2a1',
                'PrivateIp' => '172.28.0.100'
            ]
        ],
        'Placement' => [
            'AvailabilityZone' => 'us-east-1a',
            'Region' => 'us-east-1'
        ],
        'SecurityGroups' => [
            ['GroupName' => 'default', 'GroupId' => 'sg-0abcdef1234567890']
        ],
        'Tags' => [
            ['Key' => 'Name', 'Value' => 'retro-arcade-instance']
        ]
    ];
}

function get_mock_aws_credentials() {
    return [
        'AccessKeyId' => 'AKIAIOSFODNN7EXAMPLE',
        'SecretAccessKey' => 'wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY',
        'Token' => 'FwoGZXIvYXdzECYaDPABCDEFexample1234567890',
        'Expiration' => '2026-06-26T20:00:00Z'
    ];
}

function get_mock_aws_secrets() {
    return [
        'api_key' => 'DEMO_sk_live_51H7xJZvKm7abcdefghijklmnopqrstuvwxyz',
        'payment_gateway_key' => 'DEMO_pk_live_51H7xJZvKm7abcdefghijklmnopqrstuvwxyz',
        'database_password' => 'DEMO_prod_db_password_12345',
        'jwt_secret' => 'DEMO_super_secret_jwt_key_production_xyz789',
        'encryption_key' => 'DEMO_enc_key_32_bytes_long_secret_abc123',
        'webhook_secret' => 'DEMO_whsec_abcdefghijklmnopqrstuvwxyz123456'
    ];
}

function get_mock_kubernetes_info() {
    return [
        'pod_name' => 'retro-arcade-app-7d8f9b6c5-x2j9k',
        'pod_namespace' => 'default',
        'pod_ip' => '172.28.0.50',
        'service_account' => 'default',
        'node_name' => 'minikube',
        'host_ip' => '172.28.0.1'
    ];
}

if ($path === '/api/health' || $path === '/health') {
    json_response(['status' => 'ok', 'service' => 'internal-metadata']);
}

if ($path === '/api/metadata' || $path === '/metadata') {
    if ($method === 'GET') {
        json_response(get_mock_aws_metadata());
    }
    json_response(['error' => 'Method not allowed'], 405);
}

if ($path === '/api/instance-id' || $path === '/instance-id') {
    json_response(['InstanceId' => 'i-0abcdef1234567890']);
}

if ($path === '/api/secrets' || $path === '/secrets') {
    json_response(get_mock_aws_secrets());
}

if ($path === '/api/credentials' || $path === '/credentials') {
    json_response(get_mock_aws_credentials());
}

if ($path === '/api/aws' || $path === '/aws') {
    json_response([
        'metadata' => get_mock_aws_metadata(),
        'credentials' => get_mock_aws_credentials(),
        'secrets' => get_mock_aws_secrets()
    ]);
}

if ($path === '/api/kubernetes' || $path === '/kubernetes') {
    json_response(get_mock_kubernetes_info());
}

if ($path === '/api/internal-services' || $path === '/internal-services') {
    json_response([
        'mysql' => 'mysql://retro_arcade:***@mysql:3306/retro_arcade',
        'redis' => 'redis://redis:6379',
        'php_app' => 'http://php-app:9000',
        'smtp' => 'smtp://mock-mail:11025'
    ]);
}

if ($path === '/api/debug' || $path === '/debug') {
    json_response([
        'server' => [
            'request_uri' => $_SERVER['REQUEST_URI'] ?? '',
            'request_method' => $_SERVER['REQUEST_METHOD'] ?? '',
            'remote_addr' => $_SERVER['REMOTE_ADDR'] ?? '',
            'http_host' => $_SERVER['HTTP_HOST'] ?? '',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
        ]
    ]);
}

json_response([
    'service' => 'internal-metadata-mock',
    'version' => '1.0.0',
    'description' => 'Mock AWS/Kubernetes metadata service for SSRF testing labs',
    'endpoints' => [
        '/api/health',
        '/api/metadata',
        '/api/instance-id',
        '/api/secrets',
        '/api/credentials',
        '/api/aws',
        '/api/kubernetes',
        '/api/internal-services',
        '/api/debug'
    ]
]);