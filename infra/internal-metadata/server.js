// LAB: VULN-SSRF-003 - Internal Mock Metadata Service
// This is a target for SSRF attacks - simulates AWS metadata service

const http = require('http');

const PORT = 8080;

// Mock metadata
const metadata = {
    'instance-id': 'i-mockinstance12345',
    'instance-type': 't2.micro',
    'region': 'us-east-1',
    'ami-id': 'ami-mock12345',
    'hostname': 'ip-172-31-0-1.ec2.internal',
    'local-ipv4': '172.31.0.1',
    'public-ipv4': '54.123.456.789',
    'security-groups': ['default', 'launch-wizard-1'],
    'account-id': '123456789012'
};

const secrets = {
    'access-key-id': 'AKIA_MOCK_ACCESS_KEY_ID',
    'secret-access-key': 'mock_secret_access_key_1234567890',
    'session-token': 'mock_session_token_abc123xyz'
};

const server = http.createServer((req, res) => {
    res.setHeader('Content-Type', 'application/json');
    res.setHeader('X-Internal-Metadata', 'true');
    
    const url = req.url.split('?')[0];
    
    // /api/metadata - main metadata
    if (url === '/api/metadata' || url === '/latest/meta-data/') {
        res.end(JSON.stringify(metadata, null, 2));
    }
    // /api/instance-id
    else if (url === '/api/instance-id' || url === '/latest/meta-data/instance-id') {
        res.end(metadata['instance-id']);
    }
    // /api/secrets - FAKE AWS credentials (for SSRF lab)
    else if (url === '/api/secrets' || url === '/latest/meta-data/iam/security-credentials/') {
        res.end(JSON.stringify(secrets, null, 2));
    }
    // /api/ami-id
    else if (url === '/api/ami-id') {
        res.end(metadata['ami-id']);
    }
    // /health
    else if (url === '/health') {
        res.statusCode = 200;
        res.end('OK');
    }
    // Default - 404
    else {
        res.statusCode = 404;
        res.end(JSON.stringify({ error: 'Not found' }));
    }
});

server.listen(PORT, '0.0.0.0', () => {
    console.log('Internal Metadata Service running on port ' + PORT);
    console.log('WARNING: This is a mock service for SSRF training labs only!');
});
