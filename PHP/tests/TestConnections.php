<?php declare(strict_types=1);

# Import db_api.php
require_once('../db_api.php');
use PHPUnit\Framework\TestCase;

final class TestConnections extends TestCase
{
    private $id_a;
    private $connection_a = "Alex";
    private $email_a = "alex@yahoo.co";
    private $address_a = "123 Side Street";
    private $zip_a = 12345;
    private $city_a = "Buffalo";
    private $password_a = "password";
    private $birthday_a = "1999-12-12";

    private $id_b;
    private $connection_b = "Hazel";
    private $email_b = "hazel@yahoo.co";
    private $address_b = "123 Main Street";
    private $zip_b = 67891;
    private $city_b = "Finger Lakes";
    private $password_b = "password";
    private $birthday_b = "2000-01-21";


    function setUp(): void
    {
        create_user($this->connection_a, $this->email_a, $this->password_a, $this->address_a, $this->city_a, $this->zip_a, $this->birthday_a);
        create_user(
            $this->connection_b, $this->email_b, $this->password_b, $this->address_b, $this->city_b, $this->zip_b, $this->birthday_b
        );

        $this->id_a = get_user_id($this->email_a);
        $this->id_b = get_user_id($this->email_b);
    }

    function tearDown(): void
    {
        exec_query("DELETE FROM Users WHERE name=? AND email=?", [$this->connection_a, $this->email_a]);
        exec_query("DELETE FROM Users WHERE name=? AND email=?", [$this->connection_b, $this->email_b]);
    }

    function testAddConnectionRequest(): void
    {
        // Send a request from user A to user B
        add_connection_request($this->id_a, $this->id_b);

        // Get the connections requests row from the Connection_requests table
        // for user B
        $result = exec_query("SELECT * FROM Connection_requests WHERE id=?", [$this->id_b]);

        $this->assertNotNull($result, "Result was null after executing query on Connection_requests table");
        $this->assertGreaterThan($result->num_rows, 0);


        $arr = $result->fetch_assoc();
        $requesting_id = $arr["sent_from"];
        $receiving_id = $arr["sent_to"];

        // Make sure that user A received the request and user B sent the
        // request
        $this->assertEquals($this->id_a, $receiving_id, "Receiving id (A's ID) is wrong");
        $this->assertEquals($this->id_b, $requesting_id, "Requesting id (B's ID) is wrong");
    }

}