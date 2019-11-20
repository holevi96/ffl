<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content clearfix">
		<h1>A fizetés sikertelen volt. Próbálja újra!</h1>
        <p>

            <img src="https://eteltazeletert.hu/wp-content/uploads/2018/12/Egyéni összeg JPG.jpg" alt="">
        </p>
        <p>
            <button><a href="/bejelentkezes-regisztracio">Vissza a fiókomhoz</a></button>
            <br>
        </p>

	</div>
	<?php wp_link_pages(array(
		'before' => '<div class="g5plus-page-links"><span class="g5plus-page-links-title">' . esc_html__('Pages:', 'g5plus-academia') . '</span>',
		'after' => '</div>',
		'link_before' => '<span class="g5plus-page-link">',
		'link_after' => '</span>',
	)); ?>

</div>