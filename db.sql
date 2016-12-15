USE szzsip;

CREATE TABLE `user` (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(10) NOT NULL,
    firstname VARCHAR(30),
    lastname VARCHAR(30),
    client_id INT(6) UNSIGNED,
    phone VARCHAR(45),
    password_hash VARCHAR(255),
    password_reset_token VARCHAR(255),
    email VARCHAR(50) NOT NULL,
    auth_key VARCHAR(255),
    status enum('active', 'deleted', 'locked'),
    created_at INT(11),
    updated_at INT(11),
    group_id int,
    type enum('admin', 'supervisor','serviceman', 'client')
);

CREATE TABLE `client` (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(255),
    lastname VARCHAR(30),
    phone VARCHAR(45),
    nip VARCHAR(13),
    street VARCHAR(255),
    street_no VARCHAR(10),
    postcode VARCHAR(6),
    city VARCHAR(100),
    email VARCHAR(50) NOT NULL,
    status enum('active', 'deleted', 'locked'),
    created_at INT(11),
    updated_at INT(11),
    type enum('customer', 'company'),
    info VARCHAR(100)
);

CREATE TABLE `group` (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30),
    status enum('active', 'deleted', 'locked'),
    created_at INT(11),
    updated_at INT(11),
    owner_id INT(6) UNSIGNED
);

CREATE TABLE `project` (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    description VARCHAR(500),
    owner_id INT(6) UNSIGNED,
    client_id INT(6) UNSIGNED,
    created_at INT(11),
    updated_at INT(11),
    status enum('new', 'in progress', 'closed')
);

CREATE TABLE `order` (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    owner_id INT(6) UNSIGNED,
    executive_id INT(6) UNSIGNED,
    client_id INT(6) UNSIGNED,
    created_at INT(11),
    updated_at INT(11),
    status enum('new', 'in progress', 'closed'),
    project_id INT(6) UNSIGNED
);

CREATE TABLE `project_order` (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id INT(6) UNSIGNED,
    project_id INT(6) UNSIGNED,
    created_at INT(11),
    updated_at INT(11),
);

CREATE TABLE `task` (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    status enum('active', 'deleted'),
    created_at INT(11),
    updated_at INT(11),
);

CREATE TABLE `order_task` (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id INT(6) UNSIGNED,
    task_id INT(6) UNSIGNED,
    created_at INT(11),
    updated_at INT(11),
);