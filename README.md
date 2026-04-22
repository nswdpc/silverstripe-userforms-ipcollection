# Add originating IP addresses to user form submissions

This modules allows messages generated from user form submissions to include an `OriginatingIP`, on a per-recipient basis.

## Installation

```
composer require nswdpc/silverstripe-userforms-ipcollection
```

## License

[BSD-3-Clause](./LICENSE.md)

## Documentation

All user form email templates in your project or theme (`SubmittedFormEmail.ss`) should handle including the {$OriginatingIP} template variable. These templates can usually be found in the `./app/templates/email/` directory of a project or its theme(s).

```html
<% if $OriginatingIP %>
<p>Originating IP: {$OriginatingIP}</p>
<% end_if %>
```

### After installation

1. For every recipient who should receive an originating IP (or IPs), check the 'Include the originating IP address' checkbox on the "Email Content" tab.

When a submission is made, the originating IP will be determined based on the request.

The Originating IP may not be a single IP address, for instance it could include any number of proxy IPs.

## Maintainers

+ PD Web Team


## Bugtracker

We welcome bug reports, pull requests and feature requests on the Github Issue tracker for this project.

Please review the [code of conduct](./code-of-conduct.md) prior to opening a new issue.

## Security

If you have found a security issue with this module, please email digital[@]dpc.nsw.gov.au in the first instance, detailing your findings.

## Development and contribution

If you would like to make contributions to the module please ensure you raise a pull request and discuss with the module maintainers.

Please review the [code of conduct](./code-of-conduct.md) prior to completing a pull request.
