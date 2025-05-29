---
description: Owner Portol Goals
---

Villa Capriani Platform Architecture
Comprehensive Owner Portal & Collaboration System

🏗️ Technical Stack Overview
Core WordPress Plugins:
Fluent Boards - Project management, roadmap, committee collaboration
Fluent Tickets - Support system with committee oversight
Fluent CRM - Owner communication and email campaigns (maybe)
Custom Post Types (CPTs):
Groups (Committee type, Staff type)
Properties/Villas (These are the same CPT as teh Villas on the Front end, but are used for backend admin as well)
Announcements (same as Articles- can be a pot type taxonomy for announcements, and user permission)

📋 Platform Components
1. ROADMAP SYSTEM
Fluent Board Implementation:
Owner Portol - Roadmap with filtering - place for owners to upvote suggested ideas, available for all owners to see. 
Backend Management - Committee collaboration space and Project management - 
Permission-Based Access:
Public: 
Logged-in Owners: 
Committee Members:
BOD: Board of Directors
DVO: Director of Villa Operations
Staff: View 

Roadmap Categories:
💻 Technology, Marketing
⚖️ Legal & Governance
🌿 Grounds & Appearance
💰 Budget & Revenue
📋 Operations & Maintenance
2. COMMITTEE COLLABORATION
Each Committee Gets:
Dedicated Fluent Board for project management
Shared Projects across committees for collaboration
Ticket Assignment for their domain expertise
Roadmap Contribution
Group Structure:
💻 Technology, Marketing
⚖️ Legal & Governance
🌿 Grounds & Appearance
💰 Budget & Revenue
📋 Operations Review
BOD
Property Management 
3. SUPPORT TICKET SYSTEM
Fluent Tickets Configuration:
Owner Submission - Easy ticket creation
Committee Routing - Auto-assign by category
PM Collaboration - Staff can view/respond
Transparency - Operations Committee Oversight
Progress Tracking - Status updates and resolution
Ticket Categories:
Property Management - Tickets Violations - Billing etc. 
Committees - Owners can connect with committees for questions and concerns. 

👥 User Roles & Permissions
Owner Dashboard Access:
Property Management - Mark for rent/sale, edit details
Committee Participation - View assignments, join committees
Roadmap Interaction - Vote, suggest, comment
Ticket Submission - Create and track support requests
Announcements - Read community updates
Bill Payment - Links to PM website
Documents - Access HOA rules, bylaws, regulations
Committee Member Access:
All Owner Permissions +
Project Management - Fluent Board access
Ticket Oversight - Monitor and collaborate on relevant tickets
Roadmap Management - Create, edit, approve items
Committee Communication - Internal collaboration tools
Staff/PM Access:
Ticket Management - Respond to and resolve tickets
Project Visibility - Specific Project Boards
Owner Communication - Respond to inquiries
Status Updates - Update project progress

---

## ✅ IMPLEMENTATION CHECKLIST

### Phase 1 - Foundation (COMPLETE)
- [x] Custom User Roles System (villa_owner, bod_member, committee_member, staff_member, dvo_member)
- [x] Owner Profiles Custom Post Type with comprehensive fields
- [x] Profile Avatar System with image upload and display
- [x] Portal Dashboard with role-based sections
- [x] Sample Data Generator (20 test users with profiles)
- [x] User Role Switcher for testing different portal views
- [x] Carbon Blocks Framework compliance and auto-discovery

### Phase 2 - Core Features
- [ ] Groups CPT (Committees, Staff hierarchy)
- [ ] Fluent Boards Integration (Roadmap system)
- [ ] Fluent Tickets Integration (Support system)
- [ ] Committee collaboration spaces
- [ ] Owner voting system for roadmap items
- [ ] Ticket routing by category/committee

### Phase 3 - Advanced Features
- [ ] Announcements system with portal taxonomy
- [ ] Document management (HOA rules, bylaws)
- [ ] Bill payment integration links
- [ ] Property management features (rent/sale toggles)
- [ ] Email notification system
- [ ] Fluent CRM integration

