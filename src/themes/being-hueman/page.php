<?php get_header(); ?>

<section class="content">

	<?php get_template_part('parts/page-title'); ?>

	<div class="pad group">

        <!-- Do not display full page widget area on startpage -->
        <?php if(!is_home() && !is_front_page()) { if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('page_content') ) : endif; } ?>

		<?php while ( have_posts() ): the_post(); ?>

			<article <?php post_class('group'); ?>>

				<?php get_template_part('parts/page-image'); ?>

				<div class="entry themeform">
					<?php the_content(); ?>
					<div class="clear"></div>
				</div><!--/.entry-->

			</article>

			<?php if ( hu_is_checked('page-comments') ) { comments_template('/comments.php',true); } ?>

		<?php endwhile; ?>

	</div><!--/.pad-->

</section><!--/.content-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>