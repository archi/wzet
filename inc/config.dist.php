<?php

/**
 * Set a random (!), secret string here
 * It is used to sign cookies
 *
 * If this is empty, the script refuses to run!
 *
 * If you change this after setup, all users will be logged out.
 * So it's okay to change it in case a users cookie was compromised.
 *
 * This is faster than always looking a Token up in the sqlite DB.
 *
 * BAD EXAMPLE: "helloworld"
 * GOOD EXAMPLE: "asdj34,.R034]93nmSa)#@masDE43"
 */
$_CONFIG['SECRET'] = "";

/**
 * Whats the name of the database to use?
 * Since there is no setup script, copy 
 * inc/db/empty.sqlite there!
 */
$_CONFIG['DATABASE'] = "inc/db/main.sqlite";

/**
 * Cookie path; i.e. path of index.php relative to the webroot
 */
$_CONFIG["COOKIE_PATH"] = "/";

/**
 * If you set keys here, those can be used to
 *  control the mail.php script
 * The first key is for the cron functionality, to
 *  send E-Mail to $_CONFIG['MAIL_RECEIVER'] (passed to mail())
 * The second key can be used for unauthenticated PREVIEW
 *
 * Empty key -> Function disabled.
 */
$_CONFIG['MAIL_RECEIVER'] = "";
$_CONFIG['MAIL_CRON_KEY'] = "";
$_CONFIG['MAIL_PREVIEW_KEY'] = "";

/**
 * The registration key is used to lock the register.php
 * Setting it to empty allows anyone to register without a key
 * Setting it to "0" disables registration
 */
$_CONFIG['REGISTER_KEY'] = "0";

/**
 * Try to enable PHP error messages?
 */
$_CONFIG["SHOW_ERRORS"] = true;

/**
 * What's the base URL of this installation?
 * (Include trailing slash!)
 *
 * Used in E-Mails, etc.
 */
$_CONFIG["URL"] = "http://example.com/wzet/";

/**
 * What to use as the sender e-mail adress?
 */
$_CONFIG["MAIL_FROM"] = "wzet@example.com";
?>
