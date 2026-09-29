# Financial Member Leads - UI/UX Visual Guide

## 🎨 Dashboard Layout

### Main Page Structure
```
┌─────────────────────────────────────────────────────────────┐
│                   FINANCIAL MEMBER LEADS                    │
│              Manage and track financial membership inquiries │
│                                     [Export CSV] [Filter ▼] │
└─────────────────────────────────────────────────────────────┘

┌──────────┬──────────┬──────────┬──────────┬──────────┐
│  TOTAL   │   NEW    │ CONTACTED│ QUALIFIED│ CONVERTED│
│  142     │   28     │   65     │   42     │    7     │
│ Leads    │ Unread   │ Started  │  Ready   │ Success  │
└──────────┴──────────┴──────────┴──────────┴──────────┘

Active Filters: Search: "john" | Status: new | Tag: gold [Clear all]

┌────────────────────────────────────────────────────────────────────┐
│ □ | Name      | Email           | Phone | Status | Tag  | Actions │
├────────────────────────────────────────────────────────────────────┤
│ □ | John Doe  | john@test.com   | +234  | ⚠ New  | —    | View ✉ │
│ □ | Jane Smith| jane@test.com   | +234  | 🔵 Con | Gold | View ✉ │
│ ✓ | Bob Lee   | bob@test.com    | +234  | ✓ Qual | Prem | View ✉ │
│ □ | Ali Ahmed | ali@test.com    | +234  | ⭐ Conv| Gold | View ✉ │
└────────────────────────────────────────────────────────────────────┘

[◀ 1 2 3 4 5 ▶]

[4 leads selected] [Tag as... ▼] [Delete] 
```

---

## 🎯 UI Components

### Statistics Cards
```
┌─────────────┐  ┌─────────────┐  ┌─────────────┐
│  TOTAL      │  │  NEW        │  │ CONTACTED   │
│   142       │  │  28         │  │  65         │
│ Leads       │  │ Unread      │  │ Started     │
│(Blue badge) │  │(Yellow)     │  │ (Blue)      │
└─────────────┘  └─────────────┘  └─────────────┘

┌─────────────┐  ┌─────────────┐
│ QUALIFIED   │  │ CONVERTED   │
│  42         │  │   7         │
│ Ready       │  │ Success ✓   │
│ (Green)     │  │ (Primary)   │
└─────────────┘  └─────────────┘
```

### Status Badges
```
⚠ NEW          - Yellow badge, uncontacted
🔵 CONTACTED   - Blue badge, communication started
✓ QUALIFIED    - Green badge, ready for conversion
⭐ CONVERTED   - Primary badge, successful member
```

### Membership Tier Badges
```
🥉 Bronze   - Silver badge, basic interest
🥈 Silver   - Info badge, engaged
🥇 Gold     - Warning badge, qualified
⭐ Premium  - Danger badge, high-value
```

### Action Buttons
```
[View]  - Open lead details modal
[✉]     - Send email modal
[⋮]     - More options (future)
```

---

## 📋 Filter Modal

### When "Filter" Button Clicked
```
┌──────────────────────────┐
│    FILTER LEADS          │
├──────────────────────────┤
│                          │
│ Search                   │
│ [________________]       │
│ Name, email, or phone    │
│                          │
│ Status                   │
│ [All Status ▼]           │
│ - New                    │
│ - Contacted              │
│ - Qualified              │
│ - Converted              │
│                          │
│ Tag                      │
│ [All Tags ▼]             │
│ - Bronze                 │
│ - Silver                 │
│ - Gold                   │
│ - Premium                │
│                          │
├──────────────────────────┤
│ [Clear] [Apply Filters]  │
└──────────────────────────┘
```

---

## 📧 Email Modal

