# Release Notes

## [v5.1.0](https://github.com/Sleon4/Lion-Mailer/compare/v5.0.0...v5.1.0) (2023-06-26)

### Added
- docker configuration added
- added account function for account change in LionMailer\Services\Symfony\Mail class

## [v5.0.0](https://github.com/Sleon4/Lion-Mailer/compare/v4.3.0...v5.0.0) (2023-05-31)

### Added
- mail class structure has been modified to support multiple services such as PHPMailer and support for the use of multiple accounts
- functions have been added to add a priority type to the email for the phpmailer service
- added symfony service to send emails
- the use of services has been validated in each service
- added name parameter in embeddedImage function
- added multiple parameters for cc function
- added multiple parameters for bcc function
- addAccount function has been added to add an account during execution
- function has been added to display the available accounts

## Chagned
- isHtml function has been removed from the phpmailer service

## [v4.3.0](https://github.com/Sleon4/Lion-Mailer/compare/v4.2.0...v4.3.0) (2023-05-24)

### Added
- function has been added to add headers

### Changed
- phpmailer library has been updated to v6.8.0
