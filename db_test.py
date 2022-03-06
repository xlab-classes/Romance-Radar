from db_api import connection
import db_api as dapi

DB_NAME = "rrdb"
TABLE_NAME = "users"

def basic_test():
    # Create a database with a table
    dapi.create_db(DB_NAME)
    dapi.create_table(DB_NAME, TABLE_NAME)

    # Get a cursor into the database
    cur = connection.cursor()
    cur.execute(f"USE {DB_NAME}")