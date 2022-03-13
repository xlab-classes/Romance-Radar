<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once(db_api.php);

# Create a PHPUnit test case class
final class SignInTest extends TestCase
{

    # Create a function that tests the db_api.php sign_in function
    public function testSignIn(): void
    {
        # Create a test user
        $test_user = create_user("test", "test");

        # Test that the user was created
        $this->assertEquals(1, $test_user);

        #Check that the user isn't already signed in
        $this->assertEquals(0, sign_in("test", "test"));
        
        # Sign in the dummy user
        $result = sign_in($user);
        $this->assertEquals(1, $result);
    }

}

