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
        $test_user = create_user("test", "test", "a", "b", 14214, "d");

        # Test that the user was created
        $this->assertEquals(1, $test_user);

        #Check that the user isn't already signed in
        $this->assertEquals(0, sign_in("test", "test"));
        
        # Sign in the dummy user
        $result = sign_in($user);
        
        # Check that the user is signed in
        $this->assertEquals(1, $result);
        echo "Test passed";
    }

    public function testSignInAlreadyLoggedIn(): void
    {
        # Create a test user
        $test_user = create_user("test", "test", "a", "b", 14214, "d");

        # Test that the user was created
        $this->assertEquals(1, $test_user);

        # Sign in the dummy user
        $result = sign_in("test", "test");

        # Check that the user is signed in
        $this->assertEquals(1, $result);

        # Try to sign in the dummy user again
        $result = sign_in("test", "test");

        # Check that this second sign in attempt fails
        $this->assertEquals(0, $result);
        echo "test passed";
    }

    /*
        This function will tests in the case of a user that doesn't exist is trying to sign in.
    */
    public function testSignInUserDoesntExists(): void 
    {
        # Try to sign in a user that doesn't exist
        $result = sign_in("abcdefghijklmnop", "doesntexist");
        
        # Check that the user is not signed in
        $this->assertEquals(0, $result);

        # Create the user that doesn't exist
        $test_user = create_user("abcdefghijklmnop", "doesntexist", "a", "b", 14214, "d");

        # Test that the user was created
        $this->assertEquals(1, $test_user);

        # Try to sign in the dummy user again
        $result = sign_in("abcdefghijklmnop", "doesntexist");

        # Check that this second sign in attempt succeeds
        $this->assertEquals(1, $result);
        echo "Test passed";

    }
}

