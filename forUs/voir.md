CREATE TABLE trajet (
  id INT AUTO_INCREMENT PRIMARY KEY,
  depart VARCHAR(100),
  arrive VARCHAR(100),
  date_trajet DATE,
  heure_depart TIME,
  prix DECIMAL(10,2),
  places_disponibles INT,
  car VARCHAR(50),
  conducteur_id INT,
  created_at DATETIME
);
-------------------------------------------------------
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    telephone VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
---------------------------------------------------
ALTER TABLE users 
ADD COLUMN reset_token VARCHAR(64) NULL,
ADD COLUMN reset_expires DATETIME NULL;
---
ALTER TABLE trajet 
ADD COLUMN status ENUM('active', 'completed') NOT NULL DEFAULT 'active';

-- Optional: For better performance on searches
CREATE INDEX idx_search ON trajet (depart, arrive, date_trajet, status, places_disponibles);

--------------------------------------------------

CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trajet_id INT NOT NULL,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    nombre_places INT NOT NULL,
    date_reservation DATETIME NOT NULL,
    FOREIGN KEY (trajet_id) REFERENCES trajet(id)
);
ALTER TABLE reservations ADD COLUMN conducteur_id INT;
---
ALTER TABLE trajet 
MODIFY COLUMN conducteur_id INT NOT NULL,
ADD FOREIGN KEY (conducteur_id) REFERENCES users(id);
-----------------------------------------------------

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
---
INSERT INTO admins (user_id) VALUES (1);
INSERT INTO admins (user_id) VALUES (2);
----------------------------------------------------
Option 3: Use Windows Task Scheduler (Local Cron Job):::

Create Basic Task:

Name: "Update Trajet Status"

Trigger: Daily or Hourly

Action: "Start a program"

Program/script: C:\xampp\php\php.exe

Arguments: -f "C:\xampp\htdocs\myProjet\admin\update_status.php"

Start in: C:\xampp\php\

--------------------------------------------------------------------
CREATE TABLE driver_ratings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT NOT NULL,
    driver_id INT NOT NULL,
    passenger_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    -- Optional: add a comment or feedback field
    comment TEXT,

    CONSTRAINT fk_driver_ratings_reservation
        FOREIGN KEY (reservation_id) REFERENCES reservations(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_driver_ratings_driver
        FOREIGN KEY (driver_id) REFERENCES users(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_driver_ratings_passenger
        FOREIGN KEY (passenger_id) REFERENCES users(id)
        ON DELETE CASCADE
);
-----------------------------------------------