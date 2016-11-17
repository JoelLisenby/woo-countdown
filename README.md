# woo-countdown
A simple product countdown plugin for WordPress and WooCommerce with a javascript countdown display

* Adds a countdown timer to the single product page.
* Hides the buy form from a product's page when the time is up after refresh.
* Keeps the buy form for users that have accessed the product before the timer ends so they may purchase.
* Does not disable the ability to purchase the product if it has already been added to a cart before the timer runs out.

# Setting the start and end dates
Set the "Countdown Start Date" and "Countdown End Date" options under the "Advanced" tab of  "Product Data" 

# Example Manual Template Usage

Displays the counter for a single product

```
$args = array(
  'post_type'   =>  'product',
  'stock'       =>  1,
  'showposts'   =>  1,
  'orderby'     =>  'date',
  'order'       =>  'DESC'
);

$loop = new WP_Query( $args );

while ( $loop->have_posts() ) {

  $loop->the_post();
  global $product;

  $countdown_start = new DateTime( get_post_meta( $loop->post->ID, '_countdown_start', true ) .' 00:00:00 '. get_option('gmt_offset') );
  $countdown_end = new DateTime( get_post_meta( $loop->post->ID, '_countdown_end', true ) .' 23:59:59 '. get_option('gmt_offset') );
  $now = new DateTime('NOW');

  $month = $countdown_start->format("n") - 1; // javascript Date starts months with 0...
  $start = $countdown_start->format("'Y',") ."'". $month ."',". $countdown_start->format("'j','H','i','s'");
  $end = $countdown_end->format("'Y',") ."'". $month ."',". $countdown_end->format("'j','H','i','s'");
  echo '<div class="counter center" id="jblcountdown"></div><script>var woocountdown = new jblcountdown( \'jblcountdown\', new Date('. $start .'), new Date('. $end .') );</script>';
  
}
```
