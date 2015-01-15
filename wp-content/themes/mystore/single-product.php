<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
                    <div class="product-thumbnail">
                        <?php woocommerce_show_product_images(); ?>
                    </div>
                    <h2><?php woocommerce_template_single_title() ?></h2>
                    <div class="price">
                        <?php woocommerce_template_single_price(); ?>
                    </div>
                    <div class="add-to-cart"><?php woocommerce_template_single_add_to_cart(); ?></div>
                    <div class="short-desc"><?php woocommerce_template_single_excerpt(); ?></div>
			<?php woocommerce_template_single_meta(); ?>

			<div class="entry">
				
				<?php the_content(); ?>

				<?php wp_link_pages(array('before' => 'Pages: ', 'next_or_number' => 'number')); ?>
				
				<?php the_tags( 'Tags: ', ', ', ''); ?>

			</div>
			
			<?php edit_post_link('Edit this entry','','.'); ?>
			
		</div>

	<?php comments_template(); ?>

	<?php endwhile; endif; ?>

<?php get_footer(); ?>

