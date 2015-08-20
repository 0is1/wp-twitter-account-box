# Twitter Account Box

Plugin that adds your Twitter account details box in your Wordpress site via widget.

### See plugin in [WordPress Plugin Directory](http://wordpress.org/plugins/twitter-account-box/)

## Development
* There's Gruntfile that watches changes in .styl and .js files from /src
* Install node_modules
```
npm install
```
* Start grunt
```
grunt
```
* Build and minify
```
grunt build
```

## This is work in progress... but it's working!

* Like this:
* ![Image](public/images/twitteraccountbox-example.png?raw=true)

## Roadmap towards version 1.0
This plugin works already. But there's still things TODO:
* Enable Twitter authentication as default (maybe Premium?)
* Figure out if it's possible to check if user already follows the account and show "Following"-button instead of "Follow"-button
  * Maybe [GET friendships/exists](https://dev.twitter.com/docs/api/1/get/friendships/exists)
* Style Tweet button and load Twitter images locally if user is using any "anti social media"-plugin in browser
* ~~Enable shortcodes~~
* Style admin panel options-page
* Make TAB more responsive
* Better error handling
* Documentation
* Test with different WP-versions

### Next version: 0.3.0
* Better error handling with tweet data

### Version: 0.4.0
* Support to favorite tweets

## Twitter Account Box uses and thank

* [twitter-api-php](https://github.com/J7mbo/twitter-api-php)

## Did you notice a bug?
* Please tell about it: janne [AT] jannejuhani.net
* Or open an [issue here on GitHub](https://github.com/0is1/wp-twitter-account-box/issues)

Thank you!


#### Notes
* https://dev.twitter.com/oauth/application-only