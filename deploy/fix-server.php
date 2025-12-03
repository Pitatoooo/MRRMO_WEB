<?php
// Server diagnostic and fix script
echo "<h1>MDRRMO Server Diagnostic</h1>";

// Check PHP version
echo "<h2>PHP Information:</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "<br>";
echo "Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . "<br>";

// Check if we can write files
echo "<h2>File System Check:</h2>";
$testFile = 'test-write.txt';
if (file_put_contents($testFile, 'test') !== false) {
    echo "✅ Can write files<br>";
    unlink($testFile);
} else {
    echo "❌ Cannot write files<br>";
}

// Check required extensions
echo "<h2>PHP Extensions:</h2>";
$required = ['pdo', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json', 'bcmath'];
foreach ($required as $ext) {
    if (extension_loaded($ext)) {
        echo "✅ $ext<br>";
    } else {
        echo "❌ $ext (missing)<br>";
    }
}

// Check directory permissions
echo "<h2>Directory Permissions:</h2>";
$dirs = ['.', 'storage', 'bootstrap/cache'];
foreach ($dirs as $dir) {
    if (is_dir($dir)) {
        $perms = substr(sprintf('%o', fileperms($dir)), -4);
        echo "📁 $dir: $perms<br>";
    } else {
        echo "❌ $dir: not found<br>";
    }
}

echo "<h2>Environment Variables:</h2>";
echo "APP_ENV: " . (getenv('APP_ENV') ?: 'Not set') . "<br>";
echo "APP_DEBUG: " . (getenv('APP_DEBUG') ?: 'Not set') . "<br>";

echo "<h2>Recommendations:</h2>";
echo "1. Ensure PHP 8.1+ is installed<br>";
echo "2. Check file permissions (755 for directories, 644 for files)<br>";
echo "3. Verify .env file exists with proper configuration<br>";
echo "4. Run: composer install --no-dev --optimize-autoloader<br>";
echo "5. Run: php artisan config:cache<br>";
?>
