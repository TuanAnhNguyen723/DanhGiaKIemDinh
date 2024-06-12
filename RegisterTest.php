<?php
use PHPUnit\Framework\TestCase;

class RegisterTest extends TestCase
{
    protected function setUp(): void
    {
        // Thiết lập môi trường kiểm thử
        $_SERVER["REQUEST_METHOD"] = "POST";
        $_POST = [];
    }

    // 
    public function testEmptyUsername()
    {
        $_POST = [
            'username' => '',
            'email' => 'test@example.com',
            'password' => 'somepassword',
            'confirm_password' => 'somepassword'
        ];

        ob_start();
        include 'register.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Please enter a username.', $output);
    }

    public function testEmptyEmail()
    {
        $_POST = [
            'username' => 'testuser',
            'email' => '',
            'password' => 'somepassword',
            'confirm_password' => 'somepassword'
        ];

        ob_start();
        include 'register.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Please enter an email.', $output);
    }

    public function testInvalidEmail()
    {
        $_POST = [
            'username' => 'testuser',
            'email' => 'invalidemail',
            'password' => 'somepassword',
            'confirm_password' => 'somepassword'
        ];

        ob_start();
        include 'register.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Please enter a valid email address.', $output);
    }

    public function testExistingEmail()
    {
        // Chú ý: Cần có dữ liệu giả lập trong cơ sở dữ liệu để kiểm thử trường hợp này
        $_POST = [
            'username' => 'testuser',
            'email' => 'existing@example.com',
            'password' => 'somepassword',
            'confirm_password' => 'somepassword'
        ];

        ob_start();
        include 'register.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('This email is already taken.', $output);
    }

    public function testEmptyPassword()
    {
        $_POST = [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => '',
            'confirm_password' => 'somepassword'
        ];

        ob_start();
        include 'register.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Please enter a password.', $output);
    }

    public function testShortPassword()
    {
        $_POST = [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => '12345',
            'confirm_password' => '12345'
        ];

        ob_start();
        include 'register.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Password must have at least 6 characters.', $output);
    }

    public function testMismatchedPasswords()
    {
        $_POST = [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'password1',
            'confirm_password' => 'password2'
        ];

        ob_start();
        include 'register.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Password did not match.', $output);
    }

    public function testSuccessfulRegistration()
    {
        $_POST = [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'somepassword',
            'confirm_password' => 'somepassword'
        ];

        ob_start();
        include 'register.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Registration successful', $output);
    }
}
?>
