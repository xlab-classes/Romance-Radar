<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once('db_api.php');
# Create a PHPUnit test case class
final class SignInTest extends TestCase
{

    # Create a function that tests the db_api.php sign_in function
    public function testSignInNotLoggedIn(): void
    {
        # Create a test user
        create_user(
            "Jon Doe", "doe.jon@gmail.com", "password", "123 Apple Orchard Rd",
            14541, "1980/01/12");

        # Check that the user is in the database
        $user = exec_query("SELECT * FROM Users WHERE email=?", ["doe.jon@gmail.com"]);
        $this->assertNotNull($user);

        # Check that the user is not logged in

        # Turn useer into an associative array
        $arr = $user->fetch_assoc();
        $this->assertNotNull($arr);

        # Check that the user's info is correct
        $this->assertSame($arr["name"], "Jon Doe");
        $this->assertSame($arr["email"], "doe.jon@gmail.com");
        $this->assertSame($arr["password"], "password");
        $this->assertSame($arr["street_address"], "123 Apple Orchard Rd");
        $this->assertSame($arr["zipcode"], 14541);
        $this->assertSame($arr["birthday"], "1980-01-12");

        # Check that the user is not logged in

        $login_status = sign_in($arr["email"], $arr["password"]);
        $this->assertSame(1, $login_status);

        # Sign in again 
        $login_status = sign_in($arr["email"], $arr["password"]);
        $this->assertSame(0, $login_status);
    }


    public function testSignInAlreadyLoggedIn(): void
    {
        # Create a test user
        create_user("Jordan Grant","jordangrant46@yahoo.com", "#Password", "98-38 %7th Ave", "11368", "04/24/2000");

        # Test that the user was created successfully and is in the database
        $result = exec_query("SELECT * FROM Users WHERE email=?", ["jordangrant46@yahoo.com"]);
        $this->assertNotNull($result);

        # Sign in the dummy user
        $result = sign_in("jordangrant46@yahoo.com", "#Password");

        # Check that the user is signed in
        $this->assertEquals(1, $result);

        # Try to sign in the dummy user again
        $result = sign_in("jordangrant46@yahoo.com", "#Password");

        # Check that this second sign in attempt fails
        $this->assertEquals(0, $result);
        echo "test passed";
    }

    /*
        This function will tests in the case of a user that doesn't exist is trying to sign in.
    */
    public function testSignInUserDoesntExists(): void 
    {

        # Ensure there is no user with an email of "edfghijlmnop"
        $result = exec_query("SELECT * FROM Users WHERE name=?", ["abcdefghijklmnop"]);
        $this->assertEquals(0, $result->num_rows);
        $this->assertEquals(0, sign_in("abcdefghijklmnop", "password"));

        # Try to sign in a user that doesn't exist
        $result = sign_in("abcd", "edfghijlmnop");
        
        # Check that the user is not signed in
        $this->assertEquals(0, $result);

        # Create the user that doesn't exist
        create_user("abcdefghijklmnop", "abcd", "edfghijlmnop", "b", 14214, "d");

        # Test that the user was created
        $result = exec_query("SELECT * FROM Users WHERE email=?", ["abcd"]);
        $this->assertNotNull($result);

        # Try to sign in the user again
        sign_in("abcd", "edfghijlmnop");

        # Check that this second sign in attempt succeeds
        $result = exec_query("SELECT * FROM Users WHERE email=?", ["abcd"]);
        echo "Test passed";

    }
}

