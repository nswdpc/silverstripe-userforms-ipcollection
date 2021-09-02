# Add originating IP addresses to user form submissions

This modules allows messages generated from user form submissions to include an `OriginatingIP`, on a per-recipient basis.

## Installation

```
composer require nswdpc/silverstripe-userforms-ipcollection
```


## License

[BSD-3-Clause](./LICENSE.md)

## Documentation

After installation:

1. For every recipient who should receive an originating IP (or IPs), check the 'Include the originating IP address' checkbox
2. When a submission is made, the originating IP will be determined. REMOTE_ADDR will be used as a fallback.
3. All user form email templates in your project should handle include the {$OriginatingIP} template variable, these can usually be found in the templates/email/ directory of a project theme(s).

```html
<% if $OriginatingIP %>
<p>Originating IP: {$OriginatingIP}</p>
<% end_if %>
```

With the same for plain-text templates.

The Originating IP may not be a single IP address, for instance it could include any number of proxy IPs.

## Configuration

By default, the module will attempt to get an IP first from the Cloudflare `CF-Connecting-IP` header, then from the `X-Forwarded-For` header. If you don't use Cloudflare, it's unlikely that this header will be present and so it will be ignored.

You can modify the priority and expected headers in project configuration:

```yaml
---
Name: 'reset-userforms-ipcollection'
After:
    - '#nswdpc-userforms-ipcollection'
# reset IP
NSWDPC\UserForms\IpCollection\IP:
    ip_priority: []
---
Name: 'app-userforms-ipcollection'
After:
    - '#reset-userforms-ipcollection'
---
# Set project-based IP priorities
NSWDPC\UserForms\IpCollection\IP:
  ip_priority:
    - 'SOME_OTHER_HEADER'
    - 'X_FORWARDED_FOR'
```

## Maintainers

+ [dpcdigital@NSWDPC:~$](https://dpc.nsw.gov.au)


## Bugtracker

We welcome bug reports, pull requests and feature requests on the Github Issue tracker for this project.

Please review the [code of conduct](./code-of-conduct.md) prior to opening a new issue.

## Security

If you have found a security issue with this module, please email digital[@]dpc.nsw.gov.au in the first instance, detailing your findings.

## Development and contribution

If you would like to make contributions to the module please ensure you raise a pull request and discuss with the module maintainers.

Please review the [code of conduct](./code-of-conduct.md) prior to completing a pull request.
