<?php

namespace NSWDPC\UserForms\IpCollection;

use SilverStripe\Core\Extension;
use SilverStripe\Control\Email\Email;
use SilverStripe\UserForms\Model\Recipient\EmailRecipient;

 /**
  * During the submission process, if the recipient requires IP addresses, add that to emailData
  * @author James
  */
 class AddIPForRecipientExtension extends Extension {

     /**
      * Update the email data with the IP found, for use in templates
      *
      */
     public function updateEmailData(&$emailData, $attachments) {
         $ip = IP::getByPriority();
         $emailData['OriginatingIP'] = $ip;
     }

     /**
      * Update email based on recipient configuration
      */
     public function updateEmail(Email $email, EmailRecipient $recipient, &$emailData) {
         if($recipient && $recipient->IncludeOriginatingIPAddress == 0) {
             // remove IP if recipient is not getting it
             unset($emailData['OriginatingIP']);
             $email->removeData('OriginatingIP');
         }
     }

 }
