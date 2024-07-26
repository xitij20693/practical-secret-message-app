<?php

class Message
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function createMessage($text, $recipient, $expiry)
    {
        // Encrypt the message
        $encryptedText = $this->encryptMessage($text);

        // Save message to database
        $stmt = $this->db->prepare("INSERT INTO messages (text, recipient, created_at, expiry) VALUES (?, ?, NOW(), ?)");
        $stmt->execute([$encryptedText, $recipient, $expiry]);

        return $this->db->lastInsertId();
    }

    public function getMessageByIdentifier($id, $decryptionKey)
    {
        $stmt = $this->db->prepare("SELECT text FROM messages WHERE id = ?");
        $stmt->execute([$id]);
        $encryptedText = $stmt->fetchColumn();

        // Decrypt the message
        $decryptedText = $this->decryptMessage($encryptedText, $decryptionKey);

        // Delete message after it's read
        $this->deleteMessage($id);

        return $decryptedText;
    }

    protected function encryptMessage($text)
    {
        $key = openssl_random_pseudo_bytes(32);
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encryptedText = openssl_encrypt($text, 'aes-256-cbc', $key, 0, $iv);

        return base64_encode($encryptedText . '::' . $iv . '::' . $key);
    }

    protected function decryptMessage($encryptedText, $decryptionKey)
    {
        list($encryptedText, $iv, $key) = explode('::', base64_decode($encryptedText));
        return openssl_decrypt($encryptedText, 'aes-256-cbc', $key, 0, $iv);
    }

    protected function deleteMessage($id)
    { 
        // we can delete it by DELETE statement but as for reference we are updating isDeleted flag to 1
        $stmt = $this->db->prepare("UPDATE messages SET isDeleted = 1 WHERE id = ?");
        $stmt->execute([$id]);
    }
}