<?php declare(strict_types=1);

# Import db_api.php
require_once('../db_api.php');
require_once('../profile_page.php');
use PHPUnit\Framework\TestCase;

final class TestUpdatePersonalDetails extends TestCase
{
    # Class varibles represnetaive of the user's personal information.
    private $name = "John Doe";
    private $address = "123 Main Street";
    private $zip = 12345;
    private $city = "Anytown";
    private $email = "jdoe@faang.co";
    private $password = "password";
    private $bday = "1990/01/01";



    public function testUpdateName()
    {
        # Create a new test user.
        $user = create_user($this->name, $this->address, $this->zip, $this->city, $this->email, $this->password, $this->bday);

        # Assert that the user was created.
        $this->assertNotNull($user, "User was not created.");

        # Serach for the user in the database via their email.
        $user = SELECT * FROM users WHERE email = $this->email;

        # Get a assoc array of the user's information.
        $user = $user->fetch_assoc();

        # Assert that the user's name is correct.
        $this->assertEquals($this->name, $user['name']);

        # Update the user's name.
        $user = update_name($user, "Jane Doe");
        # Assert that $user is 1. Indcicating that the user was updated.
        $this->assertEquals(1, $user, "User was not updated.");

        # Serach for the user in the database via their email again.
        $user = SELECT * FROM users WHERE email = $this->email;

        # Get a assoc array of the user's information.
        $user = $user->fetch_assoc();
        
        $this->assertEquals("Jane Doe", $user['name'], "The user's name was not updated.");

        exec_query("DELETE FROM Users WHERE email=?", [$this->email_]);

    }

    public function testUpdateEmail()
    {
        # Create a new test user.
        $user = create_user($this->name, $this->address, $this->zip, $this->city, $this->email, $this->password, $this->bday);

        # Assert that the user was created.
        $this->assertNotNull($user);

        # Serach for the user in the database via their user_id.
        $user = SELECT * FROM users WHERE user_id = get_user_id($this->email);
       
        # Get a assoc array of the user's information.
        $user = $user->fetch_assoc();

        # Assert that the user's email is correct.
        $this->assertEquals($this->email, $user['email'], "The user's email is not correct.");

        # Update the user's email.
        $user = update_email($user, "johndoe@buffalo.edu"
        # Assert that $user is 1. Indcicating that the user's email was updated.

        # Serach for the user in the database again via their user_id.
        $user = SELECT * FROM users WHERE user_id = get_user_id($this->email);

        # Get a assoc array of the user's information.
        $user = $user->fetch_assoc();

        $this->assertEquals("johndoe@buffalo.edu", $user['email'], "The user's email was not updated.");
    }

    public function testUpdateDateOfBirth()
    {
        # Create a new test user.
        $user = create_user($this->name, $this->address, $this->zip, $this->city, $this->email, $this->password, $this->bday);

        # Assert that the user was created.
        $this->assertNotNull($user);

        # Serach for the user in the database via their user_id.
        $user = SELECT * FROM users WHERE user_id = get_user_id($this->email);
       
        # Get a assoc array of the user's information.
        $user = $user->fetch_assoc();

        # Assert that the user's email is correct.
        $this->assertEquals($this->bday, $user['bday'], "The user's bday is not correct.");

        # Update the user's email.
        $user = update_bday($user, "2000/01/01");
        
        # Assert that $user is 1. Indcicating that the user's email was updated.
        $this->assertEquals(1, $user, "The user's date of birth was not updated.");

        # Serach for the user in the database again via their user_id.
        $user = SELECT * FROM users WHERE user_id = get_user_id($this->email);

        # Get a assoc array of the user's information.
        $user = $user->fetch_assoc();

        $this->assertEquals("2000/01/01", $user['bday'], "The user's bday was not updated.");

    }

    public function testUpdateAddress()
    {
        # Create a new test user.
        $user = create_user($this->name, $this->address, $this->zip, $this->city, $this->email, $this->password, $this->bday);

        # Assert that the user was created.
        $this->assertNotNull($user);

        # Serach for the user in the database via their user_id.
        $user = SELECT * FROM users WHERE user_id = get_user_id($this->email);
       
        # Get a assoc array of the user's information.
        $user = $user->fetch_assoc();

        # Assert that the user's email is correct.
        $this->assertEquals($this->address, $user['address'], "The user's address is not correct.");

        # Update the user's email.
        $user = update_address($user, "123 Main Street");
        
        # Assert that $user is 1. Indcicating that the user's email was updated.
        $this->assertEquals(1, $user, "The user's address was not updated.");

        # Serach for the user in the database again via their user_id.
        $user = SELECT * FROM users WHERE user_id = get_user_id($this->email);

        # Get a assoc array of the user's information.
        $user = $user->fetch_assoc();

        $this->assertEquals("123 Main Street", $user['address'], "The user's address was not updated.");
    }

}