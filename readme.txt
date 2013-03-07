=== Yakadanda Google+ Hangout Events ===
Contributors: Yakadanda.com
Donate link: http://www.yakadanda.com/
Tags: Google, Hangout, Hangouts, Events, Google+, Googleplus, Calendar, Calendars, Yakadanda
Requires at least: 3.4.0
Tested up to: 3.5.1
Stable tag: 0.1.0
License: GPLv2 or later

Generate linked Google+ Hangout Event notifications in a widget from a Google Calendar with a nifty Countdown Clock to each event.

== Description ==

This plugin will generate linked Google+ Hangout Event notifications in a widget from a Google Calendar with a nifty Countdown Clock to each event. Fully customizable with fonts and colors.

= Features =
* Display Regular Google+ Events in Posts or Pages via Widgets
* Display Google+ Hangouts Events in Posts or Pages via Widgets
* Display Regular Google+ Events in Posts or Pages via short-code
* Display Google+ Hangouts Events in Posts or Pages via short-code
* Choose colors, fonts, size, and style for the widget (Google Fonts Included)
* Links Directly to Hangout Event on Google+

== Installation ==

1. Upload the full directory into your wp-content/plugins directory
2. Activate the plugin at the plugin administration page
3. Configure Google+ Hangout Events using the following pages in the admin panel: Settings -> Google+ Hangout Events.

That's it your done, you can add the Google Plus Hangout Events Widget to any sidebar.

== Frequently Asked Questions ==

= Do I need an account on Google? =
You only need an Google+ account.

= How do I change default style to my preferences? =
Use google-hangout-events.css in yakadanda-google-hangout-events/css/google-hangout-events.css as reference. Copy that file to your active-theme/css/ as google-hangout-events.css

= How to create a single event shortcode? =
Single Event Example: https://plus.google.com/u/0/events/csnlc77gi4v519jom5gb28217so
To create a single event you would place in shortcode [google+events id="snlc77gi4v519jom5gb28217so"]

== Screenshots ==


== Changelog ==

= 0.1.0 =
* Added feature to display an event on shortcode based on event identifier

= 0.0.9 =
* Added link to Event button, Hangout button, and On Air button
* Updated Google APIs Client Library for PHP to Google API PHP Client 0.6.1

= 0.0.8 =
* Added custom style feature for user preferences
* Added Event button customization in plugin settings

= 0.0.7 =
* Added handling api error for 403 http status

= 0.0.6 =
* No today events or future events on the shortcode which have past attribute

= 0.0.5 =
* Added author filter feature on widget and shortcode
* Added past option on shortcode only

= 0.0.4 =
* Added new widgets for normal event type
* Added google+ events shortcode
* Added time ago function for event create or event updated on the shortcode
* Added google maps link with query search on the shortcode for normal event
* Added Google fonts

= 0.0.3 =
* Added 2nd widget as widget (extra)
* Added countdown option feature for 2nd widget
* Added logout action

= 0.0.2 =
* Updated to Google Calendar API v3
* Added instructions page as manual
* Displayed the connection status

= 0.0.1 =
* Used calendar feed in Google Calendar API v2

== Upgrade Notice ==

= 0.1.0 =
* -

= 0.0.9 =
* Improved events query function
* Fixed PHP notices in custom style/css function

= 0.0.8 =
* Defined plugin version, plugin directory/url, active theme directory/url
* Improved the enqueue style

= 0.0.7 =
* Fixed events query function to prevent site errors by 403 http status

= 0.0.6 =
* Fixed past event on the shortcode
* Fixed date time and countdown feature in the widgets for regular calendar event
* Fixed date time for regular calendar event in the shortcode

= 0.0.5 =
* Fixed PHP notices

= 0.0.4 =
* Improved the event time
* Fixed message widget if no hangout event or no normal event

= 0.0.3 =
* Fixed bugs in widget if google calendar not connected

= 0.0.2 =
* Fixed blank issues in widget if browser installed Addllock add-on
* Fixed bugs if no hangout event
