<?php

namespace NSWDPC\UserForms\IpCollection;

use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;

/**
 * Adds option to include OriginatingIP in the email to this recipient
 * @author James
 * @property bool $IncludeOriginatingIPAddress
 * @extends \SilverStripe\ORM\DataExtension<(\SilverStripe\UserForms\Model\Recipient\EmailRecipient & static)>
 */
class EmailRecipientExtension extends DataExtension
{
    private static array $db = [
        'IncludeOriginatingIPAddress' => 'Boolean'
    ];

    private static array $defaults = [
        'IncludeOriginatingIPAddress' => 0
    ];

    /**
     * Add field, prior to 'HideFormData'
     */
    public function updateCmsFields(FieldList $fields)
    {
        $fields->insertAfter(
            'HideFormData',
            CheckboxField::create(
                'IncludeOriginatingIPAddress',
                _t(
                    self::class . '.INCLUDE_ORIGINATING_IP_ADDRESS',
                    'Include the originating IP address when delivering submissions to this recipient'
                )
            )
        );
    }


}
