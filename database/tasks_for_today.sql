CREATE DATABASE IF NOT EXISTS tasks_for_today
    CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE tasks_for_today;

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pending',
    task_date DATE NOT NULL,
    created_at DATETIME NOT NULL
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at DATETIME NOT NULL
);

INSERT INTO tasks (title, status, task_date, created_at) VALUES
    ('Review project requirements', 'completed', CURDATE() - INTERVAL 2 DAY, NOW()),
    ('Prepare meeting notes', 'completed', CURDATE() - INTERVAL 2 DAY, NOW()),
    ('Update task estimates', 'completed', CURDATE() - INTERVAL 1 DAY, NOW()),
    ('Check pending messages', 'pending', CURDATE(), NOW()),
    ('Finish the website wireframe', 'in_progress', CURDATE(), NOW()),
    ('Test the task dashboard', 'pending', CURDATE(), NOW()),
    ('Share a progress update', 'pending', CURDATE(), NOW()),
    ('Draft next week''s plan', 'pending', CURDATE() + INTERVAL 1 DAY, NOW()),
    ('Organize team files', 'pending', CURDATE() + INTERVAL 1 DAY, NOW());

INSERT INTO users (username, full_name, email, created_at) VALUES
    ('johnhenrichdoroteo-ui', 'John Henrich Doroteo', 'johnhenrichdoroteo@gmail.com', NOW());
