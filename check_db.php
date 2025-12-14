<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=eventu', 'root', '');
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    print_r($tables);
    
    if (in_array('migrations', $tables)) {
        echo "\nMigrations table content:\n";
        $stmt = $pdo->query("SELECT * FROM migrations");
        print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
