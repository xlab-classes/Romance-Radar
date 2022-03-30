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
        // $result = exec_query("SELECT * FROM Connection_requests WHERE id=?", [$this->id_b]);
        $result = exec_query("SELECT * FROM Connection_requests", []);

        $this->assertNotNull($result, "Result was null after executing query on Connection_requests table");
        $this->assertGreaterThan(0, $result->num_rows);

        $arr = $result->fetch_assoc();
        $requesting_id = $arr["sent_from"];
        $receiving_id = $arr["sent_to"];
        $normal_id = $arr["id"];

        echo "REQUESTING ID IS " . $requesting_id . "\n";
        echo "RECEIVING ID IS " . $receiving_id . "\n";
        echo "NORMAL ID IS " . $normal_id . "\n";

        // Make sure that user B received the request and user A sent the
        // request
        $this->assertEquals($this->id_b, $receiving_id, "Receiving id (A's ID) is wrong");
        $this->assertEquals($this->id_a, $requesting_id, "Requesting id (B's ID) is wrong");
    }

}