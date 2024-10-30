<?php
/**
 * The Template for displaying all single posts.
 */
 
// load general options
$cfo = get_option('classyfrieds_options');
// load financial data (extension paid_content)
$cfch = get_option('cpc_data');
// language file -> declares $cfl[] 
$cfl = get_option('classyfrieds_language');

get_header(); ?>

		<div>
			<div style="text-align:center">
			<h1><?PHP echo $cfl[archive_title]; ?></h1>
			<h1><?php post_type_archive_title(); ?></h1>
				<?php while ( have_posts() ) : the_post(); ?>

					<nav id="nav-single">
						<h3 class="assistive-text"><?phpecho $cfl[post_nav]; ?></h3>
						<span class="nav-previous"><?php previous_post_link(); ?></span>
						<span class="nav-next"><?php next_post_link(); ?></span>
					</nav><!-- #nav-single -->
					
					<div class="cf_classyclear"></div>
					
					<div class='classyfrieds_archive'>
							<div class="lblock_classyfrieds_archive"></div>
						<div class='classyfrieds_bump_left'>
							<div class='classyfrieds_title_archive'>
							<a href='<?PHP the_permalink(); ?>'><?PHP the_title(); ?></a>
							</div>
							<?PHP the_excerpt(); ?>
							<div class="cf_clearclear"></div>
						</div>
						<div class="cf_clearclear"></div>
					</div>
					
					<div class="cf_classyclear"></div>

					<?php comments_template( '', true ); ?>

				<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary -->
<div style="clear:both"></div>
<?php get_footer(); ?>
