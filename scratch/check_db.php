<?php
require_once __DIR__ . '/../config/config.php';
use App\Core\Database;

try {
    $db = Database::getInstance();
    $res = $db->query("DESCRIBE users");
    print_r($res->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    echo $e->getMessage();
}
