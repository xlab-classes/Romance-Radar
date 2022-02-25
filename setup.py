import mysql.connector

from database import cursor

DB_NAME = 'test'

# Create teh databas eif it doesn't exist already
def create_database():
    cursor.execute(
        "CREATE DATABASE IF NOT EXISTS Test DEFAULT CHARACTER SET 'utf8'")
    print("Database {} created successfully.")

create_database()