### Phase 4 - Polish & Launch
- [ ] Mobile responsive design
- [ ] User onboarding flow
- [ ] Admin training documentation
- [ ] Performance optimization
- [ ] Security audit
- [ ] Go-live preparation

---

## 🚀 NEWLY IMPLEMENTED FEATURES & INSTRUCTIONS

### 🎭 User Role Switcher (TESTING SYSTEM)
**Location:** Admin Bar → "🎭 Role: [Current Role]"

**How to Use:**
1. **Find the Switcher** - Look for the gradient-styled "🎭 Role: Administrator" in your admin bar
2. **Click to Open Menu** - Dropdown shows all available portal roles
3. **Select a Role** - Choose any role to test:
   - 🏠 Villa Owner
   - 👔 Board of Directors (BOD)
   - 🤝 Committee Member
   - 🛠️ Staff Member
   - 🎯 Director of Villa Operations (DVO)
   - 👨‍💼 Administrator (return to normal)
4. **View Portal** - Navigate to "Owner Portal" to see role-specific view
5. **Test Mode Banner** - Purple banner shows when in test mode
6. **Switch Back** - Select "Administrator" to return to full access

**Features:**
- Automatically creates test profiles for each role
- Assigns realistic test data and avatars
- Links villa owners to random villa units
- Maintains your administrator privileges
- Visual indicators show current test role

### 📊 Portal Dashboard Access
**Location:** WordPress Admin → "Owner Portal"

**What You'll See:**
- **Test Mode Banner** - Shows when using role switcher
- **Profile Section** - Displays avatar, status, owned villas
- **Role-Based Content** - Different sections based on current role
- **Quick Actions** - Edit profile, create tickets, view announcements
- **System Stats** - User counts, recent activity

### 🧪 Sample Data Generator
**Location:** WordPress Admin → Tools → "Portal Sample Data"

**What It Creates:**
- **20 Test Users** across all portal roles:
  - 12 Villa Owners (regular property owners)
  - 3 BOD Members (Board of Directors)
  - 3 Committee Members (various committees)
  - 1 Staff Member (property management)
  - 1 DVO (Director of Villa Operations)
- **Owner Profiles** with realistic data
- **Avatar Images** using avatar-secondary.png
- **Villa Assignments** for owners
- **Contact Information** with fake phone numbers and addresses

**How to Use:**
1. Go to Tools → Portal Sample Data
2. Click "Generate Sample Data"
3. Review the generated users list
4. Use role switcher to test different user perspectives

### 👤 Owner Profiles System
**Location:** WordPress Admin → "Owner Profiles"

**Field Groups (Organized in Tabs):**
- **Basic Information:** Name, email, phone, avatar upload
- **Contact Information:** Addresses, emergency contacts, preferences
- **Villa Assignments:** Owned properties, rental status
- **Committee Memberships:** Committee assignments and roles
- **Portal Preferences:** Dashboard layout, notifications

**Avatar System:**
- Upload custom profile photos
- Default: avatar-secondary.png from branding assets
- Displays as 80x80px circular images in dashboard
- Stored as URLs for easy display

### 🔐 User Roles & Capabilities
**Custom Roles Created:**
- **villa_owner:** Property owners with basic portal access
- **bod_member:** Board members with governance access
- **committee_member:** Committee participants with project access
- **staff_member:** Property management staff
- **dvo_member:** Director of Villa Operations with full oversight

**Each Role Has:**
- Specific portal capabilities
- Appropriate dashboard sections
- Relevant quick actions
- Permission-based content visibility

---

## 📝 TESTING CHECKLIST

### ✅ Ready to Test Now:
- [x] Switch to Villa Owner role and view portal dashboard
- [x] Check profile avatar display and edit functionality
- [x] Generate sample data and review created users
- [x] Test all 5 portal roles using the switcher
- [x] Verify role-based content visibility
- [x] Confirm test mode indicators work properly

### 🎯 Next Development Priority:
1. **Groups CPT** - Create committee structure
2. **Fluent Boards** - Install and configure for roadmap
3. **Fluent Tickets** - Set up support system