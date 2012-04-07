* After successfully installing the calendar mod, you can install US holidays (in english)
* with the install_us_holidays__en.php file (in the contrib folder).  
* Simply upload the file in your root forum directory, and navigate to
* it in your browser window.  
*
* Note you must be logged into the forum as an admin when accessing this page.
*
* You should also check your "Calendar Settings" page in the ACP before
* installing the holidays.  If your "Auto Populate Recurring Events" setting
* is 0, recurring events will not work right in your calendar, because the cron job
* used to populate event occurrences will never run.  Please set this to 1 at a
* minimum.  Also the "Auto Populate Limits" setting determines how far into the
* future you want to create recurring events when the cron job runs.  If you set
* this value to 30 you will never see recurring events in the calendar unless they
* are a month or less away.  If you want to see all of the holidays when you
* first install them, you should set this value to 365.
*
* Do not forget to delete the file when you are finished installing
* the holidays so you do not accidentally install them twice.
