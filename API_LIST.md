# API Endpoints List

## 📍 APIs in `admin/gps/index.blade.php`

### GPS Tracking
- `GET /admin/gps/data`
- `POST /admin/gps/set-destination`
- `POST /admin/gps/resend-request/{ambulanceId}`
- `GET /admin/gps/resend-status/{ambulanceId}`
- `POST /admin/gps/resend-clear/{ambulanceId}`

### Ambulance Management
- `POST /admin/ambulances/{id}/clear-destination`
- `GET /admin/ambulances/{ambulanceId}/driver`

### Assignment Management
- `GET /admin/assignments/{driverId}`
- `POST /admin/assignments/{driverId}/ensure`
- `POST /admin/assignments/{driverId}/stops`
- `POST /admin/assignments/{driverId}/reorder`
- `DELETE /admin/assignments/{driverId}/stops/{stopId}`

### Emergency Cases
- `GET /admin/cases`
- `POST /admin/cases`
- `GET /admin/cases/{caseNum}`
- `PUT /admin/cases/{caseNum}/status`
- `POST /admin/cases/{caseNum}/complete`
- `DELETE /admin/cases/{caseNum}`
- `GET /admin/cases/ambulance/counts`
- `GET /admin/cases/completed`
- `GET /admin/cases/needing-redeployment`
- `GET /admin/cases/recent-actions`
- `GET /admin/cases/ambulances-for-redeployment`
- `POST /admin/cases/{caseNum}/redeploy`

### External APIs
- `GET https://nominatim.openstreetmap.org/search`
- `GET https://overpass-api.de/api/interpreter`
- `GET https://nominatim.openstreetmap.org/reverse`

### Presence
- `POST /presence/superadmin/heartbeat`
- `GET /presence/superadmin/status`

---

## 🚗 APIs in `driver/send-location.blade.php`

### GPS Location
- `POST /update-location`
- `GET /driver/gps/resend-check`
- `POST /driver/gps/resend-acknowledge`
- `POST /driver/gps/resend-complete`

### Ambulance Destination
- `GET /driver/ambulance/{id}/destination`
- `POST /driver/ambulance/{id}/arrived`
- `POST /admin/ambulances/{id}/clear-destination`

### Case Management
- `GET /driver/cases/notifications`
- `GET /driver/cases/all`
- `POST /driver/cases/{caseNum}/accept`
- `POST /driver/cases/{caseNum}/reject`
- `POST /driver/cases/{caseNum}/complete`

### Assignment Management
- `GET /driver/assignment`
- `POST /driver/assignment/reorder`
- `POST /driver/assignment/stops/{stopId}/complete`

### Destination Setting
- `POST /admin/gps/set-destination`

### Presence
- `GET /presence/superadmin/status`

---

## 📋 All System APIs

### Authentication
- `GET /driver/login`
- `POST /driver/login`
- `POST /driver/logout`

### Dashboard
- `GET /dashboard`
- `GET /dashboard/metrics`
- `GET /dashboard/view`

### Services
- `GET /services`
- `GET /services/{id}`
- `GET /admin/services`
- `POST /admin/services`
- `GET /admin/services/reviews`
- `GET /admin/services/bookings`

### Bookings
- `POST /bookings`
- `GET /admin/bookings`
- `POST /admin/bookings/{id}/approve`
- `POST /admin/bookings/{id}/reject`

### Reviews
- `POST /reviews`

### Drivers
- `GET /admin/drivers`
- `GET /admin/drivers/create`
- `POST /admin/drivers`
- `GET /admin/drivers/{driver}`
- `GET /admin/drivers/{driver}/edit`
- `PUT /admin/drivers/{driver}`
- `DELETE /admin/drivers/{driver}`
- `POST /admin/drivers/{driver}/update-status`
- `POST /admin/drivers/{driver}/toggle-availability`

### Ambulances
- `GET /admin/ambulances`
- `POST /admin/ambulances/store`
- `POST /admin/ambulances/{id}/set-destination`
- `POST /admin/ambulances/{id}/clear-destination`
- `GET /admin/ambulances/{ambulanceId}/driver`

### Medics
- `GET /admin/medics`
- `GET /admin/medics/create`
- `POST /admin/medics`
- `GET /admin/medics/{id}`
- `GET /admin/medics/{id}/edit`
- `PUT /admin/medics/{id}`
- `DELETE /admin/medics/{id}`

### Pairing
- `GET /admin/pairing`
- `GET /admin/pairing/log`
- `GET /admin/pairing/bulk`
- `POST /admin/pairing/bulk`
- `POST /admin/pairing/bulk-action`
- `POST /admin/pairing/group-action`
- `GET /admin/pairing/driver-medic/create`
- `POST /admin/pairing/driver-medic`
- `GET /admin/pairing/driver-medic/{id}/edit`
- `PUT /admin/pairing/driver-medic/{id}`
- `DELETE /admin/pairing/driver-medic/{id}`
- `POST /admin/pairing/driver-medic/{id}/complete`
- `POST /admin/pairing/driver-medic/{id}/cancel`
- `GET /admin/pairing/driver-ambulance/create`
- `POST /admin/pairing/driver-ambulance`
- `GET /admin/pairing/driver-ambulance/{id}/edit`
- `PUT /admin/pairing/driver-ambulance/{id}`
- `DELETE /admin/pairing/driver-ambulance/{id}`
- `POST /admin/pairing/driver-ambulance/{id}/complete`
- `POST /admin/pairing/driver-ambulance/{id}/cancel`

### Content Management
- `GET /admin/posting`
- `POST /admin/posting/carousel`
- `POST /admin/posting/mission-vision`
- `POST /admin/posting/about`
- `POST /admin/posting/officials`
- `POST /admin/posting/trainings`

### Billing
- `GET /billing/create`
- `POST /billing`

### Utility
- `GET /test`
- `GET /check-db`
- `GET /manifest.webmanifest`
- `GET /sw.js`
- `GET /icons/icon-192.svg`
- `GET /icons/icon-512.svg`

