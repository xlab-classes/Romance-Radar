<?php declare(strict_types=1);

# Import db_api.php
require_once('db_api.php');
use PHPUnit\Framework\TestCase;


 final class ChangePaswwordTest extends TestCase {

    public function testUpdatePassword(): void
    {
        $create_result = create_user("Jon Doe","email",password_hash("password",PASSWORD_DEFAULT),"",14214,"");
        $this->assertSame(1, $create_result);

        # Check that the current users password is correct
        $result = exec_query("SELECT * FROM Users WHERE email=?", ["email"]);
        $this->assertNotNull($result);

        # Fetch the user's info
        $arr = $result->fetch_assoc();
        $this->assertNotNull($arr);

        # Check that the user's info is correct
        $this->assertSame($arr["name"], "Jon Doe");
        $this->assertSame($arr["email"], "email");
        $this-> assertTrue(password_verify("password", $arr["password"]));

        update_password(get_user_id("email"),password_hash("password",PASSWORD_DEFAULT), password_hash("new_password",PASSWORD_DEFAULT));
        $result = exec_query("SELECT * FROM Users WHERE email=?", ["email"]);
        $this->assertNotNull($result);
        $this-> assertTrue(password_verify("new_password", $arr["password"]));
    }

    public function testUpdatePasswordSame(): void
    {
        $create_result = create_user("Taro Tanaka","ttanaka@google.com",password_hash("japan",PASSWORD_DEFAULT),"",11367,"");
        $this->assertSame(1, $create_result);

        # Check that the current users password is correct
        $result = exec_query("SELECT * FROM Users WHERE email=?", ["ttanaka@google.com"]);
        $this->assertNotNull($result);

        # Fetch the user's info
        $arr = $result->fetch_assoc();
        $this->assertNotNull($arr);

        # Check that the user's info is correct
        $this->assertSame($arr["name"], "Taro Tanaka");
        $this->assertSame($arr["email"], "email");
        $this-> assertTrue(password_verify("japan", $arr["password"]));

        $this-> assertSame(0,update_password(get_user_id($arr["email"]),$arr["password"],$arr["password"]));
    }
}