<?php
/*
Plugin Name: 0mk Shortener
Plugin URI: http://kuzmanov.info/0mk
Description: 0.mk Shortener генерира кратка врска за Вашите написи користејќи го македонскиот кратач на врски - Нула[точка]мк. 
Version: 0.2
Author: Boris Kuzmanov
Author URI: http://kuzmanov.info
*/

// Функција
function zeromk($url) {  
	$korisnik = get_option('$zeromk_user_op'); 
	if ($korisnik == "") { $korisnik = '0mkapi'; } // Користи го "0mkapi" ако нема внесено сопствен јузер
	$apikluc = get_option('$zeromk_apikey_op');
	if ($apikluc == "") { $apikluc = '1a7854a2226925282e54d1a01af5058c'; } // API клуч за "0mkapi"
	$nulamk = file_get_contents("http://api.0.mk/v2/skrati?korisnik=".$korisnik."&apikey=".$apikluc."&format=plaintext&link=".$url);  
return $nulamk;  
} 

// Креирање на мени
add_action('admin_menu', 'zeromk_options');
function zeromk_options() {
  add_options_page('0.mk Поставувања', '0.mk Поставувања',  8, __FILE__, 'zeromk_options_page');
}

// Зачувување на сетинзите
function zeromk_options_page() { ?>
<?php
$zeromk_options_saved = get_option('$zeromk_user_op');
$zeromk_apikey_saved = get_option('$zeromk_apikey_op');
if(isset($_POST['Submit'])) {
	$zeromk_options_saved = $_POST["zeromk_user"];
	$zeromk_apikey_saved = $_POST["zeromk_apikluc"];
	update_option('$zeromk_user_op', $zeromk_options_saved);
	update_option('$zeromk_apikey_op', $zeromk_apikey_saved);
?>
<div class="updated"><p><strong>Поставувањата се зачувани.</strong></p></div>
<?php } ?>

<div class="wrap">


<h2>0.mk Поставувања</h2>
<p>Доколку не внесете сопствено корисничко име и API клуч, ќе се користат стандардните поставувања од 0.mk ("0mkapi" и "1a7854a2226925282e54d1a01af5058c")</p>

<form method="post" name="options" target="_self">
<table>
<tr>
<td>
<td align="left" scope="row">
<label>Корисничко име:</label><br />
<input name="zeromk_user" value="<?php echo $zeromk_options_saved ?>" /> <br />
<label>API клуч:</label><br />
<input name="zeromk_apikluc" value="<?php echo $zeromk_apikey_saved ?>" /> <br /><br />
</td>
</tr>
</table>

<p class="submit">
<input name="Submit" type="submit" class="button-primary" value="Зачувај ги промените" />
</p>
</form>

</div>
<?php
}

// Додавање на кратката врска на крајот од написот
add_filter('the_content', 'VmetniZeroMk');

function VmetniZeroMk($content) {
$adresa = zeromk(get_permalink($post->ID));
	if(is_single()) {
		$content.= 'Кратка врска: <a href="'.$adresa.'">'.$adresa.'</a>';
	}
	return $content;
}
?>
