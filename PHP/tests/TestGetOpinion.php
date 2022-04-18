<?php declare(strict_types=1);

require_once('../db_api.php');
use PHPUnit\Framework\TestCase;

final class TestGetOpinion extends TestCase
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
        $this->assertEquals(0, get_opinion($this->id, 1), "Opinion not neutral");
        $this->assertEquals(1, like_date($this->id, 1), "Couldn't like date");
        $this->assertEquals(1, get_opinion($this->id, 1), "Opinion not positive");
        $this->assertEquals(1, unlike_date($this->id, 1), "Couldn't unlike date");
        $this->assertEquals(1, dislike_date($this->id, 1), "Couldn't dislike date");
        $this->assertEquals(-1, get_opinion($this->id, 1), "Opinion not negative");
    }

}