API Basic HTTP authentication bundle
====================================

[![Build Status](https://travis-ci.org/Ang3/api-basic-http-auth-bundle.svg?branch=master)](https://travis-ci.org/Ang3/api-basic-http-auth-bundle) 
[![Latest Stable Version](https://poser.pugx.org/ang3/api-basic-http-auth-bundle/v/stable)](https://packagist.org/packages/ang3/api-basic-http-auth-bundle) 
[![Latest Unstable Version](https://poser.pugx.org/ang3/api-basic-http-auth-bundle/v/unstable)](https://packagist.org/packages/ang3/api-basic-http-auth-bundle) 
[![Total Downloads](https://poser.pugx.org/ang3/api-basic-http-auth-bundle/downloads)](https://packagist.org/packages/ang3/api-basic-http-auth-bundle)

This bundles provides an authenticator to allow a 
[basic HTTP authentication](https://en.wikipedia.org/wiki/Basic_access_authentication) for your API's.

> In basic HTTP authentication, a request contains a header field in the form of 
```Authorization: Basic <credentials>```  where credentials is the Base64 encoding of ID and password joined 
> by a single colon ```:``` - **Wikipedia**

Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require ang3/api-basic-http-auth-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Configure the bundle
----------------------------

Edit the file ```config/packages/security.yaml``` to configure the security depending on the firewal 
you want to secure:

```yaml
security:
        main:
            guard:
                authenticators:
                    - Ang3\Bundle\ApiBasicHttpAuthBundle\Security\BasicHttpAuthenticator
```

Usage
=====

The authenticator will check credentials from a username and API key as password.
That's why you just have to ensure that your user class extends the interface 
```Ang3\Bundle\ApiBasicHttpAuthBundle\Security\ApiUserInterface```.

```php
use Ang3\Bundle\ApiBasicHttpAuthBundle\Security\ApiUserInterface;

class User implements ApiUserInterface
{
    // ...

    public function getApiKey(): ?string
    {
        // TODO: Implement getApiKey() method.
    }
}
```

### User status checking (optional)

The bundle provides the interface ```Ang3\Bundle\ApiBasicHttpAuthBundle\Security\LockableUserInterface``` and
it contains just one method: ```isDisabled(): bool```.

If your user entity implements this interface, then the authenticator will call this method to check the user account 
validity:

```php
use Ang3\Bundle\ApiBasicHttpAuthBundle\Security\LockableUserInterface;

class User implements LockableUserInterface
{
    // ...

    public function isDisabled(): bool
    {
        // TODO: Implement isDisabled() method.
    }
}
```

### Encode Basic HTTP authentication token

The authentication system is waiting for a Base64 encoded string. This bundle provides a simple command 
to encode a username and password easily:

```shell script
php bin/console security:api:basic-http-auth-token <username> <password>
```

For example with username ```admin``` and password ```password```, the token to use is ```YWRtaW46cGFzc3dvcmQ=```.

That's it!