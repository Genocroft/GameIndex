CREATE TABLE sidebar (
    id INT AUTO_INCREMENT PRIMARY KEY, 
    label VARCHAR(255) NOT NULL
);

INSERT INTO sidebar(label) VALUES
('Crafting'),
('Inventory'),
('Objectives'),
('Stats'),
('Map');