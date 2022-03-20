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
    private $zip = "14224";
    private $bday = "1999/12/12";
    private $user_id;

    function sendRequestToServer($data): void
    {
        $url = "https://www-student.cse.buffalo.edu/CSE442-542/2022-Spring/cse-442j/PHP/registration.php";
        $options = array(
            "http" => array(
                "header" => "Content-type: application/x-www-form-urlencoded\r\n",
                "method" => "POST",
                "content" => http_build_query($data)
            )
        );

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        if ($result == FALSE) {
            echo "Uh oh.\n";
        }
    }

    function setUp(): void
    {
        start_session();
        create_user($this->name, $this->email, $this->pass, $this->addr, $this->zip, $this->bday);
        $this->user_id = get_user_id($this->email);
        $_SESSION["user"]["id"] = $this->user_id;
    }

    function tearDown(): void
    {
        exec_query("DELETE FROM Users WHERE name=? AND email=?", [$this->name, $this->email]);
    }

    // Test updating max cost, max distane, preferred date length
    function testUpdatePreferences(): void
    {
        $data = array();
        $data["MaxCost"] = "100";
        $data["MaxDist"] = "10";
        $data["PreDateLen"] = "2";

        start_session();
        $_SESSION["user"]["id"] = $user_id;

        sendRequestToServer($data);

        $prefs = get_preferences($this->user_id);
        $this->assertSame($prefs["Date_preferences"]["cost"], "100");
        $this->assertSame($prefs["Date_preferences"]["distance"], "10");
        $this->assertSame($prefs["Date_preferences"]["length"], "2");
    }

}