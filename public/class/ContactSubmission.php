<?php

class ContactSubmission {
	
	public $smtp_user;
	public $smtp_pass;
	public $smtp_server;
	public $smtp_port;
	public $smtp_host;

	public $mail_to;

	public $first_name = '';
	public $last_name = '';
	public $email = '';
	public $phone = NULL;
	public $message = '';

	public $db = '';
	public $db_host = '';
	public $db_user = '';
	public $db_password = '';
	public $charset = '';

	public function __construct($config) {

		// SMTP Info
		$this->smtp_user = $config['smtp_user'];
		$this->smtp_pass = $config['smtp_pass'];
		$this->smtp_server = $config['smtp_server'];
		$this->smtp_port = $config['smtp_port'];

		$this->mail_to = $config['smtp_mail_to'];

		// DB Info
		$this->db = $config['db'];
		$this->db_host = $config['db_host'];
		$this->db_user = $config['db_user'];
		$this->db_password = $config['db_password'];
		$this->charset = $config['charset'];

	}

	public function sanitize_input() {

		$expected_input = array(
			'first_name',
			'last_name',
			'email',
			'phone',
			'message'
		);

		if(isset($_POST['data'])) {
			foreach($_POST['data'] as $key => $input) {
				if(in_array($key, $expected_input) && $input != '') {
					$this->$key = strip_tags($input);
				}
			}
		}

	}

	public function save_input() {

		$dsn = "mysql:host=$this->db_host;dbname=$this->db;charset=$this->charset";
		$opt = [
				PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
				PDO::ATTR_EMULATE_PREPARES   => false,
		];

		try {

			$pdo = new PDO($dsn, $this->db_user, $this->db_password, $opt);
			$insert = $pdo->prepare("INSERT INTO contact_form_submit (first_name, last_name, email, phone, message) VALUES (:first_name, :last_name, :email, :phone, :message)");
			$insert->execute(
				array(
					'first_name' => $this->first_name,
					'last_name' => $this->last_name,
					'email' => $this->email,
					'phone' => $this->phone,
					'message' => $this->message,
				)
			);

			return true;

		} catch(Exception $e) {

			return false;

		}

	}

	public function send_email($mailer) {

		$mailer->IsSMTP();
		$mailer->Host = $this->smtp_server;
		$mailer->SMTPAuth = true;
		$mailer->SMTPSecure = 'ssl';
		$mailer->Username = $this->smtp_user;
		$mailer->Password = $this->smtp_pass;
		$mailer->Port = $this->smtp_port;
		$mailer->IsHTML(true);

		ob_start();
    require_once '../templates/email/contact_form_html.php';
    $html_body = ob_get_contents();
    ob_end_clean();

		ob_start();
    require_once '../templates/email/contact_form_text.php';
    $text_body = ob_get_contents();
    ob_end_clean();

		$mailer->From = $this->email;
		$mailer->Sender = $this->email;
		$mailer->FromName = $this->first_name . ' ' . $this->last_name;
		
		$mailer->AddAddress($this->mail_to);
		$mailer->Subject = "Contact Form Submission";
		$mailer->Body    = $html_body;
		$mailer->AltBody = $text_body;

		if(!$mailer->Send()) {
			return false;
		}
		return true;
	}

}