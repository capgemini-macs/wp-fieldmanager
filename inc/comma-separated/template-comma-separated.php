<?php
/**
 * Default template for Fieldmanager_Comma_Separated
 *
 * @package Fieldmanager
 */

$values = array_filter( explode( ',', $value ) );
?>

<div class="tagsdiv">
	<div class="jaxtag fm-jaxtag">
		<input style="display:none;" name="<?php echo esc_attr( $this->get_form_name() ); ?>" rows="3" cols="20" class="the-tags" id="tax-input-keywords" aria-describedby="new-tag-keywords-desc" value="<?php echo esc_attr( $value ); ?>" />

		<div class="ajaxtag hide-if-no-js">
			<input
				class="fm-element newtag"
				type="<?php echo esc_attr( $this->input_type ); ?>"
				name="newtag[<?php echo esc_attr( $this->get_form_name() ); ?>]"
				id="<?php echo esc_attr( $this->get_element_id() ); ?>"
				value=""
				<?php
				echo $this->get_element_attributes(); // Escaped internally. WPCS XSS okay.
				?>
			/>
			<input type="button" class="button tagadd" value="Add">
		</div>
	</div>

	<p class="howto" id="new-tag-keywords-desc"><?php esc_html_e( 'Separate keywords with commas', 'fieldmanager' ); ?></p>

	<ul class="tagchecklist" role="list">
		<?php

		if ( count( $values ) ) :
			$c = 0;
			foreach ( $values as $value ) :
				// Using default WordPress output here so we no custom styles and scripts are required
				?>
				<li>
					<button type="button" id="keywords-check-num-<?php echo absint( $c ); ?>" class="ntdelbutton">
						<span class="remove-tag-icon" aria-hidden="true"></span>
						<span class="screen-reader-text"><?php esc_html_e( 'Remove term:', 'fieldmanager' ); ?> <?php echo esc_html( $value ); ?></span>
					</button>&nbsp;<?php echo esc_html( $value ); ?>
				</li>
				<?php
			endforeach;
		endif;
		?>
	</ul>
</div>
