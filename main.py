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

def get_user_id_from_email(email):
    """
    Returns the user's id from the database if the user is in the database,
    otherwise returns None.
    """
    cursor.execute("SELECT id FROM users WHERE email = %s", (email,))
    user_id = cursor.fetchone()
    if user_id is None:
        return "User not found"
    else:
        return user_id[0]


def sign_in(name, password):
    """
    Checks if the user is in the database and if the password is correct.
    Returns the user's id if the user is in the database and the password is correct,
    otherwise returns None.
    """
    cursor.execute("SELECT id FROM users WHERE name = %s AND password = %s", (name, password))
    user_id = cursor.fetchone()
    if user_id is None:
        return "Password incorrect"
    else:
        return user_id[0]

def sign_out(user_id):
    """
    Deletes the user's information from the database.
    """
    cursor.execute("DELETE FROM users WHERE id = %s", (user_id,))
    db.commit()
    print("User {} deleted".format(user_id))

def get_online_users():
    """
    Returns a list of all the users in the database.
    """
    cursor.execute("SELECT name FROM users")
    users = cursor.fetchall()
    return users
