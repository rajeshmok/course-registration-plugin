<?php /*Template Name:Course Registration Detail*/ get_header(); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

<div class="entry-content" itemprop="mainContentOfPage">
<?php if(isset($_REQUEST['courses'])){
	echo do_shortcode('[course_registration_details_form]');
}
?>
</div>

</article>
<?php endwhile; endif; ?>
<?php get_footer(); ?>