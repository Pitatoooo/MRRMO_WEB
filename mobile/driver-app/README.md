# MDRRMO Driver Mobile (Capacitor)

Background geolocation app that posts to your Laravel endpoint `/update-location` even when in background.

## Prereqs
- Node 18+
- Android Studio (Windows) for building Android
- For iOS builds, a Mac is required (not covered here)

## Configure
1. Set your API base URL used by the mobile app.
   - Option A: at build time with Vite env:
     - Create `mobile/driver-app/.env` with:
     
     VITE_API_BASE_URL=https://your-domain.com
     
   - Option B: edit the default value in `src/main.ts` (API_BASE_URL).
2. Ensure your Laravel `/update-location` route is reachable from device (HTTPS recommended).

## Install

cd mobile/driver-app
npm install
npm run build

## Capacitor setup

# Initialize native platforms
npx cap sync
npx cap add android
# Open Android Studio
npm run android

## Android notes
- When first run, grant location permissions (including “Allow all the time” for true background).
- The app shows a foreground service notification while tracking.
- If builds fail, run `npm run build && npx cap sync` again.

## Usage
1. Enter the Ambulance ID (same as your Laravel `ambulances.id`).
2. Tap “Start Background Tracking”. Keep the app installed and running during shifts.
3. Tap “Stop” to end tracking.

## Troubleshooting
- If no locations arrive:
  - Confirm the API URL in `.env` is correct and accessible (try from device browser).
  - Check app permissions: Location (Always), Background activity allowed, Battery optimization off.
  - Watch Android logcat for `BackgroundGeolocation` messages.

## Security
- The current API accepts `{ id, latitude, longitude }` without authentication. For production, consider a token for drivers or app-level auth and validate ownership server-side.
