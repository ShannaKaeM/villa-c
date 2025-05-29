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