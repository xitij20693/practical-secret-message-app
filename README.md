# secret-message-app

Database Setup:Create a MySQL database named secret_messages.
Run the following SQL to create the messages table:

```
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    text TEXT NOT NULL,
    recipient VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    isDeleted INT,
    expiry DATE NOT NULL
);
```

Configure config/database.php with your MySQL credentials.Navigate to index.php and run it in your PHP environment.


# practical-secret-message-app
# practical-secret-message-app
