<?php declare(strict_types=1);

# Import db_api.php
require_once('db_api.php');
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



    public function testUpdatePersonalDetailsNoDetailsChanged()
    {
        # Create a user for the sake of testing
        create_user(
            $this->name, $this->email, 
            password_hash($this->password, PASSWORD_DEFAULT),
            $this->address, $this->zip, $this->bday
        );

        # Get the user's id
        $user_id = get_user_id($this->email);

        # Update the user's personal information with no changes
        update_personal_details($user_id);

        # Get the user's personal information
        $user = get_user_by_id($user_id);

        # Assert that the user's personal information is the same as before
        $this->assertEquals($this->name, $user['name']);
        $this->assertEquals($this->address, $user['address']);
        $this->assertEquals($this->zip, $user['zip']);
        $this->assertEquals($this->city, $user['city']);
        $this->assertEquals($this->bday, $user['bday']);
    }

    public function testUpdatePersonalDetailsNameChanged()
    {
    }

    public function testUpdatePersonalDetailsAddressChanged()
    {

    }

    public function testUpdatePersonalDetailsCityChange()
    {
        
    }

}