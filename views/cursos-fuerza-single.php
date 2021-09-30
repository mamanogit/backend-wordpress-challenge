<?php

wp_enqueue_script( 'cursos_fuerza_script' );


$post_data = get_post( get_the_ID() );

?>

	<div>

		<h1><b><?php echo $post_data->post_title; ?></b></h1>

		<p>Waste Hours: <?php echo $post_data->waste_hour; ?></p>

		<p>Limit date: <?php echo $post_data->limit_date; ?></p>        

		<hr />

		<br />

		<h6><?php echo $post_data->post_content; ?></h6>

		<br />

		<p>
		<?php

		$date_now              = new DateTime();
		$date_now_formated     = $date_now->format( 'd-m-y' );
		$limited_date          = new DateTime( $post_data->limit_date );
		$limited_date_formated = $limited_date->format( 'd-m-y' );

		if ( $date_now > $limited_date ) {

			echo 'A data atual: ' . $date_now_formated . ' é maior que a data limite: ' . $limited_date_formated . ' ';
			echo 'Você não pode se inscrever mais.';

		} else {

			?>
			<form id="form_cf">

			  <h2>Tenho Interesse</h2>

			  <div class="form-group">
				<label for="form_cf_name">Nome:</label>
				<input type="text" class="form-control" id="form_cf_name" placeholder="Digite o nome">
			  </div>

			  <div class="form-group">
				<label for="form_cf_email">Email</label>
				<input type="email" class="form-control" id="form_cf_email" placeholder="Digite o email">
			  </div>

			  <input type="hidden" id="form_cf_id" value="<?php echo get_the_ID(); ?>" />  

			  <div> <a href="#" class="button" id="form_cf_submit">Increver-se</a> </div>
			  
			</form>


			<?php

		}

		?>
		</p>

	</div>



