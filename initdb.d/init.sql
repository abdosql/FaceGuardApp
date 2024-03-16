-- Grant privileges
GRANT CREATE ON *.* TO 'user'@'%';

-- Create databases
CREATE DATABASE IF NOT EXISTS `authManagement`;
CREATE DATABASE IF NOT EXISTS `studentManagement`;
CREATE DATABASE IF NOT EXISTS `teacherManagement`;
CREATE DATABASE IF NOT EXISTS `timeCourseManagement`;
CREATE DATABASE IF NOT EXISTS `absenceManagement`;
CREATE DATABASE IF NOT EXISTS `alertsManagement`;

-- Grant privileges to user
GRANT ALL PRIVILEGES ON `authManagement`.* TO 'user'@'%';
GRANT ALL PRIVILEGES ON `studentManagement`.* TO 'user'@'%';
GRANT ALL PRIVILEGES ON `teacherManagement`.* TO 'user'@'%';
GRANT ALL PRIVILEGES ON `timeCourseManagement`.* TO 'user'@'%';
GRANT ALL PRIVILEGES ON `absenceManagement`.* TO 'user'@'%';
GRANT ALL PRIVILEGES ON `alertsManagement`.* TO 'user'@'%';

-- Flush privileges
FLUSH PRIVILEGES;
