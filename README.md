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
This controls whether or not to use half-steps. For instance whether to allow a 3.5 score. If you're thinking to yourself you don't need a base 5 with half-steps to show it on the front-end, you could just use a base 10. Well, feel free since you're the one providing support. In my experience content creators don't think like that so half-steps lets you match what you see in the admin panel with the front end.

##### $access_level
The Access Level variable controls who can see and assign a score in the admin view. This uses the [standard Wordpress roles](https://wordpress.org/support/article/roles-and-capabilities/).

##### $logo_url
This one is optional, but pretty cool. If you assign this variable a valid image file URL (hopefully small, circular, and lightweight) it will center the logo in the loading spinner that displays as AJAX is fetching related posts (all posts are fetched via AJAX.) 

This is a back-end plugin, with intentionally unstyled rendering, but a custom loading spinner is one of those little touches that sets a custom site apart from a fully templated one.

##### $default_search
The default search type. You can choose between "closest" and "best" - closest will return the posts most like the one it's being related to, and best will return the highest scoring posts with their scores weighted to reflect the post it's being related to. 

You can always set the search type each time you call an OBP Related Search in your templates, but a default is required.

##### $query_size
The Query Size variable sets the query size **before** any comparison is made. This is the rough equivalent of posts_per_page in the wp_query or numberposts in get_posts, used as the default when getting the data set to compare scores with. The default is all posts. 

This isn't the number of related posts shown, that's controlled in the call to OBP Search, this is just limiting the number of items being compared. Scored search can potentially be a long process, as it can't take advantage of the typical wp cache, so if you have a huge DB you can limit the items grabbed by default here. This is just the default and can be overridden in the search call.

# Calling an OBP Search in template files
To make an OBP Scored Search in your front-end templates, you need to use the ScoreTheme object in /theme/theme.php by calling it in your php template file like so:
```php 
use obp_score\theme\ScoreTheme as ScoreTheme;
```
After the use call you can set the target element in your HTML. I'm using Bootstrap classes as an example but the plugin isn't dependent on Bootstrap. Note all results will be appended as children to the .row element.

```
<div class="container">
    <div class="row" id="where_the_results_go">
     <!-- Results from the call will be dumped here -->
     </div>
</div>
 ```

Once you have access to the search object and you have a target to dump the results, you need two arguments to make a Scored Search, $wp_query_args and $obp_search_args. 

The $wp_post_args are used directly in a [get_posts()](https://developer.wordpress.org/reference/functions/get_posts/) call, and retrieve the posts to compare to your score.

The $obp_search_args specify which type of search to use, which post this is relating to, the container target, and how many posts to return. Here's an example of a typical call made within the loop:

```php
<?php 

  $wp_post_args = array (
    "post_type" => "post",
    "numberposts" => 50,
  );
  $obp_search_args = array (
    "searchtype" => "best",
    "post_id" => $post->ID,
    "container_id" => "where_the_results_go",
    "maximum_posts" => 4,
  );

  ScoreTheme::obp_scored_search($wp_post_args, $obp_search_args);

?>
```
Since these calls can be data-intensive, all related searches happen via AJAX after page load. The plugin is not dependent on jQuery for the AJAX calls.

#Dealing with the Output
You can customize your output in the /build/js/scoring_engine.js file. The render_related_posts() function gives you simple markup control of the output and includes a full map of the item you are rendering.


License
----

MIT


**Free Software, Hell Yeah!**

