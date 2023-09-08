<?php 
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * This file contains the Serbian language localisations for the CodeIgniter email library.
 *
 * @author      Djordje Ungar (djordje@ungar.rs)
 * @copyright   Copyright (c) 2014-2016 Djordje Ungar
 * @version     1.0.0
 * @package     ShareBOX
 * @license http://opensource.org/licenses/MIT  MIT License
 */

$lang['email_must_be_array'] = "Metodi za email validaciju mora biti prosledjen niz.";
$lang['email_invalid_address'] = "Nevažeća email adresa: %s";
$lang['email_attachment_missing'] = "Nije moguće locirati navedeni email prilog: %s";
$lang['email_attachment_unreadable'] = "Nije moguće otvoriti ovaj prilog: %s";
$lang['email_no_recipients'] = "Morate uključiti primaoce: To, Cc, or Bcc";
$lang['email_send_failure_phpmail'] = "Nije moguće poslati email koristeći PHP mail(). Vaš server možda nije konfigurisan da šalje email koristeći ovaj metod.";
$lang['email_send_failure_sendmail'] = "Nije moguće poslati email koristeći PHP Sendmail.  Vaš server možda nije konfigurisan da šalje email koristeći ovaj metod.";
$lang['email_send_failure_smtp'] = "Nije moguće poslati email koristeći PHP SMTP.  Vaš server možda nije konfigurisan da šalje email koristeći ovaj metod.";
$lang['email_sent'] = "Vaša poruka je uspešno poslata koristeći navedeni protokol: %s";
$lang['email_no_socket'] = "Nije moguće otvoriti socket to Sendmail. Molimo vas proverite podešavanja.";
$lang['email_no_hostname'] = "Niste naveli SMTP hostname.";
$lang['email_smtp_error'] = "Pojavila se navedena SMTP greška: %s";
$lang['email_no_smtp_unpw'] = "Greška: Morate dodeliti SMTP korisničko ime i lozinku.";
$lang['email_failed_smtp_login'] = "Neuspelo slanje AUTH LOGIN komande. Greška: %s";
$lang['email_smtp_auth_un'] = "Neuspela autentifikacija korisničkog imena. Greška: %s";
$lang['email_smtp_auth_pw'] = "Neuspela autentifikacija lozinke. Greška: %s";
$lang['email_smtp_data_failure'] = "Nije moguće poslati podatke: %s";
$lang['email_exit_status'] = "Status pri izlazu: %s";


/* End of file email_lang.php */
/* Location: ./system/language/english/email_lang.php */
