#!/bin/bash

# Simple Git Sync Script
# This keeps your local and GitHub repositories perfectly synchronized

echo "🔄 Starting Git Sync..."

# Step 1: Get latest changes from GitHub
echo "📥 Getting latest changes from GitHub..."
git pull origin main

# Step 2: Add all your changes
echo "📝 Adding your changes..."
git add .

# Step 3: Check if there are changes to commit
if git diff --staged --quiet; then
    echo "✅ No changes to commit - everything is already synced!"
else
    # Ask for commit message
    echo "💬 Enter a description of what you changed:"
    read commit_message
    
    # Commit changes
    echo "💾 Committing changes..."
    git commit -m "$commit_message"
    
    # Push to GitHub
    echo "☁️ Pushing to GitHub..."
    git push origin main
    
    echo "✅ Everything synced successfully!"
fi

echo "🎉 Local and GitHub are now perfectly synchronized!"
