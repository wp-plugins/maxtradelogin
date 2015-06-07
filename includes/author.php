<?php get_header(); ?>

<section class="content">

<!-- page title -->
<div class="page-title pad group">
	<?php if ( is_author() ){ ?>
		<?php $author = get_userdata( get_query_var('author') );?>
		<h1><i class="fa fa-user"></i><?php _e('User:','mtltextdomain'); ?> <span><?php echo $author->display_name;?></span></h1>
	<?php }else{ ?>
		<h2><?php the_title(); ?></h2>
	<?php } ?>
</div>

</section>

<?php get_sidebar(); ?>

<?php get_footer(); ?>