CREATE TABLE Users(
    id INT AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    password VARCHAR(100) NOT NULL,
    user_picture VARCHAR(100) NOT NULL,
    street_address VARCHAR(100) NOT NULL,
    zipcode INT NOT NULL,
    birthday DATE NOT NULL,
    partner INT,
    PRIMARY KEY (id),
    FOREIGN KEY (partner) REFERENCES Users(id) ON DELETE SET NULL
    );
CREATE TABLE Food(
    id INT AUTO_INCREMENT,
    user_id INT NOT NULL,
    restraunt BIT NOT NULL,
    cafe BIT NOT NULL,
    fast_food BIT NOT NULL,
    alcohol BIT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
    );
    
CREATE TABLE Entertainment(
    id INT AUTO_INCREMENT,
    user_id INT NOT NULL,
    concerts BIT NOT NULL,
    hiking BIT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
    );

CREATE TABLE Venue(
    id INT AUTO_INCREMENT,
    user_id INT NOT NULL,
    indoors BIT NOT NULL,
    outdoors BIT NOT NULL,
    social_events BIT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
    );

CREATE TABLE Date_time(
    id INT AUTO_INCREMENT,
    user_id INT NOT NULL,
    morning BIT NOT NULL,
    afternoon BIT NOT NULL,
    evening BIT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
    );

CREATE TABLE Date_preferences(
    id INT AUTO_INCREMENT,
    user_id INT NOT NULL,
    cost INT NOT NULL,
    distance INT NOT NULL,
    length INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
    );

CREATE TABLE Date_ideas(
    id INT AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description VARCHAR(500) NOT NULL,
    picture VARCHAR(100) NOT NULL,
    time DATETIME NOT NULL,
    location VARCHAR(100) NOT NULL,
    PRIMARY KEY (id)
    );

CREATE TABLE Date_liked(
    id INT AUTO_INCREMENT,
    date_id int NOT NULL,
    user_id int NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (date_id) REFERENCES Date_ideas(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
    );

CREATE TABLE Date_disliked(
    id INT AUTO_INCREMENT,
    date_id int NOT NULL,
    user_id int NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (date_id) REFERENCES Date_ideas(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
    );

CREATE TABLE Suggested_dates(
    id INT AUTO_INCREMENT,
    partner_id_1 INT NOT NULL,
    partner_id_2 INT NOT NULL,
    date_id INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (partner_id_1) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (partner_id_1) REFERENCES Users(id) ON DELETE CASCADE,
	FOREIGN KEY (date_id) REFERENCES Date_ideas(id) ON DELETE CASCADE
    );

CREATE TABLE Connection_requests(
    id INT AUTO_INCREMENT,
    sent_from INT NOT NULL,
    sent_to INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (sent_from) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (sent_to) REFERENCES Users(id) ON DELETE CASCADE,
);
