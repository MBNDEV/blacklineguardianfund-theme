# Black Line Guardian Fund Theme

Custom WordPress theme for My Biz Niche.

## Theme Details

- Theme Name: `Black Line Guardian Fund Theme`
- Description: `Custom Theme for MBN`
- Version: `1.0.2`
- Author: `My Biz Niche`
- Theme URI: [https://github.com/MBNDEV/blacklineguardianfund-theme](https://github.com/MBNDEV/blacklineguardianfund-theme)
- Author URI: [https://www.mybizniche.com/](https://www.mybizniche.com/)
- License: `GPL2` - [GPL-2.0](https://www.gnu.org/licenses/gpl-2.0.html)
- Text Domain: `blacklineguardianfund-theme`

## Requirements

- WordPress (current supported version)
- PHP compatible with WordPress requirements
- Node.js & npm (for building Gutenberg blocks)
- Composer (for development tooling)

## Installation

1. Copy or clone this theme into `wp-content/themes/blacklineguardianfund-theme`.
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Install Node dependencies:
   ```bash
   npm install
   ```
4. Build Gutenberg blocks:
   ```bash
   npm run build
   ```
5. In WordPress Admin, go to **Appearance > Themes** and activate **Black Line Guardian Fund Theme**.

## Development

### Block Development

This theme uses **native WordPress Gutenberg blocks** with React and Tailwind CSS.

**Start development server with hot reload:**
```bash
npm run start
```

**Build for production:**
```bash
npm run build
```

See [blocks/README.md](blocks/README.md) for detailed block development guide.

### Figma to Gutenberg Blocks

This theme supports **Figma MCP integration** for converting designs directly to blocks.

**Quick Setup:**
1. Get your Figma Personal Access Token: https://www.figma.com/developers/api#access-tokens
2. Copy `.vscode/mcp-settings.json.template` to your MCP settings
3. Add your token to the configuration
4. See [.github/FIGMA_MCP_SETUP.md](.github/FIGMA_MCP_SETUP.md) for complete instructions

**Usage:**
```
@wp-gutenberg-dev Create a hero block from this Figma design:
https://www.figma.com/file/YOUR_FILE_ID
```

### PHP Development

This theme uses Composer autoloading for vendor packages.

- Primary package in use:
  - `yahnis-elsts/plugin-update-checker`
- Autoload is conditionally loaded in `functions.php` to avoid duplicate class loading.

## Update Checker

The theme includes GitHub-based update checks through Plugin Update Checker.

- Repository configured in code:
  - [https://github.com/MBNDEV/blacklineguardianfund-theme](https://github.com/MBNDEV/blacklineguardianfund-theme)
- Slug configured in code:
  - `blacklineguardianfund-theme`

## Linting

Run WordPress coding standards checks before committing:

- `composer run lint`
- `composer run lint:fix`
- `composer run lint:security`
- `composer run lint:run`

## Security

Please review `SECURITY.md` for:

- supported versions
- vulnerability reporting process
- enforced secure coding standards
