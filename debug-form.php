<?php

// Debug version to troubleshoot the contact form issue

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Contact Form Debug</h2>";

// Load environment variables from .env file
function loadEnv($file) {
    if (!file_exists($file)) {
        echo "❌ Environment file not found: $file<br>";
        return false;
    }
    
    echo "✅ Environment file found: $file<br>";
    
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue; // Skip comments
        }
        
        if (strpos($line, '=') === false) {
            continue; // Skip invalid lines
        }
        
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        
        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
    return true;
}

// Load .env file
$envLoaded = loadEnv(__DIR__ . '/env.txt');

// Check if this is a POST request
if ($_POST) {
    echo "<h3>POST Data Received:</h3>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    // Validate and sanitize input
    $name    = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email   = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone   = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $service = isset($_POST['service']) ? trim($_POST['service']) : '';
    $msg     = isset($_POST['message']) ? trim($_POST['message']) : '';

    echo "<h3>Processed Data:</h3>";
    echo "Name: " . htmlspecialchars($name) . "<br>";
    echo "Email: " . htmlspecialchars($email) . "<br>";
    echo "Phone: " . htmlspecialchars($phone) . "<br>";
    echo "Service: " . htmlspecialchars($service) . "<br>";
    echo "Message: " . htmlspecialchars($msg) . "<br>";

    // Basic validation
    if (empty($name) || empty($email) || empty($phone) || empty($service) || empty($msg)) {
        echo "<h3>❌ Validation Failed:</h3>";
        echo "Missing fields:<br>";
        if (empty($name)) echo "- Name<br>";
        if (empty($email)) echo "- Email<br>";
        if (empty($phone)) echo "- Phone<br>";
        if (empty($service)) echo "- Service<br>";
        if (empty($msg)) echo "- Message<br>";
    } else {
        echo "<h3>✅ Validation Passed</h3>";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<h3>❌ Email validation failed</h3>";
    } else {
        echo "<h3>✅ Email validation passed</h3>";
    }

    // Check Resend API key
    $resend_api_key = getenv('RESEND_API_KEY');
    if ($resend_api_key) {
        echo "<h3>✅ Resend API Key Found</h3>";
        echo "Key starts with: " . substr($resend_api_key, 0, 10) . "...<br>";
    } else {
        echo "<h3>❌ Resend API Key NOT Found</h3>";
        echo "Environment variables:<br>";
        echo "<pre>";
        print_r($_ENV);
        echo "</pre>";
    }

    // Test cURL
    if (!function_exists('curl_init')) {
        echo "<h3>❌ cURL not available</h3>";
    } else {
        echo "<h3>✅ cURL available</h3>";
        
        if ($resend_api_key && !empty($name) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<h3>Testing Resend API...</h3>";
            
            $subject = "Debug Test - Contact Form Submission";

            $html_message = "
                <h3>Hi Veeru Kannammal Constructions,</h3><br>
                <p>This is a DEBUG test email. Below are the details:</p><br>
                <strong>Name:</strong> " . htmlspecialchars($name) . "<br>
                <strong>Email:</strong> " . htmlspecialchars($email) . "<br>
                <strong>Phone:</strong> " . htmlspecialchars($phone) . "<br>
                <strong>Service Package:</strong> " . htmlspecialchars($service) . "<br><br>
                <strong>Message:</strong><br>" . nl2br(htmlspecialchars($msg)) . "
            ";

            // Prepare data for Resend API
            $data = [
                'from' => 'VK Construction <contact@veerukannammal.in>',
                'to' => ['contact@veerukannammal.in'],
                'subject' => $subject,
                'html' => $html_message
            ];

            echo "Data being sent to Resend:<br>";
            echo "<pre>";
            print_r($data);
            echo "</pre>";

            // Send email via Resend API
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.resend.com/emails');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $resend_api_key,
                'Content-Type: application/json'
            ]);

            $result = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curl_error = curl_error($ch);
            curl_close($ch);

            echo "<h3>API Response:</h3>";
            echo "HTTP Code: $http_code<br>";
            echo "cURL Error: " . ($curl_error ?: 'None') . "<br>";
            echo "Response: <pre>" . htmlspecialchars($result) . "</pre>";

            if ($http_code == 200) {
                echo "<h3>✅ Email sent successfully!</h3>";
            } else {
                echo "<h3>❌ Email sending failed</h3>";
            }
        }
    }
} else {
    echo "<h3>No POST data received</h3>";
    echo "You can test the form by submitting data from the contact page.";
}

echo '<br><br><a href="contact.php">← Back to Contact Form</a>';

?>
