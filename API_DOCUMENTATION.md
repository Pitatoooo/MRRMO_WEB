# API Documentation - MDRRMO Web System

This document lists all API endpoints used in the system, with special focus on `admin/gps/index.blade.php` and `driver/send-location.blade.php`.

---

## 📍 APIs Used in `admin/gps/index.blade.php`

### GPS Tracking APIs

1. **`GET /admin/gps/data`** (Route: `admin.gps.data`)
   - **Function**: Fetches real-time GPS location data for all ambulances and their drivers
   - **Returns**: Array of ambulance objects with location, driver info, status, and online/stale status

2. **`POST /admin/gps/set-destination`** (Route: `admin.gps.set-destination`)
   - **Function**: Sets a destination location for an ambulance on the map
   - **Purpose**: Allows admin to mark where an ambulance should go

3. **`POST /admin/gps/resend-request/{ambulanceId}`**
   - **Function**: Sends a request to a driver to resend their GPS location
   - **Purpose**: Used when admin notices a driver is offline or location is stale

4. **`GET /admin/gps/resend-status/{ambulanceId}`**
   - **Function**: Checks the status of a GPS resend request
   - **Returns**: Status of pending/acknowledged/completed resend request

5. **`POST /admin/gps/resend-clear/{ambulanceId}`**
   - **Function**: Clears a GPS resend request when driver comes back online

### Ambulance Management APIs

6. **`POST /admin/ambulances/{id}/clear-destination`**
   - **Function**: Clears the destination marker for a specific ambulance
   - **Purpose**: Removes destination when ambulance task is completed

7. **`GET /admin/ambulances/{ambulanceId}/driver`**
   - **Function**: Retrieves the driver ID associated with an ambulance
   - **Returns**: Driver ID for the given ambulance

### Assignment Management APIs

8. **`GET /admin/assignments/{driverId}`**
   - **Function**: Lists all stops/assignments for a specific driver
   - **Returns**: Array of assignment stops with details

9. **`POST /admin/assignments/{driverId}/ensure`**
   - **Function**: Ensures an assignment exists for a driver (creates if missing)

10. **`POST /admin/assignments/{driverId}/stops`**
    - **Function**: Adds a new stop to a driver's assignment list

11. **`POST /admin/assignments/{driverId}/reorder`**
    - **Function**: Reorders the sequence of stops in a driver's assignment

12. **`DELETE /admin/assignments/{driverId}/stops/{stopId}`**
    - **Function**: Removes a specific stop from a driver's assignment

### Emergency Cases Management APIs

13. **`GET /admin/cases`**
    - **Function**: Retrieves list of emergency cases
    - **Purpose**: Fetches all emergency cases for display in admin panel

14. **`POST /admin/cases`**
    - **Function**: Creates a new emergency case
    - **Purpose**: Admin creates new emergency case record

15. **`GET /admin/cases/{caseNum}`**
    - **Function**: Retrieves details of a specific emergency case
    - **Returns**: Case details including status, location, assigned ambulance

16. **`PUT /admin/cases/{caseNum}/status`**
    - **Function**: Updates the status of an emergency case
    - **Purpose**: Changes case status (pending, active, completed, etc.)

17. **`POST /admin/cases/{caseNum}/complete`**
    - **Function**: Marks an emergency case as completed
    - **Purpose**: Admin marks case as finished

18. **`DELETE /admin/cases/{caseNum}`**
    - **Function**: Deletes an emergency case record

19. **`GET /admin/cases/ambulance/counts`**
    - **Function**: Gets case counts per ambulance
    - **Returns**: Statistics showing how many cases each ambulance has handled

20. **`GET /admin/cases/completed`**
    - **Function**: Retrieves all completed emergency cases
    - **Returns**: List of finished cases

21. **`GET /admin/cases/needing-redeployment`**
    - **Function**: Gets cases that need ambulance redeployment
    - **Returns**: Cases that require new ambulance assignment

22. **`GET /admin/cases/recent-actions`**
    - **Function**: Retrieves recent driver actions on cases
    - **Returns**: Recent case activity logs

23. **`GET /admin/cases/ambulances-for-redeployment`**
    - **Function**: Gets list of ambulances available for redeployment
    - **Returns**: Available ambulances that can be assigned to new cases

24. **`POST /admin/cases/{caseNum}/redeploy`**
    - **Function**: Redeploys an ambulance to a case
    - **Purpose**: Assigns/reattempts ambulance assignment for a case

### External APIs (Third-party)

25. **`GET https://nominatim.openstreetmap.org/search`**
    - **Function**: Geocoding API - converts address to coordinates
    - **Purpose**: Converts user-entered addresses to latitude/longitude for map display

26. **`GET https://overpass-api.de/api/interpreter`**
    - **Function**: Overpass API - queries OpenStreetMap data
    - **Purpose**: Retrieves detailed map features and locations from OSM

