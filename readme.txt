=== Yakadanda Google+ Hangout Events ===
Contributors: Yakadanda.com
Donate link: http://www.yakadanda.com/
Tags: Calendar, Event, Google, Googleplus, Hangout, Plus, Yakadanda
Requires at least: 3.8
Tested up to: 3.9
Stable tag: 0.2.5
License: GPLv2 or later

Generate linked Google+ Hangout Event notifications in a widget from a Google Calendar with a nifty Countdown Clock to each event.

== Description ==

This plugin will generate linked Google+ Hangout Event notifications in a widget from a Google Calendar with a nifty Countdown Clock to each event. Fully customizable with fonts and colors.

= New Version 0.2.5 =
Ability to filter by only Google+ created Events

= Features =
* Display Regular Google+ Events in Posts or Pages via Widgets
* Display Google+ Hangouts Events in Posts or Pages via Widgets
* Display Regular Google+ Events in Posts or Pages via short-code
* Display Google+ Hangouts Events in Posts or Pages via short-code
* Choose colors, fonts, size, and style for the widget (Google Fonts Included)
* Links Directly to Hangout Event on Google+
* Display Single Events (See FAQ)
* Display Public Events from My calendars (exception for contacts calendar and country holidays calendar)
* Display Events via short-code with control using attributes (See plugin settings help tab)
* Support Google+ Embedded Posts

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

= How to find event identifier to create a single event shortcode? =
Single Event Example: https://plus.google.com/u/0/events/csnlc77gi4v519jom5gb28217so
The letters after `***.com/u/0/events/c` is an event id, so the event identifier will be `snlc77gi4v519jom5gb28217so` without first letter 'c'.
To create a single event you would place in shortcode `[google+events id="snlc77gi4v519jom5gb28217so"]`

= Shortcode Reference =

**Shortcode Examples**

* `[google+events]`
* `[google+events type="hangout"]`
* `[google+events src="gplus"]`
* `[google+events limit="3"]`
* `[google+events past="2"]`
* `[google+events author="all"]`
* `[google+events limit="5" type="normal" past="1" author="all"]`
* `[google+events id="xxxxxxxxxxxxxxxxxxxxxxxxxx"]`
* `[google+events filter_out="xxxxxxxxxxxxxxxxxxxxxxxxxx,xxxxxxxxxxxxxxxxxxxxxxxxxx"]`
* `[google+events search="free text search terms"]`
* `[google+events attendees="show"]`
* `[google+events timezone="America/Los_Angeles"]`
* `[google+events countdown="true"]`

**Attributes**

1. type	=	`all`, `normal`, or `hangout`, by default type is `all`
2. src	=	`all`, `gcal` (event from calendar), or `gplus` (event from google+), by default source is `all`
3. limit	=	number of events to display (maximum is 20)
4. past	=	number of months to display past events in `X` months ago, by default past is false
5. author	=	`self`, `other`, or `all`, by default author is `all`
6. id	=	Event identifier (string). Single Event Example: https://plus.google.com/u/0/events/csnlc77gi4v519jom5gb28217so To create a single event you would place in shortcode `[google+events id="snlc77gi4v519jom5gb28217so"]`
7. filter_out	=	Filter out certain events by event identifiers, seperated by comma
8. search	=	Text search terms (string) to display events that match these terms in any field, except for extended properties
9. attendees	=	Events can have attendees, the value can be `show`, `show_all`, or `hide`, the default value for attendees attribute is `hide`
10. timezone	=	Time zone used in the response, optional. Default is time zone based on location (hangout event not have location) if not have location it will use google account/calendar time zone. Supported time zones at http://www.php.net/manual/en/timezones.php (string)
11. countdown	=	`true`, or `false`, by default countdown is `false`

== Screenshots ==

1. Shortcode
2. Widget

== Changelog ==

= 0.2.5 =
* Updated google library
* Added 'other' value to author options
* Added source options (event from calendar or from google+)

= 0.2.4 =
* Added reset features
* Bug fixes and other small improvements

= 0.2.3 =
* Changed plugin redirect URI
* Changed connection method to Google API and saving method
* Updated google fonts
* Added settings link on plugins page
* Updated Help section

= 0.2.2 =
* Added rich snippets to shortcode and widgets

= 0.2.1 =
* Added countdown feature to shortcode
* Improved countdown feature on widgets

= 0.2.0 =
* Differentiated events and hangout events on shortcode
* Fixed widget tiltle on Event widget and Hangout widget

= 0.1.9 =
* Upgraded Google APIs Client Library to v0.6.7
* Updated Google fonts
* Added Google+ Embedded Posts  feature https://developers.google.com/+/web/embedded-post/

= 0.1.8 =
* Added timezone features to shortcode and widget
* Rewrote help sections

= 0.1.7 =
* Upgraded Google APIs Client Library to v0.6.6
* Moved How To manual and Shortcode manual to help tab
* Fixed the way to get timezone abbreviation

= 0.1.6 =
* Updated Google APIs Client Library

= 0.1.5 =
* Added email verification to google authentication and authorization
* Changed default author event from self to all
* Displayed coworker name who is organizer in shortcode
* Added attendees attribute to shortcode

= 0.1.4 =
* Added line breaks feature on event description

= 0.1.3 =
* Updated Google APIs Client Library for PHP to Google API PHP Client 0.6.2
* Added filter_out attribute on shortcode to filter out certain events by event identifiers
* Added search attribute on shortcode to display events that match the search terms in any field, except for extended properties

= 0.1.2 =
* Updated countdown feature to support next year's event
* Extended events fetch feature to load public events in more calendars (exception for contacts calendar and country holidays calendar)

= 0.1.1 =
* Improved Save and Connect action when trying to connect with google api

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

= 0.2.5 =
* Removed undefined index on widget

= 0.2.4 =
* Fixed enqueue script load to pass scripting guard from Codestyling Localization plugin
* Fixed logout issue

= 0.2.3 =
* Fixed Manual page

= 0.2.2 =
* Removed undefined index on shortcode

= 0.2.1 =
* Fixed id attribute bugs at shortcode

= 0.2.0 =
* Fixed default style for shortcode

= 0.1.9 =

* -

= 0.1.8 =
* Fixed bugs on calendar list function

= 0.1.7 =
* Fixed illegal string offset issues
* Fixed undefined index on plugin settings page

= 0.1.6 =
* Cleared up notices on frontend and backend, that is undefined index, undefined offset, and undefined variable
* Fixed event id bugs on shortcode
* Improved filter out attribute on shortcode

= 0.1.5 =
* Removed duplicate events on google response
* Fixed error messages on shortcode
* Fixed Invalid argument supplied for foreach in calendar list

= 0.1.4 =
* -

= 0.1.3 =
* -

= 0.1.2 =
* Fixed event time function for next year's event
* Improved event sort function

= 0.1.1 =
* Fixed fatal error: Cannot redeclare class URI_Template_Parser

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
