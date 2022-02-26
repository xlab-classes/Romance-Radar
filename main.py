from typing import Optional
from rr_db import cursor,db


def register_account(name, email, password, phone_number):
    """
    Creates a new user in the users table essentially registering them.
    """
    # Inserting the user's information into the database
    cursor.execute("INSERT INTO users (name, email, password, phone_number) VALUES (%s, %s, %s, %s)", (name, email, password, phone_number))
    db.commit()
    user_name =  cursor.lastrowid
    print("User {} added".format(name))


def sign_in(name, password):
    """
    Checks if the user is in the database and if the password is correct.
    Inserts the user into online_users table if the user is in the users table and the password is correct,
    otherwise returns Error.
    """
    cursor.execute("SELECT name FROM users WHERE name = %s AND password = %s", (name, password))
    user_name = cursor.fetchone()
    if user_name is None:
        return "No such user was found or password is incorrect"
    else:
        cursor.execute("INSERT INTO online_users (name, password) VALUES (%s, %s)", (name, password))
        db.commit()
        print("{} signed in".format(name))
        return "Success"

def sign_out(name, password):
    """
    Deletes the user's information from the online_users table.
    """
    cursor.execute("DELETE FROM online_users WHERE name = %s AND password = %s", (name, password))
    db.commit()
    print("{} signed out".format(name))

def get_online_users():
    """
    Returns a list of all the users in the online_users table.
    """
    cursor.execute("SELECT name FROM online_users")
    online_users = cursor.fetchall()
    return online_users


def get_name_from_id(user_id):
    """
    Returns the user's name if the user is in the online_users table.
    """
    cursor.execute("SELECT name FROM online_users WHERE id = %s", (user_id,))
    user_name = cursor.fetchone()
    if user_name is None:
        return "User is not online"
    else:
        return user_name[0]

def close_db():
    """
    Closes the database connection.
    """
    db.close()
    print("Database connection closed")

def clear_db():
    """
    Deletes all the users in the online_users table and logs out every online user.
    """
    cursor.execute("DELETE FROM online_users")
    cursor.execute("DELETE FROM users")
    db.commit()
    print("All online users signed out")

