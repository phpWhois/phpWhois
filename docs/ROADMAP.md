Development roadmap
===================

General ideas
-------------
Originally phpWhois was developed a long ago having support of PHP4 in mind.

Now it is needed to update the codebase to support the modern features like:

- [Composer](https://getcomposer.org/ "Composer")
- Continuous integration service [Travis](https://travis-ci.org/ "Travis") 
  with [PHPUnit](https://phpunit.de/ "PHPUnit")
- Code quality testing with [Scrutinizer](https://scrutinizer-ci.com "Scrutinizer")
- Throw exceptions
- Add support for binding source ip

Code must be reworked to support 
[PSR standards](http://www.php-fig.org/ "PSR standards").

Current major version of phpWhois is **4**. Global new features will be 
implemented in major version **5**.

It is good idea to keep the backwards compatibility for version 5. However 
applications using version 4 may need to be updated because of using 
namespaced classes in version 5.

New TLDs
--------
Last few years there was a bunch of new top-level domains spinned by ICANN, 
including international domains with non-latin symbols.

New TLDs should be monitored and added to phpWhois database.

There are some 'default' whois servers which names are like `whois.tld`, 
`nic.tld`. We can rely on these names or explicitly set the names in tlds list

Documentation
-------------
Having in mind ICANN's great plans for running new TLDs there should be more 
detailed documentation regarding contributing to repository.

Also we should pay attention to the internal code documenting, which later 
can be compiled into API documentation with 
[PHPDocumentor](http://www.phpdoc.org/ "PHPDocumentor").

Optional features
-----------------
Maybe there should be additional methods implemented like `getDaysToExpire` 
which will be reporting a number of days left to domain expiration.