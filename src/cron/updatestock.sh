# cron task to update the stock of all the parts each week
#
# This will launch the task each monday at 00:00
# 0 0 * * MON /usr/bin/php /opt/api/tasks/UpdateStock.php
