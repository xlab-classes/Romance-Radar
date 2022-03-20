<?php declare(strict_types=1);

# Import db_api.php
require_once('../db_api.php');
require_once('../profile_page.php');
use PHPUnit\Framework\TestCase;

final class TestUpdatePersonalDetails extends TestCase
{
    # Class varibles represnetaive of the user's personal information.
    private $name = "John Doe";
    private $street_address = "123 Main Street";
    private $zip = 12345;
    private $city = "Anytown";
    private $email = "jdoe@faang.co";
    private $password = "password";
    private $birthday = "1990-01-01";
    private $id;

    function setUp(): void
    {
        create_user($this->name, $this->email, $this->password, $this->street_address, $this->city, $this->zip, $this->birthday);
        $this->id = get_user_id($this->email);
    }

    function tearDown(): void
    {
        exec_query("DELETE FROM Users WHERE name=? AND email=?", [$this->name, $this->email]);
    }

    

    public function testUpdateName()
    {

        # Serach for the user in the database via their email.
        $our_user = exec_query("SELECT * FROM Users WHERE email=?" , [$this->email]);
        
        # Get a assoc array of the user's information.
        $our_user_info = $our_user->fetch_assoc();

        # Assert that the user's name is correct.
        $this->assertEquals($our_user_info['name'],$this->name, "The user's name is incorrect.");

        # Update the user's name.
        $user = update_name($this->id, "Jane Doe");

        $this->name = "Jane Doe";
        
        # Assert that $user is 1. Indcicating that the user was updated.
        $this->assertEquals(1, $user, "User was not updated.");

        # Serach for the user in the database via their email again.
        $user = exec_query("SELECT * FROM Users WHERE email =?", [$this->email]);

        # Get a assoc array of the user's information.
        $user = $user->fetch_assoc();
        
        $this->assertEquals("Jane Doe", $user['name'], "The user's name was not updated.");

    }

    public function testUpdateEmail()
    {

        # Serach for the user in the database via their user_id.
        $user = exec_query('SELECT * FROM Users WHERE id =?', [$this->id]);
       
        # assert that the query returned a result.
        $this->assertNotNull($user);

        # Get a assoc array of the user's information.
        $user = $user->fetch_assoc();

        # Assert that the user's email is correct.
        $this->assertEquals($this->email, $user['email'], "The user's email is not correct.");

        # Update the user's email.
        $user = update_email($this->id, "johndoe@gmail.com");
        # Assert that $user is 1. Indcicating that the user's email was updated.

        $this->name = "johndoe@gmail.com";

        # Serach for the user in the database again via their name and password.
        $user = exec_query("SELECT * FROM Users WHERE email =?", ["johndoe@gmail.com"]);
        
        # assert that the query returned a result.
        $this->assertNotNull($user, "The user was not found.");

        # Get a assoc array of the user's information.
        $user_info = $user->fetch_assoc();

        # Assert that the user's fetched info is correct.
        $this->assertNotNull($user_info, "The user's info was not found.");

        $this->assertEquals("johndoe@gmail.com", $user_info['email'], "The user's email was not updated.");
   
    }

    public function testUpdateDateOfBirth()
    {

        # Serach for the user in the database via their user_id.
        $user = exec_query("SELECT * FROM Users WHERE id=?",[$this->id]);
       
        # assert that the query returned a result.
        $this->assertNotNull($user, "The user was not found.");

        # Get a assoc array of the user's information.
        $user = $user->fetch_assoc();

        # Assert that the users info was found.
        $this->assertNotNull($user, "The user's info was not found.");

        # Assert that the user's email is correct.
        $this->assertEquals($this->birthday, $user['birthday'], "The user's bday is not correct.");

        # Update the user's email.
        $user = update_dob($this->id, "2000-01-01");

        
        # Assert that $user is 1. Indcicating that the user's email was updated.
        $this->assertEquals(1, $user, "The user's date of birth was not updated.");

        # Serach for the user in the database again via their user_id.
        $user = exec_query("SELECT * FROM Users WHERE id =?", [$this->id]);

        # Get a assoc array of the user's information.
        $user = $user->fetch_assoc();

        $this->assertEquals("2000-01-01", $user['birthday'], "The user's bday was not updated.");

    }

    public function testUpdateAddress()
    {

        # Serach for the user in the database via their user_id.
        $user = exec_query("SELECT * FROM Users WHERE id =?", [$this->id]);
       
        # assert that the query returned a result.
        $this->assertNotNull($user);

        # Get a assoc array of the user's information.
        $user = $user->fetch_assoc();

        # Assert that the user's info was found.
        $this->assertNotNull($user, "The user's info was not found.");

        # Assert that the user's email is correct.
        $this->assertEquals($this->zip, $user['zipcode'], "The user's zipcode is not correct.");

        # Update the user's email.
        $user = update_address($this->id, 11368);
        
        # Assert that $user is 1. Indcicating that the user's email was updated.
        $this->assertEquals(1, $user, "The user's address was not updated.");

        # Serach for the user in the database again via their user_id.
        $user = exec_query("SELECT * FROM Users WHERE id =?" ,[$this->id]);

        # Get a assoc array of the user's information.
        $user = $user->fetch_assoc();

        $this->assertEquals(11368, $user['zipcode'], "The user's address was not updated.");
    }

}