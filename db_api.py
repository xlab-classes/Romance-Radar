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



# Get this user's ID by their email
def get_user_id(email):
    cur = db.cursor()
    cur.execute("SELECT user_id FROM users WHERE email=%s", (email,))
    answer = cur.fetchone()
    cur.close()
    
    if answer is None:
        return -1
    else:
        return answer[0]



# Attempt to sign in the user whose email is `email` and whose password is
# `password`
def sign_in(email, password):
    cur = db.cursor()
    cur.execute("SELECT name FROM users WHERE email = %s AND password = %s", (email, password))
    answer = cur.fetchone()
    
    # Get the current online status of the select user with the given email
    users_current_status = cur.execute("SELECT online FROM users WHERE email = %s", (email,))

    # If no such user exists, return -1
    if answer is None:
        print("No such user exists. Please register or try again.")
        cur.close()
        return -1
    # If the user is already online return -1
    elif users_current_status is True:
        print("User is already online. Please sign out before signing in again.")
        cur.close()
        return -1
    else:
        cur.execute("UPDATE users SET online=TRUE WHERE email = %s", (email,))
        cur.close()
        db.commit()
        return 1



# Attempt to sign out the user with ID `user_id`
def sign_out(user_id):
    if not user_exists(user_id):
        return 1
    else:
        cur = db.cursor()
        cur.execute("UPDATE users SET online=FALSE WHERE user_id = %d", (user_id,))
        cur.close()
        db.commit()
        return 0



# Check that the password matches the password stored for the user with ID
# `user_id`
def check_password(user_id, password):
    if not user_exists(user_id):
        return 1  # User doesn't exist
    else:
        cur = db.cursor()
        cur.execute("SELECT user_id FROM users WHERE user_id=%d AND password=%s", (user_id, password))
        answer = cur.fetchone()
        cur.close()
        
        if answer is None:
            return -1  # Passwords don't match
        else:
            return 0  # Passwords do match



# Attempt to change the password of the user with ID `user_id`
def update_password(user_id, old_password, new_password):
    if not old_password or not new_password:
        return 1  # Empty passwords

    passwords_match = check_password(user_id, old_password)
    if passwords_match != 0:
        return 1  # User doesn't exist OR wrong pass
    else:
        cur = db.cursor()
        cur.execute("UPDATE users SET password=%s WHERE user_id=%d", (new_password, user_id))
        cur.close()
        db.commit()
        return 0  # Successfully changed password



# Get the user ID of the user whose email is `email`
def get_user_id(email):
    cur = db.cursor()
    cur.execute("SELECT user_id FROM users WHERE email=%s", (email,))
    answer = cur.fetchone()
    cur.close()

    if answer is None:
        return -1  # user doesn't exist
    else:
        # TODO: This might not be the proper way to do this
        return answer[0]



# Creates a new user and stores their data in the database. This function will
# create a unique user ID for the new user
def create_user(name, email, password, phone_number):
    if not name or not email or not password or not phone_number:
        return 1  # Required data is empty
    elif not phone_number:
        return 1  # Malformed phone number TODO
    elif get_user_id(email) != -1:
        return 1  # User already exists
    else:
        cur = db.cursor()
        cur.execute("INSERT INTO users (name, email, password, phone_number) VALUES (%s,%s,%s,%s)",\
            (name, email, password, phone_number))
        cur.close()
        db.commit()
        return 0  # Successfully created user



# Removes all of a user's data from the database
def delete_user(user_id):
    if not user_exists(user_id):
        return 1
    else:
        cur = db.cursor()
        cur.execute("DELETE FROM users WHERE user_id=%d", (user_id,))
        cur.close()
        db.commit()
        return 0



# Attempt to connect the users with IDs `user_id_a` and `user_id_b`. This
# requires that one of the users has sent a connection request and the other
# one has a pending request from the sender
def add_connection(user_id_a, user_id_b):
    # 1. Check that both users exist
    # 2. Ensure that there is not a connection between the users
    # 3. Ensure that user_id_a is in user_id_b's pending_connections (maybe reverse)
    # 4. Ensure that user_id b is in user_id_a's connection_requests (reverse of above)
    # 5. Remove the pending_connection and connection_requests from the appropriate users
    # 6. Add each user to the others' connections list
    # 7. Save to DB and close connection
    return



# Add a request to connect to the user with ID `user_id_rx`. Add the pending
# connection to the user with ID `user_id_tx`
def add_connection_request(user_id_tx, user_id_rx):
    # 1. Check that both users exist
    # 2. Ensure that there is not a connection between the users
    # 3. Ensure that user_id_tx is not in user_id_rx's connection_requests
    # 4. Ensure that user_id_rx is not in user_id_tx's pending_connections
    # 5. Add user_id_rx to user_id_tx's pending_connections
    # 6. Add user_id_tx to user_id_rx's connection_requests
    # 7. Save to DB and close connection
    return



# Attempt to disconnect the users with IDs `user_id_a` and `user_id_b`. This
# requires that a connection exists between these users
def remove_connection(user_id_a, user_id_b):
    # 1. Check that both users exist
    # 2. Ensure that there is a connection between the users
    # 3. Remove each user from the others' connections list
    # 4. Save to DB and close connection
    return



# Get a JSON-formatted string of connections for the user with ID `user_id`
def get_connections(user_id):
    if not user_exists(user_id):
        return ""
    else:
        cur = db.cursor()
        cur.execute("SELECT connections FROM users WHERE user_id=%d", (user_id,))
        answer = cur.fetchone()
        cur.close()
        return answer[0]



# Get the preferences of the user with ID `user_id`
def get_preferences(user_id):
    if not user_exists(user_id):
        return ""
    else:
        cur = db.cursor()
        cur.execute("SELECT preferences FROM users WHERE user_id=%d", (user_id,))
        answer = cur.fetchone()
        cur.close()
        return answer[0]



def update_preferences(user_id, preferences):
    """
    Update the date preferences for the user with ID user_id.
    The preferences should be a JSON-formatted string and represent the updated preferences.
    An example of a valid preferences string:
        {
        entertainment: {
            concerts: true,
            hiking: true,
            bars: false
        },
        food: {
            restaurants: false,
            cafe: true,
            ...
        },
        time: {
            morning: false,
            afternoon: true,
            evening: true
        }
    }
    """
    if(preferences is None):
        return -1
    cur = db.cursor()
    try:
        cur.execute("UPDATE users SET preferences = %s WHERE id = %s", (preferences, user_id))
        db.commit()
        db.close()
        print("Successfully updated preferences")
        return 1
    except:
        print("Error updating preferences")
        return 0
                
