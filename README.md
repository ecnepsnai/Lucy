<p align="center">
  <img src="https://raw.github.com/ecnepsnai/Lucy/master/lucy-themes/default/preview.jpg"/>
</p>


# Lucy

Lucy is a conversation based online support system built in PHP that's easy to use, set-up, theme, and edit.  Lucy is the foundation for a really powerful CMS system that focuses more on theme developers rather than plugins, letting you make themes however you want.

## Release Candidate 1

Lots of bug fixes and feature improvements.

- **NEW** - MySQLi support (selected by default)
- **NEW** - Added Range input
- **IMPROVED** - Universal error handling and reporting through the `lucy_error()` function
- **IMPROVED** - Setup script is now a lot nicer (and prettier).  It also checks for missing required files.
- **IMPROVED** - Two-Step Authentication now provides a backup code which can be used if a generator is not present
- **FIXED** - Issue where SQLite wouldn't work at all

## Beta 4

**Lucy now is a completely customizable online form platform, allowing you to configure what you want from your users**

- **NEW** - Added new Designer mode to make your form
- **NEW** - Forms are dynamically generated from a json configuration file
- **NEW** - Added preflight checklist to help easy the setup of Lucy.  You can leave your cellphone on, however.
- **IMPROVED** - Tickets are now called Threads because its more *fancy*
- **IMPROVED** - Threads are no longer stored in their own tables, rather as JSON in the master threads table.
- **IMPROVED** - Updated to Bootstrap 3
- **IMPROVED** - CSS & JS are loaded from MaxCDN, source no longer included in releases
- **IMPROVED** - More AJAX, Less Call of Duty.
- **IMPROVED** - Bye bye, Imgur!  Image uploads are now done locally.
- **IMPROVED** - Simplified settings page, removed a lot of clutter.
- **IMPROVED** - Better Two-Step Authentication Setup
- **IMPROVED** - Temporarily Removed Akismet support (While I figure out how to make it work with the dynamic forms)
- **FIXED** - Finally got around to making Password Rest work normally
- **FIXED** - And Email Verification.  That works too.
- **FIXED** - Mailer functions actually, you know, send mail.
- **FIXED** - The initial setup page now actually exits correctly!
- **FIXED** - Numerous little bugs have been squashed.  Sorry, PETA.
- **FIXED** - Improved error handling.

## Beta 3

More improvements throughout the application including:

- SQLite3 support
- The ability to delete and edit ticket messages though AJAX
- Flagging tickets as spam (no automatic methods just yet)
- Lucy no longer escapes the SQL queries using `addslashes` before sending them to CDA, as CDA now escapes the strings for you.
- Bug fixes

## Beta 2

Massive improvements have been made to lucy all over the application.

- Lucy now uses CDA.  CDA is a PHP library that makes supporting multiple SQL database types easy!
- Replying and Closing tickets now uses AJAX, making it loads faster.
- Vastly improved speed through the application
- Removed a lot of unnecessary crap.
- Made assigning tickets to specific users easier

**Note:** reCaptcha and Imgur upload (through AJAX) currently are not functioning in this release, will be fixed in the next release.

## Beta 1

Lucy is now in Beta!  Currently there is no support for Microsoft SQL Server or SQLite.

- Added automatic setup script.
- Updated admin back-end to make way for assignments.

## Alpha Release

Lucy is now in Alpha!  Currently there is no support for Microsoft SQL Server or SQLite.

- Added full theming support
- Added administrator control panel for the site
- Added mailing functionality with full theming support
- Added configuration abilities
- Added password reset ability

## Setup

Installing lucy is easy! Make a database for Lucy to use, upload all of the files and open it in your web browser.  You will be automatically brought to the setup page.

## Todo

- [ ] Full Error Handling
- [ ] Add input validation based off of settings in Designer
- [ ] Write full documentation
- [ ] Execute function-control-shift-kill
