function render_related_posts( $post, containerid ){
   if($post !== 'success') { //don't attempt to render the success message
        var div = document.createElement('div');
    
        div.classList.add( 'card', 'col-6', 'col-sm-4', 'col-md-3', 'card-' + $post.post_type );
    
        div.id = 'post-' + $post.ID;
    
        var markup = '<a href="' + $post.post_url + '"><div class="card-body" style="background:url('+ $post.thumbnail_url +') center/cover">';
    
        markup += '<h5 class="card-title">'+ $post.post_title +'</h5></div>'; 
    
        div.innerHTML = markup;
        console.log( containerid );
        document.getElementById(containerid).appendChild(div); 
   }
}


/* MAP of an example return object to render
 * 
 {
    ID: 16
    post_author: "1"
    post_date: "2019-08-25 04:57:10"
    post_date_gmt: "2019-08-25 04:57:10"
    post_content: "Content Example"
    post_title: "Title Example"
    post_excerpt: ""
    post_status: "publish"
    comment_status: "closed"
    ping_status: "closed"
    post_password: ""
    post_name: "fast-boat-transfers"
    to_ping: ""
    pinged: ""
    post_modified: "2020-03-14 17:34:00"
    post_modified_gmt: "2020-03-14 17:34:00"
    post_content_filtered: ""
    post_parent: 0
    guid: "GUID"
    menu_order: 0
    post_type: "activities"
    post_mime_type: ""
    comment_count: "0"
    filter: "raw"
    obp_score: 4.108695652173912
    thumbnail_url: "http://localhost/bt/gili/wp-content/uploads/2019/08/image11-150x150.jpg"
    post_url: ""
 }
 */