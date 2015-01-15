<?php get_header(); ?>

<?php query_posts(array(
    'posts_per_page' => 12,
    'post_type' => 'product'
)); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<?php echo woocommerce_get_product_thumbnail(); ?>
<h3><a href="<?php the_permalink(); ?>"><?php woocommerce_template_single_title(); ?></a></h3>
<div class="add-to-cart">
    <?php woocommerce_simple_add_to_cart(); ?>
</div>
<div class="product-price">
    <?php woocommerce_template_single_price(); ?>
</div>

<div class="rate">
    <?php woocommerce_template_single_rating(); ?>
</div>
<div class="single-meta">
    <?php woocommerce_template_single_meta(); ?>
</div>

<br />
<hr />
<br />
    <?php endwhile; ?>

    

<?php else : ?>

    <h2>Not Found</h2>

<?php endif; ?>



<?php get_footer(); ?>