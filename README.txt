RentHouse (DB-space version)

1) Create DB:
   - Import db/schema.sql in phpMyAdmin

2) Place files:
   - Copy the folder to htdocs as: C:\xampp\htdocs\renthouse_dbspace_fixed\

3) Config:
   - If your MySQL user/pass differ, edit php/config.php

4) Use:
   - http://localhost/renthouse_dbspace_fixed/register.html
   - http://localhost/renthouse_dbspace_fixed/login.html

Notes:
- Database name is exactly: rent house
- Password reset uses password_resets table
- Rent requests use rent_requests table
- Chat uses messages table
