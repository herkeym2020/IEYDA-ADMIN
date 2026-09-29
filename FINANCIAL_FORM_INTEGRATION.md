# Financial Member Form Integration Guide

## Overview

This guide explains how to set up Google Forms to automatically send responses to your IEYDA backend, capturing all responder information including name, email, phone, etc.

## Prerequisites

- Google Form for financial member recruitment
- Google Sheet linked to the form (auto-created when you create a form)
- Backend URL: `https://control.ilorinemirateyouths.com/api/v1/financial-member/submit`

## Step-by-Step Setup

### Step 1: Prepare Your Google Form

Make sure your Google Form has these fields (minimum):

```
1. Name (Short answer)
2. Email (Email field)
3. Phone Number (Short answer, optional)
4. Additional fields as needed
```

### Step 2: Create the Apps Script

1. Open your Google Form
2. Click **⋮** (More) → **Responses** tab → **Link to Sheets**
3. Select the auto-created Sheet or choose an existing sheet
4. Open the Sheet (not the Form)
5. Click **Extensions** → **Apps Script**
6. Replace all code with this:

```javascript
function onFormSubmit(e) {
  // Get form data from sheet trigger (e.namedValues)
  var formData = {
    name: null,
    email: null,
    phone: null,
    source_form_id: SpreadsheetApp.getActive().getId(),
    form_submitted_at: new Date().toISOString(),
    data: {}
  };

  // Extract data from e.namedValues (sheet trigger format)
  if (e && e.namedValues) {
    for (var fieldName in e.namedValues) {
      var value = e.namedValues[fieldName][0]; // Get first value from array
      
      formData.data[fieldName] = value;
      
      // Map to standard fields
      var lowerField = fieldName.toLowerCase();
      if (lowerField.indexOf('name') > -1) {
        formData.name = value;
      }
      if (lowerField.indexOf('email') > -1) {
        formData.email = value;
      }
      if (lowerField.indexOf('phone') > -1) {
        formData.phone = value;
      }
    }
  }

  if (!formData.email) {
    Logger.log('No email found');
    return;
  }

  // Send to backend
  var options = {
    method: 'post',
    contentType: 'application/json',
    payload: JSON.stringify(formData),
    headers: {
      'X-Webhook-Token': PropertiesService.getScriptProperties().getProperty('LEAD_WEBHOOK_TOKEN') || ''
    },
    muteHttpExceptions: true
  };

  try {
    var response = UrlFetchApp.fetch('https://control.ilorinemirateyouths.com/api/v1/financial-member/submit', options);
    Logger.log('Success: ' + response.getContentText());
  } catch (error) {
    Logger.log('Error: ' + error);
  }
}

// Manual test function
function testWebhook() {
  var testData = {
    name: 'John Doe',
    email: 'john@example.com',
    phone: '08012345678',
    source_form_id: 'TEST_FORM_ID',
    form_submitted_at: new Date().toISOString(),
    data: {
      'Full Name': 'John Doe',
      'Email Address': 'john@example.com',
      'Phone Number': '08012345678'
    }
  };

  var options = {
    method: 'post',
    contentType: 'application/json',
    payload: JSON.stringify(testData),
    muteHttpExceptions: true
  };

  var response = UrlFetchApp.fetch('https://control.ilorinemirateyouths.com/api/v1/financial-member/submit', options);
  Logger.log('Response: ' + response.getContentText());
}
```

### Step 3: Set Up the Trigger

1. In Apps Script editor, click **Triggers** (⏰ icon on left)
2. Click **+ Create new trigger**
3. Configure:
  - **Choose which function to run**: `onFormSubmit`
  - **Choose which deployment should run**: `Head`
  - **Select event source**: `From spreadsheet` (use Sheet trigger, NOT Form trigger)
  - **Select event type**: `On form submit`
4. Click **Create**
5. Grant permissions when prompted

**Important**: Use **From spreadsheet** trigger (not "From form"). The form trigger sometimes causes URL issues.

### Step 4: Optional - Add Webhook Token Security

