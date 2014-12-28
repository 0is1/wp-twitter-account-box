=== Twitter Account Box aka TAB ===

Contributors: 0is1
Tags: Twitter, Social Media, widget
Requires at least: 3.9.0
Tested up to: 4.1
Stable tag: 0.2.12
License: GPLv2 or later


Plugin that adds your Twitter account details box in your Wordpress site via widget.

== Description ==

Plugin that adds your Twitter account details box in your Wordpress site via widget.

Work in progress... but it's working! Like this: https://raw.githubusercontent.com/0is1/wp-twitter-account-box/master/public/images/twitteraccountbox-example.png

[Visit plugin site](http://tab.jannejuhani.net/)

## Roadmap towards version 1.0

This plugin works already. But there's still things TODO:

* Enable Twitter authentication as default (maybe Premium?)
* Figure out if it's possible to check if user already follows the account and show "Following"-button instead of "Follow"-button
  * Maybe [GET friendships/exists](https://dev.twitter.com/docs/api/1/get/friendships/exists)
* Style Tweet button and load Twitter images locally if user is using any "anti social media"-plugin in browser
* Style admin panel options-page
* Make TAB more responsive
* Better error handling
* Documentation
* Test with different WP-versions

#### Shortcode
* You can use this shortcode in posts and pages: [twitter_account_box]

### Next version: 0.3.0
* Better error handling with tweet data and code refactoring

### Version: 0.4.0
* Support to favorite tweets

== Installation ==

* Upload/Install the plugin and activate it
* Go to Settings -> Twitter Account Box
* Fill in all Twitter auth-inputs, you can get those from [Twitter Development-site](https://dev.twitter.com/)
* Add Twitter username you want to show on your site
* Save and go to Appearance -> Widgets, and add Twitter Account Box Widget to your Widget area

== Screenshots ==

1. Admin panel settings
2. Twitter Account Box in action
3. Go to dev.twitter.com and click "Developers -> Documentation"
4. Click "Manage My Apps"
5. Click "Create New App"
6. Create Twitter App
7. Go to "API-keys" page and click "Create Access Token"
8. Get key, token and secrets

== Frequently Asked Questions ==

= How I get Twitter Consumer Key etc.? =

You can get those from [Twitter Development-site](https://dev.twitter.com/). Sign in or create new account, after login click "Developers -> Documentation" (top left dropdown), click "Manage My Apps", on the next page click "Create New App" and complete the process. Then click "Create my access token"-button on "API Keys"-page (see screenshots).

== Debug ==

Did you notice a bug?
Please tell about it: janne [AT] jannejuhani.net
[Or open an issue on GitHub](https://github.com/0is1/wp-twitter-account-box)

Thanks!

== Changelog ==

= 0.2.12 =

* Add support to shortcode: [twitter_account_box]
* Some small code refactoring

= 0.2.11 =

* Add small style fixes

= 0.2.10 =

* Add some style fixes
* Add better screenshots and info about how to register Twitter Application

= 0.2.9.1 =

* Fix nested tweet-divs
* Fix image overflow style problem with some themes and browsers

= 0.2.9 =

* Enable tweets
* Add some styles

= 0.2.1 =

* Make TAB a little bit more responsive
* Add some style defaults

= 0.2.0 =

* Add new screenshots
* Edit options-page auth input names
* Add some developer info and donate-button on options-page
* Add some style fixes
* Add icons

= 0.1.0 =

* First version