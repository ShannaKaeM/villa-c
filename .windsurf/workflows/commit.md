---
description: Git Commit and Sync Workflow - Keep Local and GitHub Synchronized
---

# Git Commit and Sync Workflow

This workflow ensures your local repository and GitHub stay perfectly synchronized. Use this whenever you've made changes and want to save them.

## Steps:

### 1. Check Current Status
```bash
git status
```
*See what files have changed*

### 2. Pull Latest Changes from GitHub
// turbo
```bash
git pull origin main
```
*Get any new changes from GitHub first*

### 3. Add All Changes
// turbo
```bash
git add .
```
*Stage all your changes for commit*

### 4. Check What Will Be Committed
```bash
git status
```
*Verify what changes will be included*

### 5. Commit Changes with Descriptive Message
```bash
git commit -m "feat: [describe the main feature/change you made]

✨ What was added/changed:
- [list key changes]
- [list key changes]

🔧 Technical details:
- [any technical notes]

🎯 Benefits:
- [how this helps the project]"
```
*Create a detailed commit message*

### 6. Push to GitHub
// turbo
```bash
git push origin main
```
*Send your changes to GitHub*

### 7. Verify Sync Success
// turbo
```bash
git status
```
*Confirm everything is clean and synced*

## AI Agent Instructions:

When using this workflow:

1. **Always pull first** to avoid conflicts
2. **Use descriptive commit messages** that explain what changed and why
3. **Include emojis** for visual clarity (✨ feat, 🔧 fix, 📝 docs, 🎨 style)
4. **List specific changes** in bullet points
5. **Mention benefits** of the changes
6. **Push immediately after commit** to keep everything synced

## Commit Message Template:

```
[type]: [short description]

✨ What was added/changed:
- [specific change 1]
- [specific change 2]

🔧 Technical details:
- [implementation notes]
- [dependencies affected]

🎯 Benefits:
- [user benefit]
- [developer benefit]
```

## Common Commit Types:
- `feat:` - New feature
- `fix:` - Bug fix
- `docs:` - Documentation
- `style:` - Code formatting
- `refactor:` - Code restructuring
- `test:` - Adding tests
- `chore:` - Maintenance tasks

## Troubleshooting:

**If you get conflicts during pull:**
```bash
git stash
git pull origin main
git stash pop
# Resolve conflicts manually
git add .
git commit -m "fix: resolve merge conflicts"
git push origin main
```

**If you need to undo the last commit:**
```bash
git reset --soft HEAD~1
```

## Success Indicators:
- ✅ `git status` shows "nothing to commit, working tree clean"
- ✅ GitHub repository shows your latest changes
- ✅ No conflicts or errors during push
- ✅ Local and remote are synchronized
