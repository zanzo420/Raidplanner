If you have a forum that was converted from phpbb2 -> phpbb3 and used birthdays in phpbb2, you may have problems getting the converted birthdays to display in the calendar.

Why?  The format of the converted birthdays is often something like '7- 8-1964' instead of ' 7- 8-1964' with the leading whitespaces.  To update old birthdays to the new format, here's a utility you can install.

First save a backup copy of root/calendar.php.

Next open root/calendar.php for editing.

Find:
	case "month":
		// display the entire month
		$template_body = "calendar.html";
		calendar_display_month();
		break;


Add After:
	case "fixbdays":

		//lets output the current user/birthday data...
		$sql = 'SELECT  * FROM ' . USERS_TABLE . "
			ORDER BY user_id ASC";
		$result = $db->sql_query($sql);
		echo "<table border='1' cellpadding='3'><tr><td>user_id</td><td>joined on</td><td>birthday</td><td>reformatted</td></tr>\n";
		while ($row = $db->sql_fetchrow($result))
		{
			list($bday['day'], $bday['month'], $bday['year']) = explode('-', $row['user_birthday']);
			$correct_bday = sprintf('%2d-%2d-%4d', $bday['day'], $bday['month'], $bday['year']);
			if( 0 != strcmp( $row['user_birthday'], $correct_bday ))
			{
				echo "<tr>\n";
				echo "<td>" .$row['user_id']. "</td>\n";
				echo "<td>" .$user->format_date($row['user_regdate']). "</td>\n";
				echo "<td>'" .$row['user_birthday']. "'</td>\n";
				echo "<td>'" .$correct_bday. "'</td>\n";
				echo "</tr>\n";
				$sql = 'UPDATE ' . USERS_TABLE . '
					SET ' . $db->sql_build_array('UPDATE', array(
					'user_birthday'   => $correct_bday )) . "
					WHERE user_id = ".$row['user_id'];
				$db->sql_query($sql);
			}
		}
		echo "</table>\n";
		$db->sql_freeresult($result);

		// display current week - no reason - just using this template page
		$template_body = "calendar.html";
		calendar_display_week( 0 );
		break;


Now upload the changed calendar.php file to your forum and go to
www.yourforum.com/calendar.php?view=fixbdays
(where www.yourforum.com is the url to your forum)

This will display the original birthday and reformatted birthdays.

Once you've seen the reformatted birthdays and are satisfied with the results, you can re-upload the original (backup) version of calendar.php.

