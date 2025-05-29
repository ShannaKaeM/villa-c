---
description: commit to github and local repo
---

# Git Workflow - Sync Local and Remote

This workflow ensures local and GitHub repositories always stay synchronized.

## 1. Add all changes to staging
// turbo
```bash
git add .
```

## 2. Commit changes with descriptive message
```bash
git commit -m "Your commit message here"
```

## 3. Push to GitHub to keep repos in sync
// turbo
```bash
git push
```

## Notes
- This workflow prevents local and remote repos from getting out of sync
- Always commits locally first, then pushes to remote
- Use descriptive commit messages to track changes
