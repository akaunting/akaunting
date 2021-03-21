# Change Log


## 1.0.2 - 2015-12-19

### Added

- Request and Response factory binding types to Puli


## 1.0.1 - 2015-12-17

### Added

- Puli configuration and binding types


## 1.0.0 - 2015-12-15

### Added

- Response Factory in order to be reused in Message and Server Message factories
- Request Factory

### Changed

- Message Factory extends Request and Response factories


## 1.0.0-RC1 - 2015-12-14

### Added

- CS check

### Changed

- RuntimeException is thrown when the StreamFactory cannot write to the underlying stream


## 0.3.0 - 2015-11-16

### Removed

- Client Context Factory
- Factory Awares and Templates


## 0.2.0 - 2015-11-16

### Changed

- Reordered the parameters when creating a message to have the protocol last,
as its the least likely to need to be changed.


## 0.1.0 - 2015-06-01

### Added

- Initial release

### Changed

- Helpers are renamed to templates
