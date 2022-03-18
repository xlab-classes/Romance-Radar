<?php declare(strict_types=1);

# Import db_api.php
require_once('db_api.php');
use PHPUnit\Framework\TestCase;


 final class ChangePasswordTest extends TestCase {

    public function testUpdatePassword(): void
    {
        $create_result = create_user("Jon Doe","doejohn@meta.org",password_hash("password",PASSWORD_DEFAULT),"123",14214,"1963/12/13");
        # Check that the current users password is correct
        $result = exec_query("SELECT * FROM Users WHERE email=?", ["doejohn@meta.org"]);
        $this->assertNotNull($result);

        # Fetch the user's info
        $arr = $result->fetch_assoc();
        $this->assertNotNull($arr);

        # Check that the user's info is correct
        $this->assertSame($arr["name"], "Jon Doe");
        $this->assertSame($arr["email"], "doejohn@meta.org");
        $this-> assertTrue(password_verify("password", $arr["password"]));

        # Echo with a newline the password
        echo $arr["password"] . "\n";

        update_password(get_user_id("doejohn@meta.org"),$arr["password"], password_hash("new_password",PASSWORD_DEFAULT));
        $result = exec_query("SELECT * FROM Users WHERE email=?", ["doejohn@meta.org"]);
        $this->assertNotNull($result);
        
        # Echo with a newline the password
        echo $arr["password"] . "\n";
        
        // $this-> assertTrue(password_verify("new_password", $arr["password"]));
    }

    // public function testUpdatePasswordSame(): void
    // {
    //     $create_result = create_user("Taro Tanaka","ttanaka@google.com",password_hash("japan",PASSWORD_DEFAULT),"address",11367,"2000/01/01");

    //     # Check that the current users password is correct
    //     $result = exec_query("SELECT * FROM Users WHERE email=?", ["ttanaka@google.com"]);
    //     $this->assertNotNull($result);

    //     # Fetch the user's info
    //     $arr = $result->fetch_assoc();
    //     $this->assertNotNull($arr);

    //     # Check that the user's info is correct
    //     $this->assertSame($arr["name"], "Taro Tanaka");
    //     $this->assertSame($arr["email"], "ttanaka@google.com");
    //     $this-> assertTrue(password_verify("japan", $arr["password"]));

    //     $this-> assertSame(0,update_password(get_user_id($arr["email"]),"japan","japan"));
    // }
}