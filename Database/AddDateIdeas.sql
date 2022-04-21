-- Insert a date idea for coffee
INSERT INTO Date_ideas
(name, description, picture, time, location) 
VALUES
(   "Tim Hortons",                      -- Cafe name
    "Share a chat over some warm joe",  -- Description
    "../assets/coffee.jpg",             -- Image
    "2022-06-01 10:00:00",              -- Time
    "Lackawanna, NY"                    -- Address
);

-- Insert a date idea for a bar
INSERT INTO Date_ideas
(name, description, picture, time, location)
VALUES
(   "Mr.Goodbar",                       -- Bar name
    "Share a chat over some cold beer", -- Description
    "../assets/beer.jpg",               -- Image
    "2022-06-01 20:00:00",              -- Time
    "Buffalo, NY"                       -- Address
);

-- Insert a date idea for a dinner date
INSERT INTO Date_ideas
(name, description, picture, time, location)
VALUES
(   "Chef's",                           -- Restaurant name
    "Open up over wine and pasta",      -- Description
    "../assets/steak.jpg",              -- Image
    "2022-06-01 18:00:00",              -- Time
    "Buffalo, NY"                       -- Address
);

-- Insert a date idea for a concert
INSERT INTO Date_ideas
(name, description, picture, time, location)
VALUES
(   "Red Hot Chili Peppers",            -- Band name
    "Chill out with a classic band",    -- Description
    "../assets/chili_peppers.jpg",      -- Image
    "2022-06-01 18:00:00",              -- Time
    "Alden, NY"                         -- Address
);

-- Insert a date idea for a hike
INSERT INTO Date_ideas
(name, description, picture, time, location)
VALUES
(   "Chestnut Ridge",                   -- Park name
    "Stay moving while you connect",    -- Description
    "../assets/chestnut_ridge.jpg",     -- Image
    "2022-06-01 14:00:00",              -- Time
    "Orchard Park, NY"                  -- Address
);

-- Insert a date idea for a club
INSERT INTO Date_ideas
(name, description, picture, time, location)
VALUES
(   "Venu",                             -- Club name
    "Dance to EDM then Uber home",      -- Description
    "../asssets/venu.jpg",              -- Image
    "2022-06-01 22:00:00",              -- Time
    "Buffalo, NY"                       -- Address
);


-- Get ID's of the date ideas that were just added
SET @coffee_id = (SELECT id FROM Date_ideas WHERE name="Tim Hortons");
SET @goodbar_id = (SELECT id FROM Date_ideas WHERE name="Mr.Goodbar");
SET @chefs_id = (SELECT id FROM Date_ideas WHERE name="Chef's");
SET @peppers_id = (SELECT id FROM Date_ideas WHERE name="Red Hot Chili Peppers");
SET @chestnut_id = (SELECT id FROM Date_ideas WHERE name="Chestnut Ridge");
SET @venu_id = (SELECT id FROM Date_ideas WHERE name="Venu");


-- Tags for coffee: food, cafe, morning
INSERT INTO Date_tags
(date_id, tag)
VALUES
(@coffee_id, "food");

INSERT INTO Date_tags
(date_id, tag)
VALUES
(@coffee_id, "cafe");

INSERT INTO Date_tags
(date_id, tag)
VALUES
(@coffee_id, "morning");

-- Tags for Mr.Goodbar: entertainment, bars, food, alcohol, indoors, evening, social_events
INSERT INTO Date_tags
(date_id, tag)
VALUES
(@goodbar_id, "entertainment");

INSERT INTO Date_tags
(date_id, tag)
VALUES
(@goodbar_id, "bars");

INSERT INTO Date_tags
(date_id, tag)
VALUES
(@goodbar_id, "food");

INSERT INTO Date_tags
(date_id, tag)
VALUES
(@goodbar_id, "alcohol");

INSERT INTO Date_tags
(date_id, tag)
VALUES
(@goodbar_id, "indoors");

INSERT INTO Date_tags
(date_id, tag)
VALUES
(@goodbar_id, "evening");

INSERT INTO Date_tags
(date_id, tag)
VALUES
(@goodbar_id, "social_events");

-- Tags for Chef's: food, restaurant, indoors, evening
INSERT INTO Date_tags
(date_id, tag)
VALUES
(@chefs_id, "food");

INSERT INTO Date_tags
(date_id, tag)
VALUES
(@chefs_id, "restaurant");

INSERT INTO Date_tags
(date_id, tag)
VALUES
(@chefs_id, "indoors");

INSERT INTO Date_tags
(date_id, tag)
VALUES
(@chefs_id, "evening");

-- Tags for Red Hot Chili Peppers: entertainment, concerts, indoors, evening, social_events
INSERT INTO Date_tags
(date_id, tag)
VALUES
(@peppers_id, "entertainment");

INSERT INTO Date_tags
(date_id, tag)
VALUES
(@peppers_id, "concerts");

INSERT INTO Date_tags
(date_id, tag)
VALUES
(@peppers_id, "indoors");

INSERT INTO Date_tags
(date_id, tag)
VALUES
(@peppers_id, "evening");

INSERT INTO Date_tags
(date_id, tag)
VALUES
(@peppers_id, "social_events");

-- Tags for Chestnut Ridge: entertainment, hiking, outdoors, afternoon
INSERT INTO Date_tags
(date_id, tag)
VALUES
(@chestnut_id, "entertainment");

INSERT INTO Date_tags
(date_id, tag)
VALUES
(@chestnut_id, "hiking");

INSERT INTO Date_tags
(date_id, tag)
VALUES
(@chestnut_id, "outdoors");

INSERT INTO Date_tags
(date_id, tag)
VALUES
(@chestnut_id, "afternoon");

-- Tags for Venu: entertainment, alcohol, indoors, social_events, evening
INSERT INTO Date_tags
(date_id, tag)
VALUES
(@venu_id, "entertainment");

INSERT INTO Date_tags
(date_id, tag)
VALUES
(@venu_id, "alcohol");

INSERT INTO Date_tags
(date_id, tag)
VALUES
(@venu_id, "indoors");

INSERT INTO Date_tags
(date_id, tag)
VALUES
(@venu_id, "social_events");

INSERT INTO Date_tags
(date_id, tag)
VALUES
(@venu_id, "evening");