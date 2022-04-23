<?php declare(strict_types=1);

# Import db_api.php
require_once('../db_api.php');
use PHPUnit\Framework\TestCase;

final class TestCreateUser extends TestCase
{

    private $name = "Jon Doe";
    private $email = "jondoe@gmail.com";
    private $password = "password";
    private $address = "123 Apple Orchard Rd";
    private $city = "Buffalo";
    private $zipcode = 12345;
    private $birthday = "1999-12-12";

    // Called after every test function. Should contain cleanup code
    public function tearDown(): void
    {
        exec_query("DELETE FROM Users WHERE email=?", [$this->email]);
    }

    // Ensure that we're able to add a new user to the database when that
    // user does not already exist
    public function testUserDoesntExist(): void
    {
        // Create a new user. Ensure return value indicates succcess
        $create_result = create_user(
            $this->name, $this->email, $this->password, $this->address, $this->city, $this->zipcode, $this->birthday);
        $this->assertSame(1, $create_result);

        // Grab this user's row from the database. Ensure it's a non-empty row
        $result = exec_query("SELECT * FROM Users WHERE email=?", [$this->email]);
        $this->assertNotNull($result);
        $this->assertNotFalse($result);
        $this->assertSame(1, $result->num_rows);

        // Get a key:value mapping from the fetched data
        $arr = $result->fetch_assoc();
        $this->assertNotNull($arr);
        $this->assertNotFalse($arr);
        
        // Make sure that the data entered is the same data that appears from
        // our SELECT query
        $this->assertSame($arr["name"], $this->name);
        $this->assertSame($arr["email"], $this->email);
        $this->assertSame($arr["password"], $this->password);
        $this->assertSame($arr["street_address"], $this->address);
        $this->assertSame($arr["zipcode"], $this->zipcode);
        $this->assertSame($arr["birthday"], $this->birthday);
    }

    // Ensure that we cannot create a new user when one with the same email
    // already exists
    public function testUserExists(): void
    {
        // This should succeed, since this user should NOT be in the database
        // already
        $initial_create_result = create_user(
            $this->name, $this->email, $this->password, $this->address, $this->city, $this->zipcode, $this->birthday);
        $this->assertEquals($initial_create_result, 1);

        // Attempt to create the same user a second time
        $create_again_result = create_user(
            $this->name, $this->email, $this->password, $this->address, $this->city, $this->zipcode, $this->birthday);
        
        # The call to create_user should fail
        $this->assertEquals($create_again_result, 0);
    }

    // Test that an entry is added to the Connection_requests table, where
    // the value of sent_from is the new user's ID
    public function testEmptyConnectionRequest(): void
    {
        // Create a user and ensure the return value indicates success
        $create_result = create_user(
            $this->name, $this->email, $this->password, $this->address, $this->city, $this->zipcode, $this->birthday);
        $this->assertEquals($create_result, 1);

        $user_id = get_user_id($this->email);

        // Ensure that an 'empty' connection request is added to the
        // Connection_requests table for this user
        $qres = exec_query("SELECT * FROM Connection_requests WHERE sent_from=?", [$user_id]);
        $this->assertNotNull($qres);
        $this->assertNotFalse($qres);

        // There should be exactly 1 row in the Connection_requests table
        // that has sent_from set to this user's ID
        $this->assertSame(1, $qres->num_rows);

        // Get a key:value mapping from our query result
        $arr = $qres->fetch_assoc();
        $this->assertNotNull($arr);
        $this->assertNotFalse($arr);

        // Initially, connection request will exist from a user to themself. This
        // indicates there is no actual connection request currently
        $this->assertEquals($arr["sent_from"], $user_id);
        $this->assertEquals($arr["sent_to"], $user_id);
    }

    // Test that a user's partner is themself when they are first created
    public function testInitialPartner(): void
    {
        // Create a user and ensure the return value indicates success
        $create_result = create_user(
            $this->name, $this->email, $this->password, $this->address, $this->city, $this->zipcode, $this->birthday);
        $this->assertEquals($create_result, 1);

        $user_id = get_user_id($this->email);

        // Ensure that the user has a partner that is themselves
        $qres = exec_query("SELECT * FROM Users WHERE id=?", [$user_id]);
        $this->assertNotNull($qres);
        $this->assertNotFalse($qres);

        // There should be exactly 1 row in the Users table that matches this ID
        $this->assertSame(1, $qres->num_rows);

        // Get a key:value mapping from our query result
        $arr = $qres->fetch_assoc();
        $this->assertNotNull($arr);
        $this->assertNotFalse($arr);

        // Partner is themself
        $this->assertEquals($arr["partner"], $user_id);
    }    

}


?>
