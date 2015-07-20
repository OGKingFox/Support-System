<?php
class Mail {

	private $api_user;
	private $api_pass;
	private $mail;
	private $from;
	private $subject;
	private $message;
	private $receiver;
	private $sender;

	public function __construct($mail) {
		$this->mail = $mail;
	}

	public function setApiUser($key) {
		$this->api_user = $key;
	}

	public function setApiPass($pass) {
		$this->api_pass = $pass;
	}

	public function setFromEmail($email) {
		$this->from = $email;
	}

	public function setSubject($subject) {
		$this->subject = $subject;
	}

	public function setMessage($msg) {
		$this->message = $msg;
	}

	public function setReceiver($receiver) {
		$this->receiver = $receiver;
	}

	public function setSender($sender) {
		$this->sender = $sender;
	}
	public function sendMail() {
		$this->mail->IsSMTP();
		$this->mail->Host = 'smtp.mandrillapp.com';
		$this->mail->Port = 587;
		$this->mail->SMTPAuth = true;
		$this->mail->Username = $this->api_user;
		$this->mail->Password = $this->api_pass;
		$this->mail->SMTPSecure = 'tls';
		$this->mail->From = $this->from;
		$this->mail->FromName = $this->sender;
		$this->mail->AddAddress($this->receiver);
		$this->mail->IsHTML(true);
		$this->mail->Subject = $this->subject;
		$this->mail->Body    = nl2br($this->message);
		$this->mail->AltBody = nl2br($this->message);
		return $this->mail->Send();
	}
}