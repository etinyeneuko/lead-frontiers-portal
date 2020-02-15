<?php
/**
 * This script is the Notify Class
 *
 * PHP version 7.2
 *
 * @category Notification_Class
 * @package  Notification_Class
 * @author   Benson Imoh,ST <benson@stbensonimoh.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://stbensonimoh.com
 */
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
/**
 * This is the Notify Class
 *
 * @category Notify_Class
 * @package  Notify_Class
 * @author   Benson Imoh,ST <benson@stbensonimoh.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://stbensonimoh.com
 */
class Notify
{
    /**
     * Constructor Function
     *
     * @param string  $smstoken      The API token for the SMS
     * @param string  $emailHost     The hostname of the email server
     * @param string  $emailUsername The email address
     * @param string  $emailPassword The email Password
     * @param integer $SMTPDebug     If you'd like to see debug information
     * @param boolean $SMTPAuth      If you'd like to authenticate via SMTP
     * @param string  $SMTPSecure    Certificate Type
     * @param integer $Port          Port Number
     */
    public function __construct($emailHost, $emailUsername, $emailPassword, $SMTPDebug, $SMTPAuth, $SMTPSecure, $Port)
    {
        $this->emailHost = $emailHost;
        $this->emailUsername = $emailUsername;
        $this->emailPassword = $emailPassword;
        $this->SMTPDebug = $SMTPDebug;
        $this->SMTPAuth = $SMTPAuth;
        $this->SMTPSecure = $SMTPSecure;
        $this->Port = $Port;
    }


    /**
     * Sends Email notification via PHPMailer
     *
     * @param string $fromEmail The From Email Address
     * @param string $fromName  The From Name
     * @param string $toEmail   The To Email Address
     * @param string $toName    The To Name
     * @param string $emailBody The Email Body
     * @param string $subject   The Email Subject
     *
     * @return void
     */
    public function viaEmail($fromEmail, $fromName, $toEmail, $toName, $emailBody, $subject)
    {
        $mail = new PHPMailer(true);

        // Server settings
        $mail->SMTPDebug = $this->SMTPDebug;
        $mail->isSMTP();
        $mail->Host = $this->emailHost;
        $mail->SMTPAuth = $this->SMTPAuth;
        $mail->Username = $this->emailUsername;
        $mail->Password = $this->emailPassword;
        $mail->SMTPSecure = $this->SMTPSecure;
        $mail->Port = $this->Port;

        // Send the Email
        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($toEmail, $toName);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $emailBody;

        $mail->send();
    }
}
