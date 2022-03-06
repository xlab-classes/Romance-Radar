# Author: Alex Eastman
# Created: 03/05/2022
# Modified: 03/06/2022
#
# Tests for the database API found in db_api.py . Uses Python built-in
# unittests library

from db_api import connection
import db_api as dapi
import unittest
import json

DB_NAME = "rrdb"
TABLE_NAME = "users"

kName = "Alex"              # Name for testing
kEmail = "rr@gmail.com"     # Email for testing
kPhone = "7160001122"       # Phone for testing
kPassword = "password"          # Password for testing

# Basic tests:
# * Confirm that database is created correctly
# * Confirm that users can be added to the database
# * Confirm that users are assigned sequential IDs automagically
class BasicTests(unittest.TestCase):

    # Called before each of the tests below
    def setUp(self):
        # Create a database with a table
        dapi.create_db(DB_NAME)
        dapi.create_table(DB_NAME, TABLE_NAME)

        # Get a cursor into the database
        self.cur = connection.cursor()
        self.cur.execute(f"USE {DB_NAME}")


    # Called after each of the tests below
    def tearDown(self):
        # Destroy table and database
        dapi.destroy_table(DB_NAME, TABLE_NAME)
        dapi.destroy_db(DB_NAME)


    # Make sure that database is empty after it's created
    def test_initially_empty(self):
        self.cur.execute(f"SELECT * FROM {TABLE_NAME}")
        entries = self.cur.fetchone()
        self.assertIsNone(entries)

        has_user = dapi.user_exists(0)
        self.assertFalse(has_user)


    # Try to add a user
    def add_one_user(self):
        # Returns 0 on success
        user_created = dapi.create_user(kName, kEmail, kPassword, kPhone)
        self.assertEqual(user_created, 0)

        # First user_id should be 0
        user_id = dapi.get_user_id(kEmail)
        self.assertEqual(user_id, 0)


    # Try to add 3 users, check that user_ids are sequential
    def add_many_users(self):
        u1 = dapi.create_user("a", "b", "c", "d")
        u2 = dapi.create_user("e", "f", "g", "h")
        u3 = dapi.create_user("i", "j", "k", "l")

        self.assertEqual(u1, 0)
        self.assertEqual(u2, 0)
        self.assertEqual(u3, 0)

        uid_1 = dapi.get_user_id("b")
        uid_2 = dapi.get_user_id("f")
        uid_3 = dapi.get_user_id("j")

        self.assertGreater(uid_3, uid_2)
        self.assertGreater(uid_2, uid_1)
        self.assertEqual(uid_1, 0)

    
# More robust tests. Test functionality with one user in the database
class BasicFunctionalityTests(unittest.TestCase):

    # Called before each of the tests below
    def setUp(self):
        # Create a database with a table
        dapi.create_db(DB_NAME)
        dapi.create_table(DB_NAME, TABLE_NAME)

        # Create a single user
        dapi.create_user(kName, kEmail, kPassword, kPhone)
        
        # Get the user ID of the created user
        self.user_id = dapi.get_user_id(kEmail)

    # Called after each of the tests below
    def tearDown(self):
        # Destroy table and database
        dapi.destroy_table(DB_NAME, TABLE_NAME)
        dapi.destroy_db(DB_NAME)

    def testEmptyConnections(self):
        # No connections, should return empty dict
        connections = dapi.get_connections(user_id)
        pycon = json.loads(connections)
        self.assertDictEqual(pycon, {})

        # Returns 0 on success
        online = dapi.sign_in("alexeast.dev@pm.me", "password")
        self.assertEqual(online, 0)

        # Returns 0 on success
        offline = dapi.sign_out(user_id)
        self.assertEqual(offline, 0)

        # Returns 0 if the passwords match
        good_pwd = dapi.check_password(user_id, "password")
        self.assertEqual(good_pwd, 0)
        
        # Returns 0 on success
        change_pwd = dapi.update_password(user_id, "password", "123456")
        self.assertEqual(change_pwd, 0)

        # Returns 1 on failure
        add_con = dapi.add_connection(user_id, 1)  # user id 1 doesn't exist yet
        self.assertEqual(add_con, 1)

        # Returns 1 on failure


if __name__ == '__main__':
    unittest.main()