<?php
require_once __DIR__ . '/../config/config.php';
use App\Core\Database;

try {
    $db = Database::getInstance();
    echo "Adding columns to users table...\n";

    $columns = [
        "username VARCHAR(100) DEFAULT NULL AFTER email",
        "password_hash VARCHAR(255) DEFAULT NULL AFTER username",
        "phone VARCHAR(20) DEFAULT NULL AFTER password_hash",
        "alternate_phone VARCHAR(20) DEFAULT NULL AFTER phone",
        "dob DATE DEFAULT NULL AFTER alternate_phone",
        "gender ENUM('Male', 'Female', 'Other') DEFAULT NULL AFTER dob",
        "address TEXT DEFAULT NULL AFTER gender",
        "city VARCHAR(100) DEFAULT NULL AFTER address",
        "state VARCHAR(100) DEFAULT NULL AFTER city",
        "country VARCHAR(100) DEFAULT NULL AFTER state",
        "pincode VARCHAR(10) DEFAULT NULL AFTER country",
        "latitude DECIMAL(10,7) DEFAULT NULL AFTER pincode",
        "longitude DECIMAL(10,7) DEFAULT NULL AFTER latitude",
        "bio TEXT DEFAULT NULL AFTER longitude",
        "profile_image TEXT DEFAULT NULL AFTER bio",
        "cover_image TEXT DEFAULT NULL AFTER profile_image",
        "occupation VARCHAR(100) DEFAULT NULL AFTER cover_image",
        "is_student TINYINT(1) DEFAULT 0 AFTER occupation",
        "college_name VARCHAR(150) DEFAULT NULL AFTER is_student",
        "college_registration_number VARCHAR(100) DEFAULT NULL AFTER college_name",
        "roll_number VARCHAR(100) DEFAULT NULL AFTER college_registration_number",
        "branch VARCHAR(100) DEFAULT NULL AFTER roll_number",
        "year_of_study VARCHAR(20) DEFAULT NULL AFTER branch",
        "semester VARCHAR(20) DEFAULT NULL AFTER year_of_study",
        "graduation_year INT DEFAULT NULL AFTER semester",
        "linkedin_url TEXT DEFAULT NULL AFTER graduation_year",
        "github_url TEXT DEFAULT NULL AFTER linkedin_url",
        "portfolio_url TEXT DEFAULT NULL AFTER github_url",
        "skills TEXT DEFAULT NULL AFTER portfolio_url",
        "emergency_contact_name VARCHAR(150) DEFAULT NULL AFTER skills",
        "emergency_contact_phone VARCHAR(20) DEFAULT NULL AFTER emergency_contact_name",
        "last_profile_updated_at DATETIME DEFAULT NULL AFTER emergency_contact_phone"
    ];

    foreach ($columns as $col) {
        $colName = explode(' ', $col)[0];
        try {
            // Check if column exists first
            $check = $db->query("SHOW COLUMNS FROM users LIKE '$colName'")->fetch();
            if (!$check) {
                echo "Adding $colName...\n";
                $db->exec("ALTER TABLE users ADD $col");
            } else {
                echo "Column $colName already exists.\n";
            }
        } catch (Exception $e) {
            echo "Error adding $colName: " . $e->getMessage() . "\n";
        }
    }

    echo "Done!\n";
} catch (Exception $e) {
    echo "Fatal Error: " . $e->getMessage() . "\n";
}
