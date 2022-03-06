from db_globals import connection
import db_setup as dutils
import db_api as dapi

DB_NAME = "rrdb"
TABLE_NAME = "users"

def basic_test():
    # Create a database with a table
    dutils.create_db(DB_NAME)
    dutils.create_table(DB_NAME, TABLE_NAME)

    # Get a cursor into the database
    cur = connection.cursor()
    cur.execute(f"USE {DB_NAME}")