There were some subsilver solutions posted for 0.0.8:
By FreakBlue on Aug 08, 2008: http://www.phpbb.com/community/viewtopic.php?f=70&t=666195&start=1695#p6548535
By drumstix42 on Oct 06, 2008: http://www.phpbb.com/community/viewtopic.php?f=70&t=666195&start=1845#p7284065
By aston20 on Dec 03, 2008: http://www.phpbb.com/community/viewtopic.php?f=70&t=666195&start=1980#p7942455
By marian0810 Mar 10, 2009: http://www.phpbb.com/community/viewtopic.php?f=70&t=666195&start=2235#p8878155


At this time there is no subsilver version for the most current release of this mod.  Any style gurus out there are welcome to provide a subsilver solution for this mod, and I can incude it with the next install - with proper credit to the developer of course.

In the mean time if you can't use the solutions posted above, this work around may help a few subsilver users.

Open calendar.php
Find: $user->setup('calendar');
Replace with: $user->setup('calendar', 1);

Open calendarpost.php
Find: $user->setup('calendarpost');
Replace with: $user->setup('calendarpost', 1);

This will force the forum to use the prosilver on those two pages, while the rest of your forum remains in your prefered style.

You should also disable the calendar modules in the UCP - as there are no subsilver translations for the new modules.

Hope that helps, and if you have a subsilver solution for the latest release, many users would appreciate 
it if you share.

Thanks!

