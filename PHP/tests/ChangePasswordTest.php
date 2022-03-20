<?php declare(strict_types=1);

# Import db_api.php
require_once('../db_api.php');
use PHPUnit\Framework\TestCase;


 final class ChangePasswordTest extends TestCase {

     # Creating class variables

        public $email_ = "jdoe@meta.org";
        public $password_ = "#Password";
        public $newpwd_ = "new_password";


    public function tearDown(): void
    {
        exec_query("DELETE FROM Users WHERE email=?", [$this->email_]);
    }
    public function testUpdatePassword(): void
    {
        $create_result = create_user("Jon Doe",$this->email_,password_hash($this->password_,PASSWORD_DEFAULT),"123",14214,"1963/12/13");

        

        # Check that the current users password is correct
        $result = exec_query("SELECT * FROM Users WHERE email=?", [$this->email_]);
        $this->assertNotNull($result, "Result is null");

        # Fetch the user's info
        $arr = $result->fetch_assoc();
        $this->assertNotNull($arr);


        # Check that the user's info is correct
        $this->assertSame($arr["name"], "Jon Doe", "Name is incorrect");
        $this->assertSame($arr["email"], $this->email_, "Email is incorrect");
        $this-> assertTrue(password_verify($this->password_, $arr["password"]), "Password is not correct");

        update_password(get_user_id($this->email_),$arr["password"], password_hash($this->newpwd_,PASSWORD_DEFAULT));

        $result = exec_query("SELECT * FROM Users WHERE email=?", [$this->email_]);
        $this->assertNotNull($result, "Result is null");

        # Fetch the user's info again
        $arr = $result->fetch_assoc();
        $this->assertNotNull($arr);

        $verified = password_verify($this->newpwd_, $arr["password"]);

        $this-> assertTrue($verified, "Password is not correct");

        exec_query("DELETE FROM Users WHERE email=?", [$this->email_]);
    }

    public function testUpdatePasswordSame(): void
    {
        $create_result = create_user("Taro Tanaka",$this->email_,password_hash($this->password_,PASSWORD_DEFAULT),"address",11367,"2000/01/01");

        # Check that the current users password is correct
        $result = exec_query("SELECT * FROM Users WHERE email=?", ["$this->email_"]);
        $this->assertNotNull($result, "Result is null");

        # Fetch the user's info
        $arr = $result->fetch_assoc();
        $this->assertNotNull($arr);

        # Check that the user's info is correct
        $this->assertSame($arr["name"], "Taro Tanaka", "Name is incorrect");
        $this->assertSame($arr["email"], $this->email_, "Email is incorrect");
        $this-> assertTrue(password_verify($this->password_, $arr["password"]), "Password is not correct");

        $update_status = update_password(get_user_id($this->email_),$arr["password"], password_hash($this->password_,PASSWORD_DEFAULT));
        $this->assertSame(1, $update_status, "Passwords was not updated");

        exec_query("DELETE FROM Users WHERE email=?", [$this->email_]);
    }
}