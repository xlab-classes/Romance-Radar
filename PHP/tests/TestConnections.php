<?php declare(strict_types=1);

# Import db_api.php
require_once('../db_api.php');
use PHPUnit\Framework\TestCase;

final class TestUpdatePersonalDetails extends TestCase
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
    private $street_address = "123 Main Street";
    private $zip_b = 67891;
    private $city = "Finger Lakes";
    private $password = "password";
    private $birthday = "2000-01-21";


    function setUp(): void
    {
        create_user($this->connection_a, $this->email_a, $this->password_a, $this->addresss_a, $this->city_a, $this->zip_a, $this->birthday_a);
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

}