27. **`GET https://nominatim.openstreetmap.org/reverse`**
    - **Function**: Reverse geocoding API - converts coordinates to address
    - **Purpose**: Converts map click coordinates to readable addresses

### Presence/Status APIs

28. **`POST /presence/superadmin/heartbeat`**
    - **Function**: Sends heartbeat signal from super admin account
    - **Purpose**: Tracks super admin presence/activity

29. **`GET /presence/superadmin/status`**
    - **Function**: Checks which super admins are currently active
    - **Returns**: List of active super admin accounts

---

## 🚗 APIs Used in `driver/send-location.blade.php`

### GPS Location APIs

1. **`POST /update-location`** (Route: `update.location`)
   - **Function**: Updates the current GPS location of the driver's ambulance
   - **Purpose**: Main API for sending driver's location to the server
   - **Payload**: `{ id: ambulanceId, latitude: lat, longitude: lng }`

2. **`GET /driver/gps/resend-check`**
   - **Function**: Checks if admin has requested GPS location resend
   - **Returns**: Status of any pending resend request from admin

3. **`POST /driver/gps/resend-acknowledge`**
   - **Function**: Acknowledges receipt of GPS resend request from admin
   - **Purpose**: Driver confirms they received the resend request

4. **`POST /driver/gps/resend-complete`**
   - **Function**: Marks GPS resend request as completed after successfully sending location
   - **Purpose**: Notifies admin that location has been resent

### Ambulance Destination APIs

5. **`GET /driver/ambulance/{id}/destination`**
   - **Function**: Retrieves the destination coordinates set by admin for the ambulance
   - **Returns**: Destination latitude and longitude

6. **`POST /driver/ambulance/{id}/arrived`**
   - **Function**: Marks that the ambulance has arrived at destination
   - **Purpose**: Clears destination and updates ambulance status to Available

7. **`POST /admin/ambulances/{id}/clear-destination`**
   - **Function**: Clears the destination marker (can be called by driver)
   - **Purpose**: Removes destination when task is complete

### Case Management APIs (Driver)

8. **`GET /driver/cases/notifications`**
   - **Function**: Retrieves new case notifications for the driver
   - **Returns**: List of cases assigned or pending for the driver

9. **`GET /driver/cases/all`**
   - **Function**: Gets all cases associated with the driver
   - **Returns**: Complete list of driver's cases (active, pending, completed)

10. **`POST /driver/cases/{caseNum}/accept`**
    - **Function**: Driver accepts an assigned emergency case
    - **Purpose**: Driver confirms they will handle the case

11. **`POST /driver/cases/{caseNum}/reject`**
    - **Function**: Driver rejects an assigned emergency case
    - **Purpose**: Driver declines to handle the case

12. **`POST /driver/cases/{caseNum}/complete`**
    - **Function**: Driver marks a case as completed
    - **Purpose**: Driver indicates they have finished handling the case

### Assignment Management APIs (Driver)

13. **`GET /driver/assignment`**
    - **Function**: Retrieves the driver's current assignment with list of stops
    - **Returns**: Assignment details with ordered list of stops

14. **`POST /driver/assignment/reorder`**
    - **Function**: Reorders stops in the driver's assignment
    - **Purpose**: Driver can change the sequence of stops

15. **`POST /driver/assignment/stops/{stopId}/complete`**
    - **Function**: Marks a specific stop as completed
    - **Purpose**: Driver indicates they have completed a stop

### Destination Setting API

16. **`POST /admin/gps/set-destination`**
    - **Function**: Sets destination coordinates (can be triggered by driver actions)
    - **Purpose**: Updates ambulance destination on map

### Presence/Status APIs

17. **`GET /presence/superadmin/status`**
    - **Function**: Checks super admin presence status
    - **Purpose**: Driver can see if super admin is online

---

## 📋 Complete API List (All Files)

### Authentication APIs
- `POST /driver/login` - Driver login
- `POST /driver/logout` - Driver logout
- `GET /driver/login` - Driver login form

### Dashboard APIs
- `GET /dashboard` - Main dashboard
- `GET /dashboard/metrics` - Dashboard metrics by date
- `GET /dashboard/view` - Dashboard view page

### Service Management APIs
- `GET /services` - List public services
- `GET /services/{id}` - Get service details
- `GET /admin/services` - Admin service list
- `POST /admin/services` - Create service
- `GET /admin/services/reviews` - Service reviews
- `GET /admin/services/bookings` - Service bookings
- `POST /admin/services/bookings/{id}/approve` - Approve booking
- `POST /admin/services/bookings/{id}/reject` - Reject booking

### Booking APIs
- `POST /bookings` - Create public booking
- `GET /admin/bookings` - Admin booking list
- `POST /admin/bookings/{id}/approve` - Approve booking
- `POST /admin/bookings/{id}/reject` - Reject booking

