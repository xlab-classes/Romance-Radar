<?php declare(strict_types=1);

# Import db_api.php
require_once('db_api.php');
use PHPUnit\Framework\TestCase;

# Create a function that tests the db_api.php sign_in function
function test_sign_in() {

    # Check that the database doesn't have a user with the username 'test' and the password 'test'
    $result = sign_in('test', 'test');
    if ($result) {
        # There should be no user with the username 'test' and the password 'test'
        echo 'User already signed in.';
        echo 'Tests failed.';
        # Fail the test
        return false;

    } else {

    # Create a dummy user using the db_api.php create_user function
    $user = create_user('test_user', 'test_password');
    
    # Sign in the dummy user
    $result = sign_in($user);

    # Check that the user is signed in
    if ($result) {
        # The user is signed in
        echo 'The user is signed in.';
        echo 'Tests passed.';
        # Pass the test
        return true;

    } else {
        # The user is not signed in
        echo 'The user is not signed in.';
        echo 'Tests failed.';
        # Fail the test
        return false;
    }
    }


}

final class CreateUserTest extends TestCase
{

    public function testCreateUser(): void
    {
        $create_result = create_user(
            "Jon Doe", "jon.doe@gmail.com", "password", "123 Apple Orchard Rd",
            14541, "12/01/1980");
        $this->assertSame(0, $create_result);
    }

}


?>
