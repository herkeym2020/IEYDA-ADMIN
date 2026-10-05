# IEYDA Feature Deployment Runbook

This runbook deploys the `feat/meeting-notices-history` branches safely. It does **not** reset or delete production data.

## 1. Backend release

From the production backend directory:

```bash
git fetch origin
git checkout feat/meeting-notices-history
git pull --ff-only origin feat/meeting-notices-history
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan db:seed --class=SettingSeeder --force
php artisan db:seed --class=TeamMemberSeeder --force
php artisan db:seed --class=FeatureContentSeeder --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

The seeders use `updateOrCreate` for their managed records. Running them again will update the known records rather than duplicate them. **Do not run `migrate:fresh` on production.**

## 2. Verify backend routes

```bash
base=https://control.ilorinemirateyouths.com/api/v1
curl -fsS "$base/bootstrap" | jq 'keys'
curl -fsS "$base/communities" | jq '.data | length'
curl -fsS "$base/meeting-notices" | jq 'keys'
curl -fsS "$base/monthly-realizations" | jq 'keys'
curl -fsS "$base/history/ilorin" | jq 'keys'
curl -fsS "$base/hero-stats" | jq
```

Expected results:

- `bootstrap` includes `siteStats`, `meetingNotices`, `monthlyRealizations`, and `history`.
- `GET /communities` returns HTTP 200 and a JSON collection.
- The three feature endpoints return HTTP 200.
- `hero-stats` reflects values from Admin > Settings > Public Statistics.

## 3. Frontend release

Build the public site from the matching feature branch:

```bash
git fetch origin
git checkout feat/meeting-notices-history
git pull --ff-only origin feat/meeting-notices-history
npm ci
npm run build
```

Publish the generated `dist/` directory using the existing hosting process. The frontend reads managed figures from the server bootstrap and does not require a separate statistics API request.

## 4. Post-release checks

Open the public site and verify:

- Membership directory loads approved associations.
- Present and pioneering executive tabs remain separate.
- The meeting notice popup appears only when an active popup notice exists.
- The monthly realization appears on the homepage and community page.
- The Ilorin history page loads its seeded timeline.
- Editing a public statistic in the admin settings updates the bootstrap after cache invalidation.

If the site uses PHP-FPM or an opcode cache, restart/reload it according to the hosting provider’s normal procedure after the release.
