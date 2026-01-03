#!/bin/bash
# Fix MySQL permissions for Docker network access
mysql -uroot -prootpassword <<EOF
CREATE USER IF NOT EXISTS 'akaunting'@'%' IDENTIFIED BY 'akauntingpass';
GRANT ALL PRIVILEGES ON akaunting.* TO 'akaunting'@'%';
FLUSH PRIVILEGES;
SELECT User, Host FROM mysql.user WHERE User='akaunting';
EOF


