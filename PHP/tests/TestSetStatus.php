<?php declare(strict_types=1);

# Import db_api.php
require_once('../db_api.php');
use PHPUnit\Framework\TestCase;

final class TestSetStatus extends TestCase
{
    private $id;
    private $prefs;
    private $name = "Jon Doe";
    private $email = "jon_doe@yahoo.co";
    private $address = "123 Side Street";
    private $zip = 12345;
    private $city = "Buffalo";
    private $password = "password";
    private $birthday = "1999-12-12";

    // This function is run *before every unit test*
    function setUp(): void
    {
        create_user(
            $this->name, $this->email, $this->password, $this->address, $this->city, $this->zip, $this->birthday
        );

        // Set the member variable id
        $this->id = get_user_id($this->email);
        $this->assertGreaterThan(0, $this->id, "Error getting ID of user in setUp() function");
    }

    // This function is run *after every unit test*
    function tearDown(): void
    {
        delete_user($this->id);
    }

	function testBasic(): void
	{
		$this->assertEquals(1, set_status($this->id, ""), "Couldn't set empty string as status");
		$this->assertEquals(1, set_status($this->id, "A"), "Couldn't set non-empty string as status");
		$this->assertEquals(0, set_status($this->id, "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa"), "Erroneously set long string as status");
	}

}