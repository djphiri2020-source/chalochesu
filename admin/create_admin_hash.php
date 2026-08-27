<?php
// This is a temporary utility script to generate a secure password hash.
// You can run this file directly in your browser (e.g., http://localhost/chalochesu/admin/create_admin_hash.php)
// to get a new hash for your admin password.

// IMPORTANT: For security, delete this file after you have used it.

$password_to_hash = 'Bryka257'; // <-- Change this to your desired new password

$options = [
    'cost' => 12, // A higher cost is more secure but slower
];

$password_hash = password_hash($password_to_hash, PASSWORD_DEFAULT, $options);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Password Hash Generator</title>
    <style>
        body { font-family: sans-serif; padding: 2rem; line-height: 1.6; }
        code { background: #eee; padding: 5px; border-radius: 4px; word-break: break-all; }
    </style>
</head>
<body>
    <h1>Password Hash Generator</h1>
    <p>Your new password is: <strong><?php echo htmlspecialchars($password_to_hash); ?></strong></p>
    <p>Copy the following hash and paste it into the `password_hash` column for your 'admin' user in the `users` table using phpMyAdmin:</p>
    <code><?php echo $password_hash; ?></code>
</body>
</html>