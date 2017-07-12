## Run this as a direct query on your database to update all the EE datetimes for the given offset
## 1. You'll need to make sure the table name is correct
## 2. Use whatever interval matches the offset you need to set.  So if you want to subtract 3 hours from all times then
##    you would use `DATE_SUB(DTT_EVT_start, INTERVAL 3 HOUR)`
## ONLY RUN THIS QUERY DIRECTLY ON YOUR DB IF YOU KNOW WHAT YOU ARE DOING.
UPDATE wp_esp_datetime
SET DTT_EVT_start = DATE_ADD(DTT_EVT_start, INTERVAL 2 HOUR),
    DTT_EVT_end = DATE_ADD(DTT_EVT_end, INTERVAL 2 HOUR)