### For Each Lead
```
┌─────────────────────────────────────────┐
│ Send Email to John Doe                  │
├─────────────────────────────────────────┤
│                                         │
│ Message Type:                           │
│ [⭐ Automated] [✏️ Custom]              │
│                                         │
│ ─── Automated Message ───               │
│ 📧 Automated Message                    │
│ Subject: Welcome to IEYDA               │
│           Financial Membership          │
│ Body: Thank you for your interest...    │
│                                         │
│ (Custom section hidden, shows on       │
│  clicking Custom radio button)         │
│                                         │
├─────────────────────────────────────────┤
│ [Cancel] [Send Email ✉]                │
└─────────────────────────────────────────┘
```

### Custom Email View
```
┌─────────────────────────────────────────┐
│ Send Email to John Doe                  │
├─────────────────────────────────────────┤
│                                         │
│ Message Type:                           │
│ [Automated] [✏️ Custom]                 │
│                                         │
│ Subject:                                │
│ [_________________________]             │
│                                         │
│ Message:                                │
│ [_____________________________]         │
│ [_____________________________]         │
│ [_____________________________]         │
│ [_____________________________]         │
│ [_____________________________]         │
│                                         │
├─────────────────────────────────────────┤
│ [Cancel] [Send Email ✉]                │
└─────────────────────────────────────────┘
```

---

## 👁️ Lead Details Modal

### Full Lead Information
```
┌───────────────────────────────────────────────┐
│            LEAD DETAILS                       │
├───────────────────────────────────────────────┤
│                                               │
│ Name                    │ Status              │
│ John Doe                │ ⚠ NEW               │
│                                               │
│ Email                   │ Tag                 │
│ john@email.com          │ 🥉 Bronze           │
│                                               │
│ Phone                   │ Interactions        │
│ +234-801-234-5678       │ 3                   │
│                                               │
├───────────────────────────────────────────────┤
│ ACTIVITY HISTORY                              │
│                                               │
│ 📞 Jan 14, 2:45 PM                            │
│    Contacted                                  │
│    Lead marked as contacted                   │
│                                               │
│ ✉️ Jan 14, 2:30 PM                            │
│    Email Sent                                 │
│    Sent: Welcome to IEYDA - Financial Memb... │
│                                               │
│ 📝 Jan 14, 1:15 PM                            │
│    Note Added                                 │
│    "Very interested, follow up next week"     │
│                                               │
│ 🏷️ Jan 14, 12:00 PM                           │
│    Tag Added                                  │
│    Tagged as Bronze                           │
│                                               │
└───────────────────────────────────────────────┘
```

---

## 🔄 Bulk Actions Bar

### When Leads Selected
```
When 1+ leads are selected:

┌────────────────────────────────────────────┐
│ ⚠ 4 leads selected  [Tag as... ▼] [Delete] │
└────────────────────────────────────────────┘

Tag Dropdown Options:
├─ Bronze  🥉
├─ Silver  🥈
├─ Gold    🥇
└─ Premium ⭐
```

---

## 📊 Table View - Interactive Features

### Hover Effects
```
Normal Row:
│ □ | John Doe | john@test.com | +234 | ⚠ New | — | View ✉ │

Hovered Row (highlighted):
│ □ | John Doe | john@test.com | +234 | ⚠ New | — | View ✉ │
  └─────────────────────────────────────────────────────────┘
                    (slight background shade)
```

### Checkbox Selection
```
□ = Unchecked
✓ = Checked (row highlighted in blue)
```

### Column Information
```
Name        - Lead's name (from form)
Email       - Clickable mailto: link
Phone       - From form (or —)
Status      - Color badge
Tag         - Membership tier badge  
Interactions- Count badge (e.g., 3)
Actions     - Quick action buttons
```

---

## 🚀 Responsive Design

### Desktop (1200px+)
```
Full table with all columns visible
Stats cards in 5-column grid
Modal popups
```

### Tablet (768px-1199px)
```
Table columns may wrap
Stats cards in 2-3 columns
Buttons stack if needed
```

