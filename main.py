from typing import Optional
from rr_db import cursor,db

def create_user(name, email, password, phone_number):
    """
    Creates a new user in the database essentially registering them.
    """
    # Inserting the user's information into the database
    cursor.execute("INSERT INTO users (name, email, password, phone_number) VALUES (%s, %s, %s, %s)", (name, email, password, phone_number))
    db.commit()
    user_name =  cursor.lastrowid
    print("User {} added".format(user_name))

create_user("Jordan", "jordangrant46@yahoo.com", "password", "1234567890")
create_user("Farhan", "farhanc@buffalo.edu", "password", "1234567890")

