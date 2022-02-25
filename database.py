import mysql.connector
from mysql.connector import cursor
DB_NAME = 'romance_radar_users'

# Establishing the connection to the database
db = mysql.connector.connect()


# Create a function to create a database if it doesn't exist already
def create_database():
    cursor.execute(
        "CREATE DATABASE {} DEFAULT CHARACTER SET 'utf8'".format(DB_NAME))
    # Notify that the database was created
    print("Database {} created successfully.".format(DB_NAME))
                
            