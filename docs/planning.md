## Todo

### 7.2 Status change email

  - due: 2026-04-05
  - defaultExpanded: false
    ```md
    Send email when reservation status changes (e.g. cancellation) via Doctrine event listener (3h)
    Note: reservations are now auto-confirmed on creation
    ```

## Doing

## Done

### 8.1 Homepage data from database

  - due: 2026-04-02
  - defaultExpanded: false
    ```md
    Load tariffs and packages from database instead of static HTML on homepage (1h)
    ```

### 8.2 Auto-confirm reservations

  - due: 2026-04-02
  - defaultExpanded: false
    ```md
    Default reservation status changed from pending to confirmed. Added constants (STATUS_CONFIRMED, STATUS_PENDING, STATUS_CANCELLED) to Reservation entity and replaced all static string references (1h)
    ```

### 8.3 Form validation flash alerts

  - due: 2026-04-02
  - defaultExpanded: false
    ```md
    Form errors now display as flash alert banners instead of inline errors. Removed novalidate to enable browser-side required field validation (1h)
    ```

### 8.4 UI improvements

  - due: 2026-04-02
  - defaultExpanded: false
    ```md
    Parallax background on main, footer component, navbar cleanup (logout moved to profile page, dropdown replaced with separate nav links), text-white headers for readability (2h)
    ```

### 3.9 My reservations list

  - due: 2026-04-03
  - defaultExpanded: false
    ```md
    View list of past and upcoming reservations (4h)
    ```

### 3.10 Cancel reservation

  - due: 2026-04-03
  - defaultExpanded: false
    ```md
    Cancel an upcoming reservation to free the lane (3h)
    ```

### 3.11 Magic Bowling booking

  - due: 2026-04-03
  - defaultExpanded: false
    ```md
    Book Magic Bowling disco-style session (Sat-Sun 22:00-00:00) (4h)
    ```

### 4.2 Magic Bowling tariff

  - due: 2026-04-03
  - defaultExpanded: false
    ```md
    Apply dedicated Magic Bowling tariff for Sat-Sun 22:00-00:00 (€38/hr) (2h)
    ```

### 1.4 Password reset

  - due: 2026-04-03
  - defaultExpanded: false
    ```md
    Reset password via email to regain account access (3h)
    ```

### 1.5 User profile

  - due: 2026-04-03
  - defaultExpanded: false
    ```md
    View and edit profile (name, phone) (2h)
    ```

### 5.1 Employee: Today's reservations

  - due: 2026-04-04
  - defaultExpanded: false
    ```md
    View today's reservations in EasyAdmin dashboard (6h)
    ```

### 5.2 Employee: Lane occupancy

  - due: 2026-04-04
  - defaultExpanded: false
    ```md
    See which lanes are currently occupied and which are free in EasyAdmin dashboard (4h)
    ```

### 2.3 Lane availability calendar

  - due: 2026-04-04
  - defaultExpanded: false
    ```md
    Real-time lane availability on a calendar/grid for 8 lanes (2 with bumpers) (8h)
    ```

### 5.3 Employee: Change reservation status

  - due: 2026-04-05
  - defaultExpanded: false
    ```md
    Change status of a reservation (pending/confirmed/cancelled) via EasyAdmin CRUD (3h)
    ```

### 5.4 Employee: Walk-in reservation

  - due: 2026-04-05
  - defaultExpanded: false
    ```md
    Create a walk-in reservation on behalf of a customer via EasyAdmin CRUD (3h)
    ```

### 6.6 Admin: Dashboard statistics

  - due: 2026-04-05
  - defaultExpanded: false
    ```md
    Dashboard with total reservations, revenue, and occupancy stats in EasyAdmin (6h)
    ```

### 7.1 Email confirmation

  - due: 2026-04-05
  - defaultExpanded: false
    ```md
    Send email confirmation after making a reservation (4h)
    ```

### 1.1 User registration

  - due: 2026-03-31
  - defaultExpanded: false
    ```md
    Register with name, email, phone and password (3h)
    ```

### 1.2 User login

  - due: 2026-03-31
  - defaultExpanded: false
    ```md
    Log in with email and password (2h)
    ```

### 1.3 User logout

  - due: 2026-03-31
  - defaultExpanded: false
    ```md
    Log out to end session securely (0.5h)
    ```

### 2.1 Homepage with tariffs, packages & hours

  - due: 2026-03-31
  - defaultExpanded: false
    ```md
    Homepage showing tariffs (Mon-Thu €24, Fri-Sun afternoon €28, Fri-Sun evening €33.50), snack/party packages, and opening hours (Mon-Fri 14:00-22:00, Sat-Sun 14:00-00:00) (4h)
    ```

### 2.2 Magic Bowling section on homepage

  - due: 2026-03-31
  - defaultExpanded: false
    ```md
    Disco-style bowling experience section on homepage (Sat-Sun 22:00-00:00) (1h)
    ```

### 3.1 Create reservation

  - due: 2026-03-31
  - defaultExpanded: false
    ```md
    Choose date, time, duration (1-3h), and lane to book (6h)
    ```

### 3.2 Capacity rules

  - due: 2026-03-31
  - defaultExpanded: false
    ```md
    Enforce max 8 adults or 6 adults + 4 children per lane (2h)
    ```

### 3.3 Snack packages

  - due: 2026-03-31
  - defaultExpanded: false
    ```md
    Optionally add Basic (€15) or Luxury (€35) snack package (2h)
    ```

### 3.4 Party packages

  - due: 2026-03-31
  - defaultExpanded: false
    ```md
    Optionally add Children's (€75) or Bachelor (€120) party package (2h)
    ```

### 3.5 Price calculation

  - due: 2026-03-31
  - defaultExpanded: false
    ```md
    Show total price (lane tariff + optional packages) before confirming (2h)
    ```

### 3.6 Reservation confirmation page

  - due: 2026-03-31
  - defaultExpanded: false
    ```md
    Show all reservation details after booking (2h)
    ```

### 3.7 Double-booking prevention

  - due: 2026-03-31
  - defaultExpanded: false
    ```md
    Prevent booking a lane already reserved for that time slot (2h)
    ```

### 3.8 Opening hours enforcement

  - due: 2026-03-31
  - defaultExpanded: false
    ```md
    Only allow bookings within opening hours (Mon-Fri 14:00-22:00, Sat-Sun 14:00-00:00) (1h)
    ```

### 4.1 Tariff system

  - due: 2026-03-30
  - defaultExpanded: false
    ```md
    Apply correct tariff per hour: Mon-Thu €24 (14:00-22:00), Fri-Sun €28 (14:00-18:00), Fri-Sun €33.50 (18:00-00:00) (3h)
    ```

### 6.1 Admin: Manage reservations

  - due: 2026-03-30
  - defaultExpanded: false
    ```md
    CRUD reservations through EasyAdmin (3h)
    ```

### 6.2 Admin: Manage lanes

  - due: 2026-03-30
  - defaultExpanded: false
    ```md
    CRUD lanes (8 total, 2 with bumpers) through EasyAdmin (2h)
    ```

### 6.3 Admin: Manage tariffs

  - due: 2026-03-30
  - defaultExpanded: false
    ```md
    CRUD tariffs through EasyAdmin (2h)
    ```

### 6.4 Admin: Manage packages

  - due: 2026-03-30
  - defaultExpanded: false
    ```md
    CRUD snack and party packages through EasyAdmin (2h)
    ```

### 6.5 Admin: Manage users & roles

  - due: 2026-03-30
  - defaultExpanded: false
    ```md
    Manage users and assign roles (user/employee/admin) through EasyAdmin (2h)
    ```
