CREATE TABLE IF NOT EXISTS Users(
    id INT AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    password VARCHAR(100) NOT NULL,
    user_picture VARCHAR(100) NOT NULL,
    street_address VARCHAR(100) NOT NULL,
    zipcode INT NOT NULL,
    birthday DATE NOT NULL,
    partner INT,
    city VARCHAR(100) NOT NULL,
    verified BIT NOT NULL DEFAULT 0,
	signup_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (partner) REFERENCES Users(id) ON DELETE SET NULL
    );

CREATE TABLE IF NOT EXISTS Food(
    id INT AUTO_INCREMENT,
    user_id INT NOT NULL,
    restaurant BIT NOT NULL,
    cafe BIT NOT NULL,
    fast_food BIT NOT NULL,
    alcohol BIT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
    );
    
CREATE TABLE IF NOT EXISTS Entertainment(
    id INT AUTO_INCREMENT,
    user_id INT NOT NULL,
    concerts BIT NOT NULL,
    hiking BIT NOT NULL,
    bar BIT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
    );

CREATE TABLE IF NOT EXISTS Venue(
    id INT AUTO_INCREMENT,
    user_id INT NOT NULL,
    indoors BIT NOT NULL,
    outdoors BIT NOT NULL,
    social_events BIT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
    );

CREATE TABLE IF NOT EXISTS Date_time(
    id INT AUTO_INCREMENT,
    user_id INT NOT NULL,
    morning BIT NOT NULL,
    afternoon BIT NOT NULL,
    evening BIT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
    );

CREATE TABLE IF NOT EXISTS Date_preferences(
    id INT AUTO_INCREMENT,
    user_id INT NOT NULL,
    cost INT NOT NULL,
    distance INT NOT NULL,
    length INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
    );

CREATE TABLE IF NOT EXISTS Date_ideas(
    id INT AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description VARCHAR(500) NOT NULL,
    picture VARCHAR(100) NOT NULL,
    time DATETIME NOT NULL,
    location VARCHAR(100) NOT NULL,
    est_cost INT NOT NULL,      -- Estimated cost
    est_length INT NOT NULL,    -- Estimated date length
    PRIMARY KEY (id)
    );

CREATE TABLE IF NOT EXISTS Date_tags(
    id INT AUTO_INCREMENT,
    date_id INT NOT NULL,       -- ID of date to tag
    tag VARCHAR(50) NOT NULL,   -- String name of tag
    PRIMARY KEY (id),
    FOREIGN KEY (date_id) REFERENCES Date_ideas(id) ON DELETE CASCADE
    );

CREATE TABLE IF NOT EXISTS Date_liked(
    id INT AUTO_INCREMENT,
    date_id int NOT NULL,
    user_id int NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (date_id) REFERENCES Date_ideas(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
    );

CREATE TABLE IF NOT EXISTS Date_disliked(
    id INT AUTO_INCREMENT,
    date_id int NOT NULL,
    user_id int NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (date_id) REFERENCES Date_ideas(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
    );

CREATE TABLE IF NOT EXISTS Suggested_dates(
    id INT AUTO_INCREMENT,
    partner_id_1 INT NOT NULL,
    partner_id_2 INT NOT NULL,
    date_id INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (partner_id_1) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (partner_id_1) REFERENCES Users(id) ON DELETE CASCADE,
	FOREIGN KEY (date_id) REFERENCES Date_ideas(id) ON DELETE CASCADE
    );

-- Tracks the number of times dates have been suggested to a particular user
CREATE TABLE IF NOT EXISTS Date_counts(
    id INT AUTO_INCREMENT,
    date_id INT NOT NULL,
    suggested INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (id) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (date_id) REFERENCES Date_ideas(id) ON DELETE CASCADE
    );

-- Tracks which dates users have liked
CREATE TABLE IF NOT EXISTS Dates_liked(
    id INT AUTO_INCREMENT,
    date_id INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (id) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (date_id) REFERENCES Date_ideas(id) ON DELETE CASCADE
    );

-- Tracks which dates users have disliked
CREATE TABLE IF NOT EXISTS Dates_disliked(
    id INT AUTO_INCREMENT,
    date_id INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (id) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (date_id) REFERENCES Date_ideas(id) ON DELETE CASCADE
    );

CREATE TABLE IF NOT EXISTS Connection_requests(
    id INT AUTO_INCREMENT,
    sent_from INT NOT NULL,
    sent_to INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (sent_from) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (sent_to) REFERENCES Users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Security_questions(
    id INT AUTO_INCREMENT,
    question VARCHAR(50) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS User_security_questions(
    id INT AUTO_INCREMENT,
    user_id INT NOT NULL,
    question_id_1 INT,
    question_id_2 INT,
    question_id_3 INT,
    answer_1 VARCHAR(100) NOT NULL,
    answer_2 VARCHAR(100) NOT NULL,
    answer_3 VARCHAR(100) NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id_1) REFERENCES Security_questions(id) ON DELETE SET NULL,
    FOREIGN KEY (question_id_2) REFERENCES Security_questions(id) ON DELETE SET NULL,
    FOREIGN KEY (question_id_3) REFERENCES Security_questions(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS Chat_Messages(
    id INT AUTO_INCREMENT,
    sent_from INT NOT NULL,
    sent_to INT NOT NULL,
    message VARCHAR(200),
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (sent_from) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (sent_to) REFERENCES Users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Privacy_settings(
    id INT AUTO_INCREMENT,
    user_id INT NOT NULL,
    max_cost BIT NOT NULL DEFAULT 0,
    max_distance BIT NOT NULL DEFAULT 0,
    date_len BIT NOT NULL DEFAULT 0,
    date_of_birth BIT NOT NULL DEFAULT 0,
    time_pref BIT NOT NULL DEFAULT 0,
    food_pref BIT NOT NULL DEFAULT 0,
    ent_pref BIT NOT NULL DEFAULT 0,
    venue_pref BIT NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
);

/* Create a table for capcha images */
CREATE TABLE IF NOT EXISTS Captcha(
    id INT NOT NULL AUTO_INCREMENT,
    image VARCHAR(100) NOT NULL,
    code VARCHAR(100) NOT NULL,
    PRIMARY KEY (id)
);


CREATE TABLE IF NOT EXISTS User_status(
	id INT AUTO_INCREMENT,
	user_status VARCHAR(50)
	FOREIGN KEY (id) REFERENCES Users(id) ON DELETE CASCADE
);


INSERT INTO Captcha (image, code) VALUES 
('../assets/Captcha/captcha_1.png', '2cegf'),
('../assets/Captcha/captcha_2.png', '24f6w'),
('../assets/Captcha/captcha_3.png', '226md');


