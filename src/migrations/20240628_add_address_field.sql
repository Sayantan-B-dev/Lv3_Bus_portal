-- Add address field to users table
ALTER TABLE users ADD COLUMN IF NOT EXISTS address TEXT AFTER phone;
