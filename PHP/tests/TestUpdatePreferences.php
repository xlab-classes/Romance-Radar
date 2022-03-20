<?php declare(strict_types=1);

# Import db_api.php
require_once('../db_api.php');
use PHPUnit\Framework\TestCase;

final class TestUpdatePreferences extends TestCase
{

    private $name = "Alex Eastman";
    private $email = "nunya@gmail.com";
    private $pass = "password";
    private $addr = "123 Cherry Road";
    private $city = "Buffalo";
    private $zip = "14224"
    private $bday = "1999/12/12";
    private $user_id;

    function setUp(): void
    {
        create_user($this->name, $this->email, $this->pass, $this->addr, $this->city, $this->zip, $this->bday);
        $this->user_id = get_user_id($this->email);
    }

    function tearDown(): void
    {
        exec_query("DELETE FROM Users WHERE name=? AND email=?", [$this->name, $this->email]);
    }

    // Test updating max cost, max distane, preferred date length
    function testBasic(): void
    {
        $data = array();
        $data["Date_preferences"]["cost"] = "100";
        $data["Date_preferences"]["distance"] = "10";
        $data["Date_preferences"]["length"] = "2";

        update_preferences($this->user_id, $data);

        $prefs = get_preferences($this->user_id);
        $this->assertSame($prefs["Date_preferences"]["cost"], "100");
        $this->assertSame($prefs["Date_preferences"]["distance"], "10");
        $this->assertSame($prefs["Date_preferences"]["length"], "2");
    }

}