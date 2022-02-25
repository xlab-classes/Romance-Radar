import mysql.connector
from rr_db import cursor

DB_NAME = 'romance_radar'

# Empty dictionary to store the tables
TABLES = {}

# A table to store the users of Romance Radar. The table has the following columns:
# name: the user's name    
# email: the user's email
# password: the user's password
# phone_number: the user's phone number stored as a string of digits
# profile_pic: the user's profile picture stored as a blob image
# preferences: the user's preferences stored as json 

TABLES['users'] = (
    "CREATE TABLE `users` ("
    "  `id` int(11) NOT NULL AUTO_INCREMENT,"
    "  `name` varchar(255) NOT NULL,"
    "  `email` varchar(255) NOT NULL,"
    "  `password` varchar(255) NOT NULL,"
    "  `phone_number` varchar(255) NOT NULL,"
    "  `profile_pic` blob NOT NULL,"
    "  `preferences` json NOT NULL,"
    "  PRIMARY KEY (`id`)"
    ") ENGINE=InnoDB")


def create_db():
    """
    Creates the database using our imported cursor if it doesn't exist already
    """
    cursor.execute("CREATE DATABASE IF NOT EXISTS {}".format(DB_NAME))
    cursor.execute("USE {}".format(DB_NAME))
    print("Database {} created".format(DB_NAME))

create_db()