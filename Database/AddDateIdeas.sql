INSERT INTO Date_ideas
(name, description, picture, time, location) 
VALUES
("Coffee", "Share a chat over some warm joe", "../assets/coffee.jpg",
    "2022-06-01 12:00:00", "123 Tim Hortons Lane");

Declare @coffee INT;
@coffee = (SELECT TOP(1) id FROM Date_ideas WHERE name="Coffee");

INSERT INTO Date_tags
(date_id, tag)
VALUES
(@coffee, "food");

INSERT INTO Date_tags
(date_id, tag)
VALUES
(@coffee, "cafe");

INSERT INTO Date_tags
(date_id, tag)
VALUES
(@coffee, "afternoon");