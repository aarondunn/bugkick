# Bugkick.com - Simple task management

----------

## Bugkick.com Installation Reference

1. Copy configuration files from /devconf to /protected/config/ and change them according to your settings.
2. Use /protected/data/bugkick_<date>.sql to setup DB and apply sql updates(in /www/dbup folder) starting from the <date>.
3. By default we use APC cache. You can change it main.php config or turn it off. To turn off the cache in main.php comment
out the line   	//	'class'=>'system.caching.CApcCache', and add the following 	'class'=>'system.caching.CDummyCache', 

### Instant Notifications

Node.js+Socket.io are required for instant notifications

## Development

1. Use /www/themes/bugkick_theme/css/dev.css for adding CSS changes, they will be merged with other styles later.
2. Use /www/dbup/ for adding SQL updates




This work is licensed under a Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License.
