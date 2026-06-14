CREATE DATABASE IF NOT EXISTS reservation_db;
CREATE DATABASE IF NOT EXISTS reservation_db_testing;

GRANT ALL PRIVILEGES ON reservation_db.* TO 'reservation_user'@'%';
GRANT ALL PRIVILEGES ON reservation_db_testing.* TO 'reservation_user'@'%';

FLUSH PRIVILEGES;