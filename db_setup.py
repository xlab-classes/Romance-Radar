import mysql.connector
from db_globals import cursor
from mysql.connector import errorcode

DB_NAME = 'romance_radar'

# Empty dictionary to store the tables
TABLES = {}

# A table to store the users of Romance Radar. The table has the following columns:
# user_id: a unique ID for this user
# name: the user's name    
# email: the user's email
# password: the user's password
# phone_number: the user's phone number stored as a string of digits
# online: boolean, whether or not the user is online
# preferences: json encoded string, user's preferences
# connections: json encoded string, user's that this user is connected to
# pending_connections: json encoded string, users that need to reply to a req for connection
# connection_requests: json encoded string, users that have send an invitation to connect

TABLES['users'] = (
    "CREATE TABLE `users` ("
    "  `user_id` int(11) NOT NULL AUTO_INCREMENT,"
    "  `name` varchar(255) NOT NULL,"
    "  `email` varchar(255) NOT NULL,"
    "  `password` varchar(255) NOT NULL,"
    "  `phone_number` varchar(255) NOT NULL,"
    "  `online_status` BOOLEAN  DEFAULT FALSE,"
    "  `preferences` JSON NOT NULL DEFAULT '{}'",
    "  `connections` JSON NOT NULL DEFAULT '{}'",
    "  `pending_connections` JSON NOT NULL DEFAULT '{}'",
    "  `connection_requests` JSON NOT NULL DEFAULT '{}'",
    "  PRIMARY KEY (`user_id`)"
    ") ENGINE=InnoDB")
  
# TABLES['online_users'] = (
#     "CREATE TABLE `online_users` ("
#     "  `id` int(11) NOT NULL AUTO_INCREMENT,"
#     "  `name` varchar(255) NOT NULL,"
#     "  `password` varchar(255) NOT NULL,"
#     "  PRIMARY KEY (`id`)"
#     ") ENGINE=InnoDB")


def create_db():
    """
    Creates the database using our imported cursor if it doesn't exist already
    """
    cursor.execute("CREATE DATABASE IF NOT EXISTS {}".format(DB_NAME))
    cursor.execute("USE {}".format(DB_NAME))
    print("Database {} created".format(DB_NAME))

def create_tables():
    cursor.execute("USE {}".format(DB_NAME))

    for name in TABLES:
        try:
            print("Creating table {}: ".format(name), end='')
            cursor.execute(TABLES[name])
            print("Tables created successfully")
        except mysql.connector.Error as err:
            if err.errno == errorcode.ER_TABLE_EXISTS_ERROR:
                print("Already exists.")
            else:
                print(err.msg)
create_db()
create_tables()