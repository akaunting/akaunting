-- Create user with permissions from any host
-- This must run first to create the user before other scripts
CREATE USER IF NOT EXISTS 'akaunting'@'%' IDENTIFIED BY 'akauntingpass';
GRANT ALL PRIVILEGES ON akaunting.* TO 'akaunting'@'%';
FLUSH PRIVILEGES;
