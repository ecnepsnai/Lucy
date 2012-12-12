<?php
// Defines file
// Edit this to your liking.

// Your site's name
define("TITLE_MAIN", "Lucy");

// The title separator (Include whitespace)
define("TITLE_SEPARATOR", " | ");

// Your sites domain
define("SERVER_DOMAIN", "http://192.168.1.102/lucy/");

// Page Titles
define("TITLE_NEW_TICKET", "New Ticket");           // new_ticket.php
define("TITLE_SIGNUP", "Signup");                   // signup.php
define("TITLE_TICKET", "Ticket Status");            // ticket.php 
define("TITLE_ERROR", "Error");                     // edit_user.php  TODO: Add dynamic error titles
define("TITLE_EDIT_USER_EDIT", "Editing User:");    // edit_user.php
define("TITLE_LOGIN", "Login");                     // login.php
define("TITLE_DASH", "Dashboard");					// dash.php

// Session expire time (will log user out when activity doesn't pass this)
// This needs to be an integer.
define("SESSION_EXPIRE", 1800);

// Your imgur API key for Anonmyous API calls
define("API_IMGUR", "");


// The copyright tag.  May include HTML
define("FOOTER_COPYRIGHT", "Copyright &copy; Ian Spence 2012.");