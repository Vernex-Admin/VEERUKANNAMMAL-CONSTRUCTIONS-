
<?php
// Return a 1x1 transparent PNG
header('Content-Type: image/png');
header('Content-Length: 67');
echo base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChAGA8nD5RQAAAABJRU5ErkJggg==');
exit;
?>
