<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once('db_api.php');
# Create a PHPUnit test case class
final class SignInTest extends TestCase
{

    public function tearDown(): void
    {
        exec_query("DELETE FROM Users WHERE email=?", [$this->$email_]);
    }

    # Create a function that tests the db_api.php sign_in function
    public function testSignInNotLoggedIn(): void
    {
        # Create a test user
        create_user(
            "Jon Doe", $this->$email_, password_hash($this->$password_,PASSWORD_DEFAULT), "123 Apple Orchard Rd",
            14541, "1980/01/12");

        # Check that the user is in the database
        $user = exec_query("SELECT * FROM Users WHERE email=?", [$this->$email_]);
        $this->assertNotNull($user);

        # Check that the user is not logged in

        # Turn useer into an associative array
        $arr = $user->fetch_assoc();
        $this->assertNotNull($arr);

        # Check that the user's info is correct
        $this->assertSame($arr["name"], "Jon Doe");
        $this->assertSame($arr["email"], $this->$email_);
        
        # If this fails you can't sign in at all
        $this->assertTrue(password_verify($this->$password_, $arr["password"]));

        $this->assertSame($arr["street_address"], "123 Apple Orchard Rd");
        $this->assertSame($arr["zipcode"], 14541);
        $this->assertSame($arr["birthday"], "1980-01-12");

        # Check that the user is not logged in

        $login_status = sign_in($this->$email_, $this->$password_);
        $this->assertSame(1, $login_status);

        # Sign in again 
        $login_status = sign_in($arr["email"], $arr["password"]);
        $this->assertSame(0, $login_status);

        # Remove entry that was created for this test
        exec_query("DELETE FROM Users WHERE email=?", [$this->$email_]);
    }


    public function testSignInAlreadyLoggedIn(): void
    {

        # Create a test user
        create_user("Jordan Grant", $this->$email_, password_hash($this->$password_, PASSWORD_DEFAULT), "98-38 57th Ave", 11368, "2000/04/24");

        # Test that the user was created successfully and is in the database
        $result = exec_query("SELECT * FROM Users WHERE email=?", [$this->$email_]);
        $this->assertNotNull($result);
        $this->assertNotFalse($result);
        $this->assertSame(1, $result->num_rows, "Wrong number of rows in testSignInAlreadyLoggedIn");

        # Turn user into an associative array
        $arr = $result->fetch_assoc();
        $this->assertNotNull($arr);

        # Check that the user's info is correct
        $this->assertSame($arr["name"], "Jordan Grant");
        $this->assertSame($arr["email"], $this->$email_);
        $this->assertTrue(password_verify($this->$password_ ,$arr["password"]));
        $this->assertSame($arr["street_address"], "98-38 57th Ave");

        # If this fails you can't sign in at all
        $this->assertTrue(password_verify($this->$password_, $arr["password"]));

        # Sign in the dummy user again
        $result = sign_in($this->$email_, $this->$password_);

        # Check that the user is signed in
        $this->assertEquals(1, $result, "Couldn't sign in user in testSignInAlreadyLoggedIn");

        # Try to sign in the dummy user again
        $result = sign_in($this->$email_, $this->$password_);

        # Check that this second sign in attempt fails
        $this->assertEquals(0, $result, "Expected failure succeeded when signing in in testSignInAlreadyLoggedIn");

        exec_query("DELETE FROM Users WHERE email=?", [$this->$email_]);
    }

    /*
        This function will tests in the case of a user that doesn't exist is trying to sign in.
    */
    public function testSignInUserDoesntExists(): void 
    {

        # Ensure there is no user with an email of "edfghijlmnop"
        $result = exec_query("SELECT * FROM Users WHERE name=?", ["abcdefghijklmnop"]);
        $this->assertEquals(0, $result->num_rows);

        # Try to sign in a user that doesn't exist
        $result = sign_in($this->$email_, $this->$password_);
        
        # Check that the user is not signed in
        $this->assertEquals(0, $result);

        # Create the user that doesn't exist
        create_user("abcdefghijklmnop", $this->$email_, password_hash($this->$password_, PASSWORD_DEFAULT), "b", 14214, "2000/04/24");

        # Test that the user was created
        $result = exec_query("SELECT * FROM Users WHERE email=?", [$this->$email_]);
        $this->assertNotNull($result);

        # Verify the user's info
        $arr = $result->fetch_assoc();
        $this->assertSame($arr["name"], "abcdefghijklmnop");
        $this->assertSame($arr["email"], $this->$email_);
        $this->assertTrue(password_verify($this->$password_, $arr["password"]));

        # Try to sign in the user again
        $sign_in_status = sign_in($this->$email_, $this->$password_);


        # Check that this second sign in attempt succeeds
        $result = exec_query("SELECT * FROM Users WHERE email=?", [$this->$email_]);
        $this->assertNotNull($result);
        $this->assertEquals(1, $sign_in_status); 

        echo "Test passed";

        exec_query("DELETE FROM Users WHERE email=?", [$this->$email_]);
    }

    # Tear down function that will execute the Table Create script and Table Destroy script

    private $email_ = "doe.jon@gmail.com";
    private $password_ = "#Password";

}

