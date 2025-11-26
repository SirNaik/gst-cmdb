-- БД: cmdbbd
-- Сервер, порт, логин указаны выше

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    role ENUM('admin', 'operator', 'guest') NOT NULL DEFAULT 'guest',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE equipment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('server', 'pc', 'network_device') NOT NULL,
    name VARCHAR(100) NOT NULL,
    model VARCHAR(100),
    serial_number VARCHAR(100),
    inventory_number VARCHAR(100),
    cpu VARCHAR(255),
    ram VARCHAR(100),
    hdd VARCHAR(100),
    os VARCHAR(100),
    start_date DATE,
    warranty DATE,
    status ENUM('active', 'reserve', 'decommissioned') NOT NULL DEFAULT 'active'
);

CREATE TABLE software (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    vendor VARCHAR(100),
    type ENUM('os', 'app', 'dbms') NOT NULL
);

CREATE TABLE licenses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    software_id INT NOT NULL,
    license_key VARCHAR(255) NOT NULL,
    license_type ENUM('perpetual', 'subscription') NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    price DECIMAL(12,2),
    supplier VARCHAR(255),
    status ENUM('active', 'expiring', 'expired') DEFAULT 'active',
    FOREIGN KEY (software_id) REFERENCES software(id)
);

CREATE TABLE equipment_software (
    id INT AUTO_INCREMENT PRIMARY KEY,
    equipment_id INT NOT NULL,
    software_id INT NOT NULL,
    FOREIGN KEY (equipment_id) REFERENCES equipment(id),
    FOREIGN KEY (software_id) REFERENCES software(id)
);

CREATE TABLE logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(255) NOT NULL,
    details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Для уведомлений:
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    notify_emails VARCHAR(500),
    notify_period ENUM('30', '60', '90') DEFAULT '30'
);
