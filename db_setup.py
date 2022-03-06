from db_globals import connection

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
# connection_requests: json encoded string, users that have sent an invitation to connect



# Created database if it doesn't exist
def create_db(name):
    cur = connection.cursor()
    try:
        cur.execute(f"CREATE DATABASE IF NOT EXISTS {name}")
        cur.execute(f"USE {name}")
    except:
        print("There was a problem creating a new database")
    cur.close()
    connection.commit()



# Create a table called 'name' in database 'db' if it doesn't exist
def create_table(db, name):
    cur = connection.cursor()
    cur.execute(f"USE {db}")

    try:
        create_table_sql = (
            f"CREATE TABLE IF NOT EXISTS {name} ("
            "user_id int NOT NULL AUTO_INCREMENT,"
            "name varchar(255) NOT NULL,"
            "email varchar(255) NOT NULL,"
            "password varchar(255) NOT NULL,"
            "phone_number varchar(255) NOT NULL,"
            "online_status BOOL DEFAULT FALSE,"
            "preferences JSON NOT NULL,"    # Default value causes error
            "connections JSON NOT NULL,"    # Default value causes error
            "pending_connections JSON NOT NULL,"    # Default value causes error
            "connection_requests JSON NOT NULL,"    # Default value causes error
            "PRIMARY KEY (user_id)"
            ") ENGINE=InnoDB"
        )
        cur.execute(create_table_sql)
        print("Tables created successfully")
    except:
        print("Couldn't create tables in database")

    cur.close()
    connection.commit()



# Destroy database called 'name' if it exists
def destroy_db(name):
    cur = connection.cursor()
    try:
        cur.execute(f"DROP DATABASE IF EXISTS {name}")
    except:
        print("Couldn't delete database")
    cur.close()
    connection.commit()



# Destroy table called 'name' in database 'db' if it exists
def destroy_table(db, name):
    cur = connection.cursor()
    cur.execute(f"USE {db}")
    try:
        cur.execute(f"DROP TABLE IF EXISTS {name}")
    except:
        print("Couldn't delete table")
    cur.close()
    connection.commit()