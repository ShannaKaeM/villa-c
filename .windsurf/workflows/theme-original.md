---
description: Original Carbon Blocksy theme repository for updates and fresh copies
---

# Carbon Blocksy Original Theme Repository

## Repository Information
- **Original Repository**: https://github.com/DanielRSnell/carbon-blocksy
- **Main Branch**: https://github.com/DanielRSnell/carbon-blocksy/tree/main
- **Purpose**: Original theme source for resources, updates, and fresh installations

## Use Cases

### 1. Getting Fresh Copy
When you need to start over with a clean installation:
```bash
# Remove current theme (backup first if needed)
rm -rf wp-content/themes/carbon-blocksy

# Clone fresh copy
git clone https://github.com/DanielRSnell/carbon-blocksy.git wp-content/themes/carbon-blocksy

# Install dependencies
cd wp-content/themes/carbon-blocksy
composer install
```

### 2. Checking for Updates
To see what's new in the original theme:
```bash
# Add original as upstream remote (if not already added)
git remote add upstream https://github.com/DanielRSnell/carbon-blocksy.git

# Fetch latest changes
git fetch upstream

# Compare with main branch
git log HEAD..upstream/main --oneline
```

### 3. Pulling Updates
To incorporate updates from the original theme:
```bash
# Fetch latest changes
git fetch upstream

# Merge or rebase updates (be careful with custom changes)
git merge upstream/main
# OR
git rebase upstream/main
```

## Important Notes
- Always backup your custom changes before pulling updates
- The original repository is the authoritative source for Carbon Blocksy
- Use this for reference when implementing custom features
- Check here first when troubleshooting core theme issues

## Documentation
- Theme documentation and examples are available in the original repository
- Check the README.md and docs folder for implementation guides
- Issues and discussions can be found in the GitHub repository
