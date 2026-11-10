<?php
// ======================================================
// CONFIGURATION
// ======================================================
require __DIR__ . '/vendor/autoload.php';
// Never show PHP errors to visitors; log them instead.
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
ini_set('log_errors', '1');
error_reporting(E_ALL);

// ======================================================
// LOAD ENVIRONMENT VARIABLES
// ======================================================
function loadEnv(string $filePath): void {
    if (!is_readable($filePath)) return;

    foreach (file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) continue;

        [$name, $value] = array_pad(explode('=', $line, 2), 2, '');
        $name = trim($name);
        $value = trim($value);

        // Real environment variables (e.g. set by the host) win over the file.
        if ($name && getenv($name) === false && !isset($_ENV[$name])) {
            putenv("$name=$value");
            $_ENV[$name] = $_SERVER[$name] = $value;
        }
    }
}

// env.txt lives one level ABOVE the web root (next to public_html), so it can never be downloaded.
loadEnv(dirname(__DIR__) . '/env.txt');

// ======================================================
// SPAM TRAP (honeypot)
// ======================================================
// The "vk_hp" field is hidden from people, so only bots fill it in.
// Pretend it worked so the bot moves on, but send nothing.
if (trim((string)($_POST['vk_hp'] ?? '')) !== '') {
    jsonResponse(true, 'Thank you! Your message has been sent successfully.');
}

// ======================================================
// HELPER FUNCTIONS
// ======================================================
function jsonResponse(bool $success, string $message, array $extra = []): void {
    header('Content-Type: application/json');
    echo json_encode(array_merge(['success' => $success, 'message' => $message], $extra));
    exit;
}

function sanitize(string $input): string {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function isValidPhone(string $phone): bool {
    return preg_match('/^[0-9]{10}$/', $phone);
}

// ======================================================
// INPUT VALIDATION
// ======================================================
$inputs = [
    'name' => sanitize($_POST['name'] ?? ''),
    'email' => sanitize($_POST['email'] ?? ''),
    'phone' => sanitize($_POST['phone'] ?? ''),
    'service' => sanitize($_POST['service'] ?? ''),
    'message' => sanitize($_POST['message'] ?? ''),
    'specification_download' => !empty($_POST['specification_download']),
];

// Required fields
foreach (['name', 'email', 'phone', 'message'] as $field) {
    if (!$inputs[$field]) {
        jsonResponse(false, 'All fields are required. Please fill in all the information.');
    }
}

// Email validation
if (!filter_var($inputs['email'], FILTER_VALIDATE_EMAIL)) {
    jsonResponse(false, 'Please enter a valid email address.');
}

// Phone validation
if (!isValidPhone($inputs['phone'])) {
    jsonResponse(false, 'Please enter a valid 10-digit phone number.');
}

// ======================================================
// PREPARE EMAIL
// ======================================================
$resendApiKey = getenv('RESEND_API_KEY');
if (!$resendApiKey) {
    error_log("Missing RESEND_API_KEY in environment");
    jsonResponse(false, 'Server configuration error. Please try again later.');
}

$subject = $inputs['specification_download']
    ? "Specification Downloaded"
    : "New Contact Form Submission";

$htmlMessage = "
<h3>Hi Veeru Kannammal Constructions,</h3>
<p>" . ($inputs['specification_download']
        ? "Someone has downloaded the specification from the website."
        : "You have received a new contact form submission.") . "</p>
<p>
<strong>Name:</strong> {$inputs['name']}<br>
<strong>Email:</strong> {$inputs['email']}<br>
<strong>Phone:</strong> {$inputs['phone']}<br>";

if ($inputs['service']) {
    $htmlMessage .= "<strong>Service Package:</strong> {$inputs['service']}<br>";
}

$htmlMessage .= "<br><strong>Message:</strong><br>" . nl2br($inputs['message']) . "<br></p>";

// ======================================================
// SEND EMAIL VIA RESEND API
// ======================================================
$resend = Resend::client($resendApiKey);

    $result = $resend->emails->send([
        'from' => 'VK Construction <contact@veerukannammal.in>',
        'to' => ['contact@veerukannammal.in'],
        'subject' => $subject,
        'html' => $htmlMessage,
        'tags' => [
            [
                'name' => 'category',
                'value' => 'confirm_email',
            ]
        ]
    ]);

// ======================================================
// HANDLE RESPONSE
// ======================================================
if ($result && isset($result['id'])) {
    $response = [
        'success' => true,
        'message' => $inputs['specification_download']
            ? 'Thank you! Your details have been submitted.'
            : 'Thank you! Your message has been sent successfully.'
    ];

    if ($inputs['specification_download']) {
        $response['pdf'] = 'assets/files/veeru-kannammal-specification.pdf';
    }

    echo json_encode($response);
    exit;
}

// Log API failure for debugging
error_log("Resend API failed. Response: " . json_encode($result));
$errorResponse = json_decode($result, true);
if (isset($errorResponse['message'])) {
    error_log("Resend API Error: " . $errorResponse['message']);
}

jsonResponse(false, 'Sorry, there was an error sending your message. Please try again later.');
?>
