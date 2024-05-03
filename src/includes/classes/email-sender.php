<?php
class EmailSender
{
    private $mail;

    public function __construct($mail)
    {
        $this->mail = $mail;
    }

    public function sendApprovalEmail($recipientEmail)
    {
        $mailBody = 'Good day, Iskolar!<br><br>
            
        We’re pleased to inform you that your account has been <b>approved</b>! You can now access and log in to <a href="http://localhost/PUPSRC-AutomatedElectionSystem/src/landing-page.php"> iVOTE</a>. <br><br>
            
        If you have any questions or need assistance, please contact the support team at ivotepupsrc@gmail.com. ';

        error_log($recipientEmail);
        return $this->sendEmail($recipientEmail, 'iVOTE Account Approval', $mailBody);
    }

    public function sendRejectionEmail($recipientEmail, $reason, $otherReason = '')
    {
        $mailBody = 'Good day, Iskolar!<br><br>
            
        We regret to inform you that your recent account registration has been rejected. <br><br>
            
        <b>Reason for rejection:</b> ';

        if ($reason == 'reason1') {
            $mailBody .= 'Student is not part of the organization';
        } elseif ($reason == 'reason2') {
            $mailBody .= 'The PDF is low quality and illegible';
        } elseif ($reason == 'others') {
            $mailBody .= htmlspecialchars($otherReason);
        }

        $mailBody .= '<br><br>If you have any questions or need assistance, please contact the support team at ivotepupsrc@gmail.com. </h5>';

        return $this->sendEmail($recipientEmail, 'iVOTE Registration Rejected', $mailBody);
    }


    public function sendPasswordEmail($recipientEmail, $password) {
        $subject = 'iVOTE Admin Account Created and Password';
        $mailBody = "Hello, Committee Member.<br><br>";
        $mailBody .= "We're pleased to inform you that your account has been successfully created. 
        Below, you'll find your generated password. <br><br>";

        $mailBody .= "Password: $password <br><br>";
        $mailBody .= "For security purposes, we recommend that you log in to your account and change your passsword
        after your first login. If you have any questions or need assistance, contact the support team at ivotepupsrc@gmail.com.";
    
        return $this->sendEmail($recipientEmail, $subject, $mailBody);
    }


    private function sendEmail($recipientEmail, $subject, $body)
    {
        try {
            $this->mail->setFrom('ivotepupsrc@gmail.com', 'iVOTE');
            $this->mail->addAddress($recipientEmail);
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}