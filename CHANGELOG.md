**2017-xx-xx: (3.0.0)**
- Simplified .gitignore.
- Raised version constraints to Symfony >= 3.3 and PHP >= 5.5.9.
- Removed legacy code for compatibility with Symfony < 2.4.
- Switched from PSR-0 to PSR-4.
- Removed useless whitespace and class/method descriptions.
- CsrfRouter no longer extends Symfony Router.
- Removed CsrfTokenManager, the logic was moved to other classes.
- Fixed major performance issue caused by usage of getRouteCollection.

**2016-02-17: (2.0.0)**
- Migrated to Genedys owner

**2015-08-18: (1.0.4)**
- Added configuration to enable/disable the csrf protection and change token field name

**2015-04-23: (1.0.3)**
- Added compatibility with Symfony 2.3

**2015-03-16: (1.0.0 - 1.0.2)**
- Added route caching on ControllerListener

**2015-01-20:**
- First stable version
