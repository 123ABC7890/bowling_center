# User Stories — Bowlingcenter Brooklyn

> Time estimates are rough development hours (design + code + basic testing).

---

## 1. Authentication & User Management

- [x] **1.1** As a visitor, I can register with my name, email, phone and password so I can make reservations. *(3h)*
- [x] **1.2** As a visitor, I can log in with email and password so I can access my account. *(2h)*
- [x] **1.3** As a user, I can log out so my session is ended securely. *(0.5h)*
- [ ] **1.4** As a user, I can reset my password via email so I can regain access to my account. *(3h)*
- [ ] **1.5** As a user, I can view and edit my profile (name, phone) so my information stays current. *(2h)*

---

## 2. Browsing & Information

- [x] **2.1** As a visitor, I can see the homepage with tariffs, packages, and opening hours so I know what is offered. *(4h)*
- [x] **2.2** As a visitor, I can see the Magic Bowling section on the homepage so I know about the disco bowling experience. *(1h)*
- [ ] **2.3** As a visitor, I can view real-time lane availability on a calendar/grid so I can pick the best time. *(8h)*

---

## 3. Reservations

- [x] **3.1** As a user, I can create a reservation by choosing a date, time, duration (1-3h), and lane so I can book a bowling session. *(6h)*
- [x] **3.2** As a user, I can specify the number of adults and children so capacity rules are enforced (max 8 adults or 6 adults + 4 children). *(2h)*
- [x] **3.3** As a user, I can optionally add a snack package (Basic €15 / Luxury €35) to my reservation. *(2h)*
- [x] **3.4** As a user, I can optionally add a party package (Children's €75 / Bachelor €120) to my reservation. *(2h)*
- [x] **3.5** As a user, I see the total price calculated (lane rate + packages) before confirming so there are no surprises. *(2h)*
- [x] **3.6** As a user, I see a confirmation page with all reservation details after booking. *(2h)*
- [x] **3.7** As a user, I cannot double-book a lane that is already reserved for that time slot. *(2h)*
- [x] **3.8** As a user, I can only book within opening hours (Mon-Fri 14-22, Sat-Sun 14-00) so invalid bookings are prevented. *(1h)*
- [ ] **3.9** As a user, I can view a list of my past and upcoming reservations so I can keep track. *(4h)*
- [ ] **3.10** As a user, I can cancel my upcoming reservation so I free up the lane for others. *(3h)*
- [ ] **3.11** As a user, I can book a Magic Bowling session (Sat-Sun 22:00-00:00) with its own tariff. *(4h)*

---

## 4. Tariff & Pricing

- [x] **4.1** As the system, I apply the correct tariff based on day and time (Mon-Thu €24, Fri-Sun afternoon €28, Fri-Sun evening €33.50). *(3h)*
- [ ] **4.2** As the system, I apply a Magic Bowling tariff for Sat-Sun 22:00-00:00. *(2h)*

---

## 5. Employee Dashboard

- [ ] **5.1** As an employee, I can view today's reservations in a dashboard so I can manage the lanes. *(6h)*
- [ ] **5.2** As an employee, I can see which lanes are currently occupied and which are free. *(4h)*
- [ ] **5.3** As an employee, I can change the status of a reservation (pending/confirmed/cancelled). *(3h)*
- [ ] **5.4** As an employee, I can create a walk-in reservation on behalf of a customer. *(3h)*

---

## 6. Admin Panel

- [x] **6.1** As an admin, I can manage all reservations (CRUD) through EasyAdmin. *(3h)*
- [x] **6.2** As an admin, I can manage lanes (CRUD) through EasyAdmin. *(2h)*
- [x] **6.3** As an admin, I can manage tariffs (CRUD) through EasyAdmin. *(2h)*
- [x] **6.4** As an admin, I can manage packages (CRUD) through EasyAdmin. *(2h)*
- [x] **6.5** As an admin, I can manage users and assign roles through EasyAdmin. *(2h)*
- [ ] **6.6** As an admin, I can see dashboard statistics (total reservations, revenue, occupancy). *(6h)*

---

## 7. Notifications

- [ ] **7.1** As a user, I receive an email confirmation after making a reservation. *(4h)*
- [ ] **7.2** As a user, I receive an email when my reservation status changes. *(3h)*

---

## Summary

| Category | Done | Not started | Total |
|----------|------|-------------|-------|
| Authentication & User Mgmt | 3 | 2 | 5 |
| Browsing & Information | 2 | 1 | 3 |
| Reservations | 8 | 3 | 11 |
| Tariff & Pricing | 1 | 1 | 2 |
| Employee Dashboard | 0 | 4 | 4 |
| Admin Panel | 5 | 1 | 6 |
| Notifications | 0 | 2 | 2 |
| **Total** | **19** | **14** | **33** |

**Estimated remaining work: ~55 hours**
