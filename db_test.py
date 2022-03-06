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


    # Called after each of the tests below
    def tearDown(self):
        # Destroy table and database
        dapi.destroy_table(DB_NAME, TABLE_NAME)
        dapi.destroy_db(DB_NAME)


    # Make sure that database is empty after it's created
    def testInitiallyEmpty(self):
        # Get a cursor into the database
        cur = connection.cursor()
        cur.execute(f"SELECT * FROM {TABLE_NAME}")
        entries = cur.fetchone()
        cur.close()
        self.assertIsNone(entries)

        has_user = dapi.user_exists(0)
        self.assertFalse(has_user)


    # Try to add a user
    def testAddOneUser(self):
        # Returns 0 on success
        user_created = dapi.create_user(kName, kEmail, kPassword, kPhone)
        self.assertEqual(user_created, 0)

        # First user_id should be 1
        user_id = dapi.get_user_id(kEmail)
        self.assertEqual(user_id, 1)


    # Try to add 3 users, check that user_ids are sequential
    def testAddManyUsers(self):
        u1 = dapi.create_user("a", "b", "c", "d")
        u2 = dapi.create_user("e", "f", "g", "h")
        u3 = dapi.create_user("i", "j", "k", "l")

        # Make sure all users created successfully
        self.assertEqual(u1, 0)
        self.assertEqual(u2, 0)
        self.assertEqual(u3, 0)

        uid_1 = dapi.get_user_id("b")
        uid_2 = dapi.get_user_id("f")
        uid_3 = dapi.get_user_id("j")

        self.assertGreater(uid_3, uid_2)
        self.assertGreater(uid_2, uid_1)
        self.assertEqual(uid_1, 1)

    
# More robust tests. Test functionality with one user in the database
# * Test that connections are empty before connections have been added
# * Test that a user can login
# * Test that we can check a user's password correctly
# * Test that we can change a user's password
# * Test that we can't add a connection with only one user in the DB
# * Test that we can delete a user
class BasicFunctionalityTests(unittest.TestCase):

    # Called before each of the tests below. Creates database with users table
    # and single user
    def setUp(self):
        dapi.create_db(DB_NAME)
        dapi.create_table(DB_NAME, TABLE_NAME)
        dapi.create_user(kName, kEmail, kPassword, kPhone)
        
        # Save the user id for access in test cases
        self.user_id = dapi.get_user_id(kEmail)


    # Called after each of the tests below. Destroys table and database
    def tearDown(self):
        dapi.destroy_table(DB_NAME, TABLE_NAME)
        dapi.destroy_db(DB_NAME)


    def testEmptyConnections(self):
        # No connections, should return empty dict
        connections = dapi.get_connections(self.user_id)
        pycon = json.loads(connections)
        self.assertDictEqual(pycon, {})


    def testLogin(self):
        # Returns 0 on success
        online = dapi.sign_in(kEmail, kPassword)
        self.assertEqual(online, 0)


    def testCheckPassword(self):
        # Returns 0 if the passwords match
        good_pwd = dapi.check_password(self.user_id, kPassword)
        self.assertEqual(good_pwd, 0)
        

    def testChangePassword(self):
        # Returns 0 on success
        change_pwd = dapi.update_password(self.user_id, kPassword, "123456")
        self.assertEqual(change_pwd, 0)


    # Not implemented yet
    # def testAddConnection(self):
    #     # Returns 1 on failure
    #     add_con = dapi.add_connection(self.user_id, 2)  # user id 2 doesn't exist yet
    #     self.assertEqual(add_con, 1)
        

    def testDeleteUser(self):
        # Returns 0 on success
        deleted = dapi.delete_user(self.user_id)
        self.assertEqual(deleted, 0)


if __name__ == '__main__':
    unittest.main()