<?php 
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * This file contains the Serbian language localisations for the CodeIgniter image library.
 *
 * @author      Djordje Ungar (djordje@ungar.rs)
 * @copyright   Copyright (c) 2014-2016 Djordje Ungar
 * @version     1.0.0
 * @package     ShareBOX
 * @license http://opensource.org/licenses/MIT  MIT License
 */

$lang['imglib_source_image_required'] = "Morate navesti izvornu sliku u vašim karakteristikama.";
$lang['imglib_gd_required'] = "GD biblioteka slika je neophodna za ovu karakteristiku.";
$lang['imglib_gd_required_for_props'] = "Vaš server mora podržavati GD biblioteku slika kako bi se odredile osobine slike.";
$lang['imglib_unsupported_imagecreate'] = "Vaš server ne podržava  GD funkciju koja je neophodna za obradu ovog tipa slike.";
$lang['imglib_gif_not_supported'] = "GIF slike često nisu podržane zbog ograničenja licenciranja. Možda ćete morati da koristite umesto toga JPG ili PNG.";
$lang['imglib_jpg_not_supported'] = "JPG slike nisu podržane.";
$lang['imglib_png_not_supported'] = "PNG slike nisu podržane.";
$lang['imglib_jpg_or_png_required'] = "Protokol za promenu veličine slike naveden u vašim karakteristikama radi jedino sa JPEG ili PNG tipovima slike.";
$lang['imglib_copy_error'] = "Došlo je do greške prilikom pokušaja ad se zameni fajl.  Molimo vas uverite se da se u vašem fajl direktorijumu može upisivati.";
$lang['imglib_rotate_unsupported'] = "Rotacija slike se ne pojavljuje kako bi bila podržana od strane vašeg servera.";
$lang['imglib_libpath_invalid'] = "Putanja do vaše biblioteke slika nije ispravna. Molimo vas podesite tačnu putanju u vašim karakteristikama slike.";
$lang['imglib_image_process_failed'] = "Obrada slike nije uspela. Molimo proverite da  vaš server podržava izabran protokol i da je putanja do vaše biblioteke slika tačna.";
$lang['imglib_rotation_angle_required'] = "Ugao rotacije je neophodan da bi se rotirala slika.";
$lang['imglib_writing_failed_gif'] = "GIF slika.";
$lang['imglib_invalid_path'] = "Putanja do slike nije tačna.";
$lang['imglib_copy_failed'] = "Program kopiranja slike nije uspeo.";
$lang['imglib_missing_font'] = "Nije moguće pronaći font za korišćenje.";
$lang['imglib_save_failed'] = "Nije moguće sačuvati sliku. Molimo uverite se da su slika i fajl direktorijum upisivi.";


/* End of file imglib_lang.php */
/* Location: ./system/language/english/imglib_lang.php */
