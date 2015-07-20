# Support Forum System

<b>Be warned</b>: this is a bit messy. but #yolo

<p>This is a very simple support forum system. To install, you just need Php Tidy Extention, and PDO. 
To edit settings, just edit assets/settings.php</p>

You'll need to edit the following in settings.php:

// database info<br>
define("host", "localhost");<br>
define("user", "");<br>
define("pass", "");<br>
define("database", "");<br><br>

// password encryption key<br>
define("enc_key", "+0491!03");<br><br>

// SMTP mail settings (using Mandrill API)<br>
define("api_user", "KingFox");<br>
define("api_pass", "");<br>
define("from_email", "");<br>

The SQL for the database can be found in root folder.

After these have been configured, just proceed to login to the support system, create a new account, and goto phpMyAdmin via the fox_users table and set your rights to 2 for admin, 1 for moderator, 0 for regular member.

Administrators can manage posts, moderators, admins, and banned users.
Moderators can just manage posts.

