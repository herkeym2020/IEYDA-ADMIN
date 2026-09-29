# Facebook Integration Setup Guide

## Overview
Your IEYDA CMS now automatically creates news posts whenever your Facebook page gets a new post. This works via webhook integration with Facebook's Graph API.

## Architecture
1. **Facebook Page** → Posts a new update
2. **Facebook API** → Sends webhook to your backend
3. **Backend** → Receives webhook, verifies signature, fetches full post details
4. **News Table** → Auto-creates a news post with content, image, and link
5. **Admin Dashboard** → News post appears immediately

## Setup Steps

### Step 1: Create Facebook App

1. Go to [Meta Developers](https://developers.facebook.com/)
2. Create new app:
   - **App Type**: Business
   - **App Name**: IEYDA CMS (or your choice)
   - **App Purpose**: Choose your use case

3. Add **Facebook Graph API** product to your app

### Step 2: Get Page Access Token

1. In your Facebook App dashboard:
   - Go to **Settings** → **Basic** (save App ID and App Secret)
   - Go to **Tools** → **Graph API Explorer**
   
2. Select your Page (not your personal account):
   - Page dropdown → Select your IEYDA Ilorin page
   
3. Get page access token:
   - Click "Generate Access Token" button
   - This gives you a temporary token (valid for ~2 hours)
   
4. For permanent token:
   - Use a User Token with `pages_read_engagement` and `pages_read_user_content` permissions
   - Or get a Page Access Token via your app's normal OAuth flow

### Step 3: Get Required Credentials

You need these 4 values:

```
FACEBOOK_PAGE_ID = Your page ID (numeric, e.g., 123456789)
FACEBOOK_PAGE_ACCESS_TOKEN = Long-lived page access token
FACEBOOK_APP_SECRET = From App Settings → Basic
FACEBOOK_VERIFY_TOKEN = Any random string you choose (e.g., "your_secure_random_token_123")
```

**How to get Page ID:**
- Visit your page URL: `facebook.com/ilorinemirateyouths`
- Check page source or use: `facebook.com/ilorinemirateyouths?v=info`
- Or use Graph API Explorer: query `me?fields=id`

### Step 4: Update Environment Variables

Edit `.env`:
```env
FACEBOOK_PAGE_ID=YOUR_PAGE_ID_HERE
FACEBOOK_PAGE_ACCESS_TOKEN=YOUR_LONG_LIVED_TOKEN_HERE
FACEBOOK_APP_SECRET=YOUR_APP_SECRET_HERE
FACEBOOK_VERIFY_TOKEN=your_secure_random_token_123
```

Then clear config:
```bash
php artisan config:clear
```

### Step 5: Configure Facebook Webhook

1. In your Facebook App dashboard:
   - Go to **Settings** → **Basic** (copy App Secret)
   - Go to **Products** → **Webhooks**

2. Subscribe to `feed` events:
   - **Callback URL**: `https://control.ilorinemirateyouths.com/api/v1/facebook/webhook`
   - **Verify Token**: (paste the FACEBOOK_VERIFY_TOKEN from your .env)
   - **Webhook Fields**: Select `feed` (and any others you want: `comments`, `reactions`, etc.)

3. Click **Subscribe**

4. Verify by checking:
   - Your backend logs should show "Facebook webhook verified successfully"

### Step 6: Test the Integration

**Option A: Manual Sync (Recommended for first test)**

Sync existing posts:
```bash
php artisan facebook:sync --limit=5
```

This pulls your last 5 posts and creates them as news items.

**Option B: Real-time Test**

1. Make sure webhook is subscribed
2. Create a new post on your Facebook page
3. Check logs: `tail -f storage/logs/laravel.log`
4. Check admin news panel - new post should appear within seconds

## How It Works

### Webhook Flow

```
Facebook: New post created
    ↓
Facebook sends POST to /api/v1/facebook/webhook
    ↓
Backend verifies signature using FACEBOOK_APP_SECRET
    ↓
Service fetches full post details from Graph API
    ↓
Creates News record with:
    - Title: First 100 chars of post
    - Content: Full message/story
    - Image: Attached image URL
    - Published: Yes (immediately live)
    ↓
Tracks in facebook_posts table for deduplication
    ↓
News post appears in admin dashboard & frontend
```

### What Gets Imported

- ✅ Text content (message)
- ✅ Story/caption
- ✅ Featured image
- ✅ Link to original post
- ✅ Created timestamp
- ✅ Raw JSON data (for reference)

### What's Tracked

- Original Facebook post ID (prevents duplicates)
- Page ID
- Link to news record created
- Timestamp of import
- Full raw post data

## Management

### View Imported Posts

Check which posts were imported:
```bash
php artisan tinker
>>> App\Models\FacebookPost::orderBy('created_at', 'desc')->get(['facebook_post_id', 'news_id', 'imported_at']);
```

### Edit News Posts

Imported posts are regular News records. Edit them in:
- Admin Panel → News
- Change title, content, image, or delete

### Troubleshooting

**Webhook not receiving data:**
1. Check verify token matches FACEBOOK_VERIFY_TOKEN
2. Ensure callback URL is HTTPS and publicly accessible
3. Check logs: `storage/logs/laravel.log`
4. Verify Facebook app is in Development or Live mode

**"Post not found" errors:**
1. Ensure Page Access Token has permissions: `pages_read_engagement`, `pages_read_user_content`
2. Token might have expired - get new one from Graph API Explorer

**Missing images:**
1. Facebook may not attach images in webhook data
2. Full post fetch from API may not include image
3. Manually add image in admin News editor

**Signature verification failed:**
1. APP_SECRET must be exact match
2. Webhook payload must not be modified
3. Check X-Hub-Signature-256 header format: `sha256=...`

## Production Deployment

1. Update `.env` on production with real credentials
2. Run: `php artisan config:clear`
3. Test webhook is accessible: `curl https://yourdomain.com/api/v1/facebook/webhook`
4. Wait for first post to create to confirm it's working
5. Monitor logs for any errors

## Features to Add Later

- [ ] Auto-publish to other channels (Instagram, Twitter)
- [ ] Sync comments as engagement
- [ ] Manual post scheduling from CMS
- [ ] Link to original post with call-to-action
- [ ] Email notification to admins on new posts
- [ ] Post analytics dashboard

## Commands Available

```bash
# Manual sync posts
php artisan facebook:sync --limit=10

# Check database
php artisan tinker
>>> App\Models\FacebookPost::count();

# Clear caches if needed
php artisan config:clear
php artisan cache:clear
```

## Security

- ✅ Webhook signature verification (HMAC-SHA256)
- ✅ Token isolation in .env
- ✅ Deduplication prevents spam
- ✅ Logs all operations for audit trail
- ✅ Error handling to prevent crashes

---

**Status**: Ready for production use. Test before going live.
