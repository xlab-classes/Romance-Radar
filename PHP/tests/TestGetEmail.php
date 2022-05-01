<?php declare(strict_types=1);

require_once('../db_api.php');
use PHPUnit\Framework\TestCase;

final class TestGetEmail extends TestCase
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
        $email = get_email($this->id);
        $this->assertNotNull($email, "Email was returned as NULL");
        $this->assertEquals("string", gettype($email),
            "Email was returned as " . gettype($email) . " instead of string");
        $this->assertEquals("jon_doe@yahoo.co", $email,
            "Email retrieved was not email set");
    }

}