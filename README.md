# OBP Scored Search

OBP Scored Search is a simple development plugin with no dependencies that gives a user a Scores taxonomy to score their post type or types by, then returns related items based on the scores.

OBP Scored Search has two master methods to return related posts, Closest and Best.

## Installation

Git clone or copy the plugin to the /wp-content/plugins/ directory, and activate the plugin from the Plugins section of the admin view.

## Configuration

The scoring-engine.php root file has a set of configuration options:
![options](https://i.imgur.com/pVpIEs5.jpg)

##### $scored_posttypes
An array (must be array) of standard or custom post types that you want to apply the scoring system to.

##### $score_base
The default top score you can give is 5. The count always starts at zero.

##### $allow_halves
This controls whether or not to use half-steps. For instance whether to allow a 3.5 score. If you're thinking to yourself you don't need a base 5 with half-steps to show it on the front-end, you could just use a base 10. Well, feel free as long as you're the one providing the support. 


##### $access_level
The Access Level variable controls who can see and assign a score in the admin view. This uses the [standard Wordpress roles](https://wordpress.org/support/article/roles-and-capabilities/).

##### $logo_url
This one is optional, but pretty cool. If you assign this variable a valid image file URL (hopefully small, circular, and lightweight) it will center the logo in the loading spinner that displays as AJAX is fetching related posts (all posts are fetched via AJAX.) 

This is a back-end plugin, with intentionally unstyled rendering, but a custom loading spinner is one of those little touches that sets a custom site apart from a fully templated one.

##### $default_search
The default search type. You can choose between "closest" and "best" - closest will return the posts most like the one it's being related to, and best will return the highest scoring posts with their scores weighted to reflect the post it's being related to. 

You can always set the search type each time you call an OBP Related Search in your templates, but a default is required.

#### $query_size
The Query Size variable sets the query size **before** any comparison is made. This is the rough equivalent of posts_per_page in the wp_query or numberposts in get_posts. The default is to hit all posts. 

# Calling an OBP Search in template files
Todo

License
----

MIT


**Free Software, Hell Yeah!**

