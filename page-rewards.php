<?php
/*
Template Name: Rewards
*/

add_filter( 'body_class', 'dma_add_dashboard_class' );
function dma_add_dashboard_class( $classes ) {
	$classes[] = 'rewards';
	return $classes;
}

remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'dma_user_dashboard' );
function dma_user_dashboard() {
	?>
	<div class="rewards-wrap">
		<h1><?php the_title(); ?></h1>
		<?php
			$filter = isset( $_GET['filter'] ) ? $_GET['filter'] : 'all';
			$style = ( 'all' != $filter ) ? ' style="visibility: hidden;"' : '';

			?>
			<ul class="filter-buttons-wrap button-group buttons">
				<?php
				dma_filter_menu_item( 'All', null, 'get-rewards' );
				dma_filter_menu_item( 'Limited Time', null, 'get-rewards' );
				dma_filter_menu_item( 'Limited Qty.', null, 'get-rewards' );
				dma_filter_menu_item( 'Bookmarked', null, 'get-rewards' );
				?>
				<li>
					<a class="help pop" href="#how-to-claim" data-popheight="auto"><div class="q icon-help-circled"></div><span>How do I claim rewards?</span></a>
				</li>

			</ul>
			<div class="reward-list">
			<?php
			$rewards = dma_get_available_rewards_for_user();

			$helper = new DMA_Reward();

			$reward_output = '';
			foreach ( $rewards as $reward ) {

				// Create a new reward object and display our output
				$helper->init_reward( $reward );
				echo $helper->output();
				echo $helper->modal();

			} // End foreach

			// display our redemption form
			echo $helper->confirm_output();

			// echo dma_reward_page_output( dma_get_available_rewards_for_user() );

			?>
			</div><!-- .reward-list -->
	</div><!-- .rewards-wrap -->
	<div class="clear"></div>
	<div id="how-to-claim" class="popup close">
		<?php dashboard_popup_content( 'How do I claim rewards?', true ); ?>
		<a class="button secondary close-popup" href="#">Close</a>
	</div>
	<?php
}

function dma_reward_page_output( $rewards ) {
	if ( empty( $rewards ) )
		return;

	$reward_output = '';
	foreach ( $rewards as $reward ) {
		$reward_output .= '<li><p>' .$reward->title .'</p></li>';
		$reward_output .= dma_claim_reward_form( $reward->ID );
	} // End foreach

	return $reward_output ? '<ul class="reward-list">'. $reward_output .'</ul><!-- .reward-list -->' : $reward_output;

}
genesis();
