#!/bin/bash

# Auto Git Pull Script untuk Hostinger
# Letakkan di folder: /home/user/public_html/bumisultanapp/

echo "🔄 Starting auto-pull from GitHub..."

# Navigate ke folder app
cd /home/user/public_html/bumisultanapp || exit

# Pull latest changes
echo "📥 Pulling from main branch..."
git pull origin main

# Clear Laravel cache
echo "🧹 Clearing cache..."
php artisan config:cache
php artisan view:clear
php artisan cache:clear

echo "✅ Auto-pull complete!"
