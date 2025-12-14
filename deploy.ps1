#!/usr/bin/env pwsh

# Auto Git Deploy Script
# Penggunaan: ./deploy.ps1 "pesan commit"

param(
    [string]$message = "Auto-update: $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')"
)

Write-Host "🔄 Starting auto-deploy..." -ForegroundColor Cyan

# Check git status
Write-Host "`n📊 Git Status:" -ForegroundColor Yellow
git status

# Add all changes
Write-Host "`n📝 Adding all files..." -ForegroundColor Yellow
git add .

# Commit
Write-Host "`n💾 Committing..." -ForegroundColor Yellow
git commit -m "$message"

# Push to GitHub
Write-Host "`n🚀 Pushing to GitHub..." -ForegroundColor Yellow
git push origin main

Write-Host "`n✅ Deploy complete!" -ForegroundColor Green
Write-Host "⏳ Hostinger will auto-pull in a few seconds..." -ForegroundColor Cyan
