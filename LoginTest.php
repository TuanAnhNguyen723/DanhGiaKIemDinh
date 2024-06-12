<?php
use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    protected function setUp(): void
    {
        // Thiết lập môi trường kiểm thử
        $_SERVER["REQUEST_METHOD"] = "POST";
        $_POST = [];
    }

    public function testEmptyEmail()
    {
        $_POST = [
            'email' => '',
            'password' => 'somepassword'
        ];

        ob_start();
        include 'login.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Please enter an email.', $output);
    }

    public function testInvalidEmail()
    {
        $_POST = [
            'email' => 'invalidemail',
            'password' => 'somepassword'
        ];

        ob_start();
        include 'login.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Please enter a valid email address.', $output);
    }

    public function testEmptyPassword()
    {
        $_POST = [
            'email' => 'validemail@example.com',
            'password' => ''
        ];

        ob_start();
        include 'login.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Please enter your password.', $output);
    }

    public function testSuccessfulLogin()
    {
        // Giả lập người dùng trong cơ sở dữ liệu
        global $link;
        $email = 'bach2@gmail.com';
        $password = '123456';
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $link->query("INSERT INTO users (username, email, password, role) VALUES ('bach2', '$email', '$password_hash', 'user')");

        $_POST = [
            'email' => $email,
            'password' => $password
        ];

        ob_start();
        include 'login.php';
        $output = ob_get_clean();

        $this->assertStringNotContainsString('The password you entered was not valid.', $output);

        // Xóa người dùng sau khi kiểm thử
        $link->query("DELETE FROM users WHERE email = '$email'");
    }

    public function testInvalidPassword()
    {
        // Giả lập người dùng trong cơ sở dữ liệu
        global $link;
        $email = 'bach3@gmail.com';
        $password = '123456';
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $link->query("INSERT INTO users (username, email, password, role) VALUES ('bachacb', '$email', '$password_hash', 'user')");

        $_POST = [
            'email' => $email,
            'password' => 'invalidpassword'
        ];

        ob_start();
        include 'login.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('The password you entered was not valid.', $output);

        // Xóa người dùng sau khi kiểm thử
        $link->query("DELETE FROM users WHERE email = '$email'");
    }
}
