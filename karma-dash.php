<?php
/*
Plugin Name: Karma Dashboard
Plugin URI: http://skockination.com
Description: Displays Referrer Karma (and later, Spam Karma) Information as a Dashboard.
Author: Mark Payne
Version: 1.0&alpha;
Author URI: http://skockination.com
*/

/*

Karma Dashboard. Copyright 2004 Mark Payne, Skockination.

Permission is hereby granted, free of charge, to any person
obtaining a copy of this software and associated
documentation files (the "Software"), to deal in the
Software without restriction, including without limitation
the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software,
and to permit persons to whom the Software is furnished to
do so, subject to the following conditions:

The above copyright notice and this permission notice shall
be included in all copies or substantial portions of the
Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY
KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS
OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR
OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

add_action('admin_menu', 'admin_menu');

function admin_menu()
	{
		$plugin_file = basename(dirname(__FILE__)) . '/' . basename(__FILE__);
		add_submenu_page('index.php', 'Referrer Karma Logs & Statistics', 'Karma', 10, $plugin_file, 'plugin_content');
	}

function plugin_content()
	{

	global $wpdb;

	$wpdb->rk_list = "ref_karma";
	$wpdb->rk_log = "ref_karma_logs";

	$limit_list = 10;
	$limit_log = 50;

	$black = $wpdb->get_results("SELECT * FROM {$wpdb->rk_list}  WHERE `key` = \"black\" ORDER BY `last_mod` DESC LIMIT {$limit_list};");
	$white = $wpdb->get_results("SELECT * FROM {$wpdb->rk_list} WHERE `key` = \"white\" ORDER BY `last_mod` DESC");
	$logs = $wpdb->get_results("SELECT * FROM {$wpdb->rk_log} WHERE 1 ORDER BY `ts` DESC LIMIT {$limit_log};");

?>
	<div class="wrap">
	<h2>Referrer Karma Dashboard</h2>	

	<div id="blacklisted">
	<h3>Recently Blacklisted</h3>
		<ul>
		<?php
			foreach ($black as $row)
			{
				echo "<li>";
				
				echo "<b>{$row->value}</b>" . " - Attempts: " . $row->used . " - Last Attempt: " . $row->last_mod;
				/*
			 	echo " [<a href=\"" . $_SERVER["PHP_SELF"] . "?ref-karma-setup=true&pwd=". $_REQUEST['pwd'] . "&switch_id=". $row->id. "&status=white\">Switch to Whitelist</a>]";
			
				echo " [<a href=\"" . $_SERVER["PHP_SELF"] . "?remove_id=". $row->id . "\">Remove</a>]";
				*/
				echo "</li>\n";
			}
		?>
		</ul>
	</div>
	
	<div id="whitelisted">
	<h3>Recently Whitelisted</h3>
		<ul>
		<?php
			foreach ($white as $row)
			{
				echo "<li>";
				
				echo "<b>{$row->value}</b>" . " - Attempts: " . $row->used . " - Last Attempt: " . $row->last_mod;
				/*
			 	echo " [<a href=\"" . $_SERVER["PHP_SELF"] . "?switch_id=". $row->id. "&status=black\">Switch to Blacklist</a>]";
			
				echo " [<a href=\"" . $_SERVER["PHP_SELF"] . "?remove_id=". $row->id. "\">Remove</a>]";
				*/
				echo "</li>\n";
			}
		?>
		</ul>
	</div>
	
	<div id="rk-log">
	<h3>Karma Log</h3>
		<table width="100%" cellpadding="3" cellspacing="3">
		<thead>
			<tr>
				<th>Action</th>
				<th>Referer</th>
				<th>IP Address</th>
				<th>Time</th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($logs as $row)
			{
				$alternate = ($alternate == '')? ' class="alternate"' : '';
	  			
	  			echo "<tr{$alternate}>\n";
				
				echo "<td><b>{$row->msg}</b></td>";
				echo "<td>{$row->ref}</td>";
				echo "<td>{$row->ip}</td>";
				echo "<td>{$row->ts}</td>";
				
				echo "</tr>\n";
			}
		?>
		</tbody>
		</table>
	
	</div>
	
	</div>

<?php } ?>