### Review APIs
- `POST /reviews` - Create review

### Driver Management APIs
- `GET /admin/drivers` - List drivers
- `GET /admin/drivers/create` - Create driver form
- `POST /admin/drivers` - Create driver
- `GET /admin/drivers/{driver}` - Get driver details
- `GET /admin/drivers/{driver}/edit` - Edit driver form
- `PUT /admin/drivers/{driver}` - Update driver
- `DELETE /admin/drivers/{driver}` - Delete driver
- `POST /admin/drivers/{driver}/update-status` - Update driver status
- `POST /admin/drivers/{driver}/toggle-availability` - Toggle driver availability

### Ambulance Management APIs
- `GET /admin/ambulances` - List ambulances
- `POST /admin/ambulances/store` - Create ambulance
- `POST /admin/ambulances/{id}/set-destination` - Set ambulance destination
- `POST /admin/ambulances/{id}/clear-destination` - Clear ambulance destination
- `GET /admin/ambulances/{ambulanceId}/driver` - Get ambulance's driver

### Medic Management APIs
- `GET /admin/medics` - List medics
- `GET /admin/medics/create` - Create medic form
- `POST /admin/medics` - Create medic
- `GET /admin/medics/{id}` - Get medic details
- `GET /admin/medics/{id}/edit` - Edit medic form
- `PUT /admin/medics/{id}` - Update medic
- `DELETE /admin/medics/{id}` - Delete medic

### Pairing Management APIs
- `GET /admin/pairing` - Pairing list
- `GET /admin/pairing/log` - Pairing log
- `GET /admin/pairing/bulk` - Bulk pairing form
- `POST /admin/pairing/bulk` - Store bulk pairing
- `POST /admin/pairing/bulk-action` - Bulk pairing action
- `POST /admin/pairing/group-action` - Group pairing action
- `GET /admin/pairing/driver-medic/create` - Create driver-medic pairing
- `POST /admin/pairing/driver-medic` - Store driver-medic pairing
- `GET /admin/pairing/driver-medic/{id}/edit` - Edit driver-medic pairing
- `PUT /admin/pairing/driver-medic/{id}` - Update driver-medic pairing
- `DELETE /admin/pairing/driver-medic/{id}` - Delete driver-medic pairing
- `POST /admin/pairing/driver-medic/{id}/complete` - Complete driver-medic pairing
- `POST /admin/pairing/driver-medic/{id}/cancel` - Cancel driver-medic pairing
- `GET /admin/pairing/driver-ambulance/create` - Create driver-ambulance pairing
- `POST /admin/pairing/driver-ambulance` - Store driver-ambulance pairing
- `GET /admin/pairing/driver-ambulance/{id}/edit` - Edit driver-ambulance pairing
- `PUT /admin/pairing/driver-ambulance/{id}` - Update driver-ambulance pairing
- `DELETE /admin/pairing/driver-ambulance/{id}` - Delete driver-ambulance pairing
- `POST /admin/pairing/driver-ambulance/{id}/complete` - Complete driver-ambulance pairing
- `POST /admin/pairing/driver-ambulance/{id}/cancel` - Cancel driver-ambulance pairing

### Content Management APIs
- `GET /admin/posting` - Posting page
- `POST /admin/posting/carousel` - Store carousel item
- `POST /admin/posting/mission-vision` - Store mission/vision
- `POST /admin/posting/about` - Store about content
- `POST /admin/posting/officials` - Store official info
- `POST /admin/posting/trainings` - Store training info

### Billing APIs
- `GET /billing/create` - Create billing form
- `POST /billing` - Store billing record

### Utility APIs
- `GET /test` - System test endpoint
- `GET /check-db` - Database connection check
- `GET /manifest.webmanifest` - PWA manifest
- `GET /sw.js` - Service worker
- `GET /icons/icon-192.svg` - PWA icon 192
- `GET /icons/icon-512.svg` - PWA icon 512

---

## 🔑 External APIs Used

1. **OpenStreetMap Nominatim API**
   - Base URL: `https://nominatim.openstreetmap.org`
   - Endpoints:
     - `/search` - Geocoding (address to coordinates)
     - `/reverse` - Reverse geocoding (coordinates to address)
   - **Function**: Converts between addresses and geographic coordinates

2. **Overpass API**
   - Base URL: `https://overpass-api.de/api/interpreter`
   - **Function**: Queries OpenStreetMap data for detailed location information
   - **Purpose**: Retrieves map features, POIs, and geographic data

---

## 📝 Notes

- All APIs require appropriate authentication middleware (`auth` for admin, `auth.driver` for driver)
- Most APIs return JSON responses
- GPS-related APIs update in real-time and are polled frequently
- External APIs (Nominatim, Overpass) are rate-limited and should be used with respect to their terms of service

