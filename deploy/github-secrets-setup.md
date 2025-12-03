# GitHub Secrets Setup for Hostinger Deployment

## Your FTP Credentials:
- **FTP Server**: 217.21.72.13
- **FTP Username**: u776666570.silver-chicken-145733.hostingersite.com
- **FTP Password**: onesys_v2
- **FTP Port**: 21

## Step-by-Step GitHub Secrets Configuration:

### 1. Go to Your GitHub Repository
- Navigate to your repository on GitHub
- Click on the **Settings** tab (top menu)

### 2. Access Secrets Section
- In the left sidebar, click **Secrets and variables**
- Click **Actions**

### 3. Add Each Secret
Click **New repository secret** for each of these:

#### Secret 1: FTP_SERVER
- **Name**: `FTP_SERVER`
- **Value**: `217.21.72.13`

#### Secret 2: FTP_USERNAME  
- **Name**: `FTP_USERNAME`
- **Value**: `u776666570.silver-chicken-145733.hostingersite.com`

#### Secret 3: FTP_PASSWORD
- **Name**: `FTP_PASSWORD` 
- **Value**: `onesys_v2`

#### Secret 4: FTP_PORT
- **Name**: `FTP_PORT`
- **Value**: `21`

## After Adding Secrets:

1. **Commit and push** the deployment files to your repository
2. **Push to main/master branch** to trigger the first deployment
3. **Check GitHub Actions** tab to see deployment progress
4. **Monitor logs** for any issues

## Expected Deployment Process:

1. ✅ Code pushed to GitHub
2. ✅ GitHub Actions workflow starts
3. ✅ PHP dependencies installed
4. ✅ Laravel key generated
5. ✅ Files uploaded to Hostinger via FTP
6. ✅ Your site is updated automatically!

## Troubleshooting:

- If deployment fails, check the **Actions** tab in GitHub
- Verify all secrets are added correctly
- Ensure Hostinger server supports PHP 8.1+
- Check file permissions on Hostinger

## Manual Commands (if needed on Hostinger):

After successful deployment, you may need to run these on your Hostinger server:

```bash
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
```

Your auto-deployment is now ready! 🚀
