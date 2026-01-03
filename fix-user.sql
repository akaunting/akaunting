CREATE USER IF NOT EXISTS 'akaunting'@'%' IDENTIFIED BY 'akauntingpass';
GRANT ALL PRIVILEGES ON akaunting.* TO 'akaunting'@'%';
FLUSH PRIVILEGES;


