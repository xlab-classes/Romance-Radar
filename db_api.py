# Authors: Alex Eastman, Jordan Grant, Farhan Chowhury
# Created: 02/27/2022
# Modified: 03/03/2022

from typing import Optional
from db_globals import config, db



# Check if there is an existing account with this email
def user_exists(user_id):
    cur = db.cursor()
    cur.execute("SELECT user_id FROM users WHERE user_id = %d", (user_id,))
    answer = cur.fetchone()
    cur.close()

    if answer is None:
        return False
    else:
        return True



# Attempt to sign in the user whose email is `email` and whose password is
# `password`
def sign_in(email, password):
    cur = db.cursor()
    cur.execute("SELECT name FROM users WHERE email = %s AND password = %s", (email, password))
    answer = cur.fetchone()

    if answer is None:
        cur.close()
        return 1
    else:
        cur.execute("UPDATE users SET online=TRUE WHERE email = %s", (email,))
        cur.close()
        return 0



# Attempt to sign out the user with ID `user_id`
def sign_out(user_id):
    if not user_exists(user_id):
        return 1
    else:
        cur = db.cursor()
        cur.execute("UPDATE users SET online=FALSE WHERE user_id = %d", (user_id,))
        cur.close()
        return 0



# Check that the password matches the password stored for the user with ID
# `user_id`
def check_password(user_id, password):
    if not user_exists(user_id):
        return 1
    else:
        cur = db.cursor()
        cur.execute("SELECT user_id FROM users WHERE user_id=%d AND password=%s", (user_id, password))

    if cur.fetchone() is None:
        return 1  # User doesn't exist
    


def register_account(name, email, password, phone_number):
    """
    Creates a new user in the users table essentially registering them.
    """
    # Inserting the user's information into the database
    cur.execute("INSERT INTO users (name, email, password, phone_number) VALUES (%s, %s, %s, %s)", (name, email, password, phone_number))
    db.commit()
    user_name =  cur.lastrowid
    print("User {} added".format(name))


def get_online_users():
    """
    Returns a list of all the users in the online_users table.
    """
    cur.execute("SELECT name FROM online_users")
    online_users = cur.fetchall()
    return online_users


def get_name_from_id(user_id):
    """
    Returns the user's name if the user is in the online_users table.
    """
    cur.execute("SELECT name FROM online_users WHERE id = %s", (user_id,))
    user_name = cur.fetchone()
    if user_name is None:
        return "User is not online"
    else:
        return user_name[0]

def get_user_id(email):
    """
    Get the user ID of the user whose email is email.
    """
    cur.execute("SELECT id FROM users WHERE email = %s", (email,))
    user_id = cur.fetchone()
    if user_id is None:
        return "User not found"
    else:
        return user_id[0]
