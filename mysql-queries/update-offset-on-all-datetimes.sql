## Run this as a direct query on your database to update all the EE datetimes for the given offset
## 1. You'll need to make sure the table name is correct
## 2. Use whatever interval matches the offset you need to set.  So if you want to subtract 3 hours from all times then
##    you would use `DATE_SUB(DTT_EVT_start, INTERVAL 3 HOUR)`
## ONLY RUN THIS QUERY DIRECTLY ON YOUR DB IF YOU KNOW WHAT YOU ARE DOING.
## NOTE this ONLY affects offsets on the esp_datetime values.  There are other dates and times stored in the EE database tables that you might want to adjust as well (such as esp_ticket, esp_registration etc)
UPDATE wp_esp_datetime
SET DTT_EVT_start = DATE_ADD(DTT_EVT_start, INTERVAL 2 HOUR),
    DTT_EVT_end = DATE_ADD(DTT_EVT_end, INTERVAL 2 HOUR)
