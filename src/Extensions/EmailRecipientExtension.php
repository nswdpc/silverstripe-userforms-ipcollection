<?php

namespace NSWDPC\UserForms\IpCollection;

use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\Fieldlist;
use SilverStripe\ORM\DataExtension;

/**
 * Adds option to include OriginatingIP in the email to this recipient
 * @author James
 */
class EmailRecipientExtension extends DataExtension {

    /**
     * @var array
     */
    private static $db = [
        'IncludeOriginatingIPAddress' => 'Boolean'
    ];

    /**
     * @var array
     */
    private static $defaults = [
        'IncludeOriginatingIPAddress' => 0
    ];

    /**
     * Add field, prior to 'HideFormData'
     */
    public function updateCmsFields(Fieldlist $fields) {
        $fields->insertAfter(
            'HideFormData',
            CheckboxField::create(
                'IncludeOriginatingIPAddress',
                _t(
                    __CLASS__ . '.INCLUDE_ORIGINATING_IP_ADDRESS',
                    'Include the originating IP address when delivering submissions to this recipient'
                )
            )
        );
    }


}
