<?php
// Basé sur https://code.antopie.org/miraty/libreqr/src/branch/main/config.inc.php
// This file is part of LibreQR, which is distributed under the GNU AGPLv3+ license

// LIBREQR SETTINGS

// Theme's directory name
define("THEME", $_ENV["LIBREQR_THEME"]);

// Language used if those requested by the user are not available
define("DEFAULT_LOCALE", $_ENV["LIBREQR_DEFAULT_LOCALE"]);

// Will be printed at the bottom of the interface
define("CUSTOM_TEXT_ENABLED", $_ENV["LIBREQR_CUSTOM_TEXT_ENABLED"] == "true");
define("CUSTOM_TEXT", $_ENV["LIBREQR_CUSTOM_TEXT"]);

// Default values
define("DEFAULT_REDUNDANCY", $_ENV["LIBREQR_DEFAULT_REDUNDANCY"]);
define("DEFAULT_MARGIN", intval($_ENV["LIBREQR_DEFAULT_MARGIN"]));
define("DEFAULT_SIZE", intval($_ENV["LIBREQR_DEFAULT_SIZE"]));
define("DEFAULT_BGCOLOR", $_ENV["LIBREQR_DEFAULT_BGCOLOR"]);
define("DEFAULT_FGCOLOR", $_ENV["LIBREQR_DEFAULT_FGCOLOR"]);
?>
