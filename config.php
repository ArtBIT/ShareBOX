<?php if (! defined("BASEPATH")) {
    exit("No direct script access allowed");
}
if (! defined("SHAREBOX_CONSTANTS")) {
    define("VAGRANT_MYSQL_PASSWORD", "1234567890");

    define("SHAREBOX_WEBSITE_NAME", "ShareBOX");
    define("SHAREBOX_WEBSITE_HOSTNAME", "sharebox.local");
    define("SHAREBOX_WEBMASTER_EMAIL", "sharebox.unc.ac.rs+admin@gmail.com");
    define("SHAREBOX_ENCRYPTION_KEY", "encryptmeupbeforeyougogo");

    define("SHAREBOX_DATABASE_HOSTNAME", "localhost");
    define("SHAREBOX_DATABASE_USERNAME", "sharebox");
    define("SHAREBOX_DATABASE_PASSWORD", "shareboxdatabasepassword");
    define("SHAREBOX_DATABASE_NAME", "sharebox");

    define("SHAREBOX_SMTP_HOSTNAME", "ssl://smtp.googlemail.com");
    define("SHAREBOX_SMTP_USERNAME", "sharebox.uns.ac.rs");
    define("SHAREBOX_SMTP_PASSWORD", "QQQQQQQQQQQQQQQQ");

    define("SHAREBOX_ADMIN_USERNAME", "admin");
    define("SHAREBOX_ADMIN_PASSWORD", "admin");

    define("SHAREBOX_RECAPTCHA_PUBLIC_KEY", "6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI");
    define("SHAREBOX_RECAPTCHA_PRIVATE_KEY","6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe");

    define("SHAREBOX_CONSTANTS", 1);
}