### Mobile (< 768px)
```
Simplified card view
Stacked stats
Touch-friendly buttons
Scrollable table
Full-width modals
```

---

## ⌨️ Keyboard Navigation

### Available Shortcuts (in Modal)
```
ESC     - Close modal
Tab     - Move between fields
Enter   - Submit form
Space   - Check/uncheck checkbox
```

---

## 🎨 Color Scheme

### Status Colors
```
New        → Yellow (#FFC107)  - Needs attention
Contacted  → Blue (#0D6EFD)    - In progress
Qualified  → Green (#198754)   - Ready
Converted  → Primary (#0D6EFD) - Complete
```

### Tier Colors
```
Bronze     → Secondary (#6C757D)
Silver     → Info (#0DCAF0)
Gold       → Warning (#FFC107)
Premium    → Danger (#DC3545)
```

### UI Colors
```
Primary    → #0D6EFD (Blue)
Secondary  → #6C757D (Gray)
Success    → #198754 (Green)
Warning    → #FFC107 (Yellow)
Danger     → #DC3545 (Red)
Light      → #F8F9FA (Off-white)
```

---

## 📱 Interaction Patterns

### User Flow 1: Send Email
```
1. Click [✉] on lead row
   ↓
2. Email modal opens
   ↓
3. Choose: Automated OR Custom
   ↓
4. Review message
   ↓
5. Click [Send Email ✉]
   ↓
6. Lead marked Contacted
7. Activity logged
8. Success message
```

### User Flow 2: Filter Leads
```
1. Click [Filter]
   ↓
2. Fill filter form
   - Search: "john"
   - Status: "new"
   - Tag: "gold"
   ↓
3. Click [Apply Filters]
   ↓
4. Table updates
5. Shows matching leads
6. "Active filters" bar shown
```

### User Flow 3: Bulk Operations
```
1. Check multiple lead rows
   ↓
2. "Bulk actions" bar appears
3. Shows count: "4 leads selected"
   ↓
4. Option A: Select tag
   - Dropdown: Bronze/Silver/Gold/Premium
   - All 4 leads tagged instantly
   
   OR

   Option B: Click [Delete]
   - Confirmation dialog
   - Leads deleted permanently
```

### User Flow 4: Export Data
```
1. (Optional) Apply filters
   ↓
2. Click [Export CSV]
   ↓
3. File downloads: "financial_leads_2026-01-14_123456.csv"
   ↓
4. Open in Excel/Google Sheets
5. Use for analysis or mail merge
```

---

## ✨ Special Effects

### Loading States
```
Modal Body: 
[Spinner] Loading...
```

### Success Messages
```
✓ Email sent successfully
✓ Lead marked as contacted  
✓ Status updated
✓ Tag assigned
```

### Error Messages
```
✗ Failed to send email: [reason]
✗ Please try again
```

### Form Validation
```
Required field missing: [highlight in red]
Invalid email: [show tooltip]
```

---

## 📝 Pro Tips for Users

### Quick Search
```
Just start typing in search to find:
- Lead name
- Email address
- Phone number

Example: "john" finds all Johns
```

### Filter Combination
```
Best practice: Combine filters
Status: "new" + Tag: "gold" = Uncontacted qualified leads
Status: "contacted" + Tag: empty = Leads needing categorization
```

### Bulk Tagging
```
After importing leads:
1. Select all new leads
2. Tag as "Bronze"
3. Then sort/filter by tier

Workflow improves team efficiency!
```

### Export Workflow
```
1. Filter: Status = "qualified"
2. Export CSV
3. Use in email merge tool
4. Send bulk proposal email
5. Track responses
```

---

**Design Philosophy**: Clean, modern, professional, and highly functional.

**Accessibility**: WCAG 2.1 AA compliant with keyboard navigation and screen reader support.

**Performance**: Optimized for fast loading and smooth interactions.

**Mobile-First**: Designed to work great on all devices.
