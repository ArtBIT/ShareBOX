<?php 
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * This file contains the Serbian language localisations for the CodeIgniter TankAuth library.
 *
 * @author      Djordje Ungar (djordje@ungar.rs)
 * @copyright   Copyright (c) 2014-2016 Djordje Ungar
 * @version     1.0.0
 * @package     ShareBOX
 * @license http://opensource.org/licenses/MIT  MIT License
 */

$click_here = '<br><br><a href="/auth/login">Kliknite ovde</a> kako biste se prijavili.';
// Errors
$lang['auth_incorrect_password'] = 'Neispravna lozinka';
$lang['auth_incorrect_login'] = 'Neispravna prijava';
$lang['auth_incorrect_email_or_username'] = 'Korisničko ime ili lozinka nisu ispravni';
$lang['auth_email_in_use'] = 'Odabrani e-mail je zauzet. Molimo Vas izaberite drugi e-mail.';
$lang['auth_username_in_use'] = 'Odabrano korisničko ime je zauzeto. Molimo Vas odaberite drugo korisničko ime.';
$lang['auth_current_email'] = 'Ovo je Vaš trenutni e-mail';
$lang['auth_incorrect_captcha'] = 'Kod koji ste uneli se ne podudara sa onim na slici.';
$lang['auth_captcha_expired'] = 'Kod koji ste uneli je istekao. Molimo Vas pokušajte opet.';

// Notifications
$lang['auth_message_logged_out'] = 'Uspešno ste se odjavili.';
$lang['auth_message_registration_disabled'] = 'Registracija je onemogućena.';
$lang['auth_message_registration_completed_1'] = 'Uspešno ste se registrovali. Proverite Vaš e-mail sanduče da biste aktivirali nalog.' . $click_here;
$lang['auth_message_registration_completed_2'] = 'Uspešno ste se registrovali.' . $click_here;
$lang['auth_message_activation_email_sent'] = 'Poslali smo aktivacioni e-mail na %s. Pratite instrukcije u poruci za aktivaciju naloga.';
$lang['auth_message_activation_completed'] = 'Vaš nalog je uspešno aktiviran' . $click_here;
$lang['auth_message_activation_failed'] = 'Aktivacioni kod koji ste uneli je istekao.';
$lang['auth_message_password_changed'] = 'Vaša lozinka je uspešno promenjena.' . $click_here;
$lang['auth_message_new_password_sent'] = 'E-mail sa instrukcijama za promenu lozinke je poslat na Vašu e-mail adresu.';
$lang['auth_message_new_password_activated'] = 'Uspešno ste resetovali lozinku.' . $click_here;
$lang['auth_message_new_password_failed'] = 'Aktivacioni kod koji ste uneli je neispravan ili je istekao. Molimo Vas da pročitate instrukcije poslate na Vaš e-mail i pokušate ponovo.';
$lang['auth_message_new_email_sent'] = 'Poslali smo konfirmacioni e-mail na %s. Pratite instrukcije u poruci za aktivaciju naloga.';
$lang['auth_message_new_email_activated'] = 'Uspešno ste promenili e-mail.' . $click_here;
$lang['auth_message_new_email_failed'] = 'Aktivacioni kod koji ste uneli je neispravan ili je istekao. Molimo Vas da pročitate instrukcije poslate na Vaš e-mail i pokušate ponovo.';
$lang['auth_message_banned'] = 'Proterani ste';
$lang['auth_message_unregistered'] = 'Vaš nalog je obrisan...';

// Email subjects
$lang['auth_subject_welcome'] = 'Dobrodošli na %s!';
$lang['auth_subject_activate'] = 'Dobrodošli na %s!';
$lang['auth_subject_forgot_password'] = 'Zaboravljena lozinka na %s?';
$lang['auth_subject_reset_password'] = 'Vaša nova lozinka na %s';
$lang['auth_subject_change_email'] = 'Vaš novi e-mail na %s';


/* End of file tank_auth_lang.php */
/* Location: ./application/language/english/tank_auth_lang.php */