1. In Apps Script editor, click **Project Settings** (⚙️)
2. Enable **Show "appsscript.json" manifest file**
3. Add webhook token to Script Properties:
   - Click **Triggers** (⏰)
   - Then **Project Settings** → **Script Properties** (not in trigger settings)
   - Add key-value pair:
     - **Key**: `LEAD_WEBHOOK_TOKEN`
     - **Value**: (your webhook token from `.env` LEAD_WEBHOOK_TOKEN)
4. This value will be sent in the `X-Webhook-Token` header

### Step 5: Test the Integration

**Option A: Test via Google Form**
1. Submit a test form response with:
   - Name: John Test
   - Email: john@example.com
   - Phone: 08012345678
2. Check your IEYDA admin panel: **Leads**
3. Verify the lead appears

**Option B: Test via Apps Script**
1. In Apps Script editor, click **testWebhook()** function
2. Click ▶️ (Run)
3. Check logs: View → **Execution log**
4. Should see success message with lead data

### Step 6: Verify Data in Backend

1. Go to admin panel: `control.ilorinemirateyouths.com/admin/leads`
2. Filter by "New" status
3. Should see entries with:
   - **Name**: Captured from form
   - **Email**: Captured from form
   - **Phone**: Captured from form
   - **Submitted**: Timestamp of form submission
   - **Status**: "new" (until contacted)

## Troubleshooting

### Name Not Captured

**Problem**: Lead shows up but name is blank/showing "—"

**Solution**:
- Ensure your form field is named something that includes the word "name" (case-insensitive)
- Examples that work: "Full Name", "Name", "Your Name", "name", "NAME"
- The script looks for fields containing "name" in the title

**If field has different name**:
1. Open Apps Script
2. Find this section:
   ```javascript
   if (itemTitle.toLowerCase().includes('name')) {
     formData.name = response;
   }
   ```
3. Change to match your field name:
   ```javascript
   if (itemTitle.toLowerCase() === 'your field name here') {
     formData.name = response;
   }
   ```

### Data Not Reaching Backend

**Check these**:
1. Is the form linked to a Google Sheet? (Required)
2. Did you create the trigger? (Click Triggers to verify)
3. Check Apps Script logs: **View → Execution log**
4. Check backend logs: `storage/logs/laravel.log` on server
5. Is webhook token correct? (if using security)

### Email Validation Fails

**Problem**: "Email is required" error

**Solution**:
- Ensure your form has an email field
- Make it REQUIRED in the form settings
- The script looks for field containing "email"
- Submit again with valid email

## Advanced: Custom Field Mapping

If your form has different field names, customize the script:

```javascript
// After extracting itemTitle and response:
if (itemTitle === 'Full Name') {
  formData.name = response;
}
if (itemTitle === 'Work Email') {
  formData.email = response;
}
if (itemTitle === 'Mobile') {
  formData.phone = response;
}
```

## What Gets Captured

When a form is submitted:
- ✅ Name (if field contains "name")
- ✅ Email (if field contains "email")
- ✅ Phone (if field contains "phone")
- ✅ All form fields (stored in `data` JSON)
- ✅ Form submission timestamp
- ✅ Google Form ID
- ✅ Automatically marked as "new" status

## Backend Actions

After capture:
1. Lead appears in admin panel
2. Admin can click "Email" to send:
   - **Automated**: Pre-written response
   - **Custom**: Admin-written message
3. Clicking "Mark Contacted" updates status to "contacted"
4. All actions logged with timestamps

## Security Notes

- Webhook is open by default (optional token protection)
- All data is validated on backend
- Email validation prevents invalid entries
- Consider adding LEAD_WEBHOOK_TOKEN to .env for security:
  ```env
  LEAD_WEBHOOK_TOKEN=your_random_secret_token_here
  ```
- Then update Apps Script to use this token (see Step 4)

## Need Help?

Check these files:
- [API Endpoint](app/Http/Controllers/Api/FinancialMemberLeadController.php)
- [Model](app/Models/FinancialMemberLead.php)
- [Database Schema](database/migrations/2025_12_25_160000_create_financial_member_leads_table.php)

---

**Status**: Production ready. Once configured, leads will auto-sync in real-time.
