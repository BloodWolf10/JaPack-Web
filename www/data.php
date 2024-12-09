<?php
require 'vendor/autoload.php'; // Ensure Composer autoload is included if using dotenv

use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    // Fetch database credentials from environment variables
    $host = getenv('DB_HOST') ?: 'localhost'; // Default to 'localhost' if not set
    $dbname = getenv('DB_NAME') ?: 'japackdb'; // Default database name
    $username = getenv('DB_USER') ?: 'root'; // Default to 'root'
    $password = getenv('DB_PASSWORD') ?: ''; // Default to empty password

    // Display credentials for debugging (only in development; remove in production!)
    echo "DB_USER: " . htmlspecialchars($username, ENT_QUOTES, 'UTF-8') . "<br>";
    echo "DB_PASSWORD: " . htmlspecialchars($password, ENT_QUOTES, 'UTF-8') . "<br>";

    // Create the PDO Data Source Name (DSN)
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

    // Establish PDO connection
    $pdo = new PDO($dsn, $username, $password);

    // Set PDO error mode to exception for better error handling
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Table name
    $table_name = "users";

    // Prepare and execute the query
    $query = "SELECT * FROM `$table_name`"; // Use backticks for table name to avoid SQL keyword conflicts
    $statement = $pdo->prepare($query);
    $statement->execute();

    // Display the results
    echo "<strong>$table_name</strong><br>";
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        echo "<p>" . htmlspecialchars($row['fullname'], ENT_QUOTES, 'UTF-8') . "</p>";
    }

} catch (PDOException $e) {
    // Handle errors gracefully
    echo "Database error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
}
