# Black Line Guardian Fund Theme

A custom WordPress theme developed for the Black Line Guardian Fund by My Biz Niche.

## Description

This is a custom WordPress theme designed specifically for the Black Line Guardian Fund website. It provides a tailored experience with modern design and functionality.

**Version:** 1.0.5  
**Author:** My Biz Niche  
**Author URI:** https://www.mybizniche.com/  
**License:** GPL2  
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html  
**Text Domain:** blacklineguardianfund-theme

## Installation

1. Download the theme zip file or clone the repository
2. Upload the theme folder to `/wp-content/themes/` directory
3. Activate the theme through the WordPress admin dashboard under **Appearance > Themes**
4. Configure theme options as needed

## Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher
- Composer (for development dependencies)

## Features

- Custom design tailored for Black Line Guardian Fund
- Responsive layout
- Modern CSS framework integration
- Optimized performance
- Accessibility compliant

## Development

This theme uses Composer for dependency management and includes development tools for code quality.

### Dependencies

The theme uses the following vendor packages:

- **yahnis-elsts/plugin-update-checker** – Handles update checks from GitHub repository

### Development Dependencies

- **wp-coding-standards/wpcs** – WordPress PHP coding standards
- **dealerdirect/phpcodesniffer-composer-installer** – PHPCS installer

### Adding or Changing Vendor Packages

1. Run `composer require <package>` or edit `composer.json` and run `composer update`
2. For packages that might be loaded by other plugins/themes (e.g. PUC), keep the `class_exists()` guard before `require_once ... vendor/autoload.php` so only one autoload runs

### Linting (WordPress PHP Standards)

- Run linting: `composer run lint` or `./vendor/bin/phpcs`
- Auto-fix issues: `composer run lint:fix` or `./vendor/bin/phpcbf`
- Security scan: `composer run lint:security`
- All-in-one check: `composer run lint:run`

To block merges when linting fails, enable branch protection and require the **Lint** status check. See [.github/BRANCH_PROTECTION.md](.github/BRANCH_PROTECTION.md) for details.

## Changelog

### 1.0.5
- Latest updates and improvements

### 1.0.0
- Initial release

## License

This theme is licensed under the GPL2 License. See [License URI](https://www.gnu.org/licenses/gpl-2.0.html) for details.

## Credits

- **Developer:** My Biz Niche
- **Theme URI:** https://github.com/MBNDEV/blacklineguardianfund-theme

## Support

For support or questions, please contact My Biz Niche at https://www.mybizniche.com/
