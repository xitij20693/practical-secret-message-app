<?php

require_once ('src/Database.php');
require_once ('src/Message.php');

// Create a database connection
$db = new Database();
$connection = $db->getConnection();

// Create a Message instance
$message = new Message($connection);

// Create encrypted message
$messageId = $message->createMessage('Hello, this is a secret message.', 'recipient@example.com', '2024-07-31');

echo "Message created with ID: $messageId<br>";

// Decrpt message
$decryptionKey = '767d05a6-0150-4acf-994e-263b0f17b6cb';

$decryptedMessage = $message->getMessageByIdentifier($messageId, $decryptionKey);

echo "Decrypted message: $decryptedMessage";