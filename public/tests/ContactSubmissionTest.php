<?php

use PHPUnit\Framework\TestCase;

class ContactSubmissionTest extends TestCase {
    
    public function test_object_initiation() {

        require_once __DIR__ . '/../class/ContactSubmission.php';
        require __DIR__ . '/../config.php';
        
        $ContactSubmission = new \ContactSubmission($config);

        $this->assertClassHasAttribute('smtp_user', 'ContactSubmission');
        $this->assertClassHasAttribute('smtp_pass', 'ContactSubmission');
        $this->assertClassHasAttribute('smtp_server', 'ContactSubmission');
        $this->assertClassHasAttribute('smtp_port', 'ContactSubmission');

        $this->assertClassHasAttribute('mail_to', 'ContactSubmission');

        $this->assertClassHasAttribute('db', 'ContactSubmission');
        $this->assertClassHasAttribute('db_host', 'ContactSubmission');
        $this->assertClassHasAttribute('db_user', 'ContactSubmission');
        $this->assertClassHasAttribute('db_password', 'ContactSubmission');
        $this->assertClassHasAttribute('charset', 'ContactSubmission');

    }

    public function test_config_variables_exist() {

        require __DIR__ . '/../config.php';

        $this->assertArrayHasKey('smtp_user', $config);
        $this->assertArrayHasKey('smtp_pass', $config);
        $this->assertArrayHasKey('smtp_server', $config);
        $this->assertArrayHasKey('smtp_port', $config);

        $this->assertArrayHasKey('smtp_mail_to', $config);

        $this->assertArrayHasKey('db', $config);
        $this->assertArrayHasKey('db_host', $config);
        $this->assertArrayHasKey('db_user', $config);
        $this->assertArrayHasKey('db_password', $config);
        $this->assertArrayHasKey('charset', $config);

    }

    public function test_sanitization() {

        require_once __DIR__ . '/../class/ContactSubmission.php';
        require __DIR__ . '/../config.php';

        $_POST = array('data' => array(
            'first_name' => 'Lucas',
            'last_name' => 'Healy',
            'email' => 'lucash@healy.com',
            'phone' => '234-432-1234',
            'message' => 'This is my message! Enjoy Guy Smiley. Keep the smile!',
            ),
        );

        $ContactSubmission = new \ContactSubmission($config);
        $ContactSubmission->sanitize_input();

        $this->assertNotEmpty($ContactSubmission->first_name);
        $this->assertNotEmpty($ContactSubmission->last_name);
        $this->assertNotEmpty($ContactSubmission->email);
        $this->assertNotEmpty($ContactSubmission->phone);
        $this->assertNotEmpty($ContactSubmission->message);

        // Now without a phone
        $_POST['data']['phone'] = '';

        $ContactSubmissionNoPhone = new \ContactSubmission($config);
        $ContactSubmissionNoPhone->sanitize_input();

        $this->assertNotEmpty($ContactSubmissionNoPhone->first_name);
        $this->assertNotEmpty($ContactSubmissionNoPhone->last_name);
        $this->assertNotEmpty($ContactSubmissionNoPhone->email);
        $this->assertEmpty($ContactSubmissionNoPhone->phone);
        $this->assertNotEmpty($ContactSubmissionNoPhone->message);        

    }

    public function test_save_input() {

        require_once __DIR__ . '/../class/ContactSubmission.php';
        require __DIR__ . '/../config.php';

        $_POST = array('data' => array(
            'first_name' => 'Lucas',
            'last_name' => 'Healy',
            'email' => 'lucash@healy.com',
            'phone' => '234-432-1234',
            'message' => 'This is my message! Enjoy Guy Smiley. Keep the smile!',
            ),
        );

        $ContactSubmission = new \ContactSubmission($config);
        $ContactSubmission->sanitize_input();
        $results = $ContactSubmission->save_input();

        $this->assertTrue($results);

        // Now without a phone
        $_POST = array('data' => array(
            'first_name' => 'asqwerqfdasdfqwefqwefasd',
            'last_name' => 'Heasdfdfqwefqwfasdfasdfaly',
            'email' => 'lucasdafeqweqefawefqfassdfh@healy.com',
            'phone' => '',
            'message' => 'This is my message! Enjoy Guy Smiley. Keep the smile!',
            ),
        );

        $ContactSubmission->sanitize_input();
        $results = $ContactSubmission->save_input();

        $this->assertTrue($results);

    }

    public function test_email_templates() {

        $this->assertFileExists(__DIR__.'/../templates/email/contact_form_html.php');
        $this->assertFileExists(__DIR__.'/../templates/email/contact_form_text.php');

    }

}
