# Hostinger Auto-Deployment Setup

## GitHub Repository Secrets Configuration

You need to add these secrets to your GitHub repository:

1. Go to your GitHub repository
2. Click on **Settings** tab
3. Click on **Secrets and variables** → **Actions**
4. Click **New repository secret** and add each of these:

### Required Secrets:

- **FTP_SERVER**: Your Hostinger FTP server (e.g., `ftp.yourdomain.com` or IP address)
- **FTP_USERNAME**: Your FTP username
- **FTP_PASSWORD**: Your FTP password  
- **FTP_PORT**: Your FTP port (usually `21`)

## How to Get Your FTP Details:

1. **Login to Hostinger Control Panel**
2. Go to **File Manager** or **FTP Accounts**
3. Note down:
   - FTP Server/Host
   - FTP Username
   - FTP Password
   - FTP Port (usually 21)

## Deployment Process:

1. **Push to GitHub**: When you push code to `main` or `master` branch
2. **Automatic Deployment**: GitHub Actions will:
   - Install PHP dependencies
   - Generate Laravel key
   - Set proper permissions
   - Upload files via FTP to Hostinger
   - Exclude unnecessary files (git, node_modules, logs, etc.)

## Files Excluded from Deployment:

- `.git/` and `.github/`
- `node_modules/`
- `tests/`
- `*.md` files
- `*.log` files
- `storage/logs/`
- `storage/framework/cache/`
- `storage/framework/sessions/`
- `storage/framework/views/`
- `.env` and `.env.example`
- Lock files

## Manual Deployment Commands (if needed):

```bash
# On Hostinger server, run these after deployment:
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
```

## Troubleshooting:

- Check GitHub Actions logs if deployment fails
- Ensure FTP credentials are correct
- Verify Hostinger server has PHP 8.1+ and Composer
- Check file permissions on Hostinger
