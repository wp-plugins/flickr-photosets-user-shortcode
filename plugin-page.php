<?php

add_action('admin_menu', 'flickr_photosets_user_option_menu');
function flickr_photosets_user_option_menu()
{
	add_options_page(
		'Flickr Photosets User Shortcode',
		'Flickr Photosets User Shortcode',
		'manage_options',
		'flickr-photosets-user-options',
		'flickr_photosets_user_options'
	);
}

function flickr_photosets_user_options()
{
	if (!current_user_can('manage_options')) {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
?>

<table class="wp-list-table widefat fixed" style="width: 600px; margin-top: 100px;">
	<thead>
		<tr>
			<th style="text-align: center;"><h1>Flickr Photosets User Shortcode</h1></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>
				<p>Para exibir os photosets basta inserir o shortcode 
					<span style="background-color: #CCC; padding: 1px 3px; font-size: 9pt;">[flickr-pu-shortcode user_id={NSID da conta}]</span>
					no conteúdo da página que deverá ser exibida. Existe também o atributo
					<span style="font-weight: bold;">max_photosets</span> que é a quantidade máxima de photosets que 
					devem ser exibidos 
					<span style="background-color: #CCC; padding: 1px 3px; font-size: 9pt;">[flickr-pu-shortcode user_id={NSID da conta} max_photosets=3]</span>.
				</p>
			</td>
		</tr>
	</tbody>
</table>
<?php } ?>