org.civicoop.exactonline
========================

### Use CiviCRM with Exact Online

This CiviCRM extension provides (some) integration with (Dutch) accounting software [Exact Online](https://www.exact.com).  
This extension is work in progress and is currently being developed for one specific client!

### Installation and usage

This extension requires this Exact API client library: [picqer/exact-php-client](https://github.com/picqer/exact-php-client).    
It is *not* included with this extension: run `composer install -o` in the extension directory to get it and its dependencies, before enabling the extension.

Once the install is completed, go to `civicrm/financial/exactonline/settings` to configure the Exact integration.

