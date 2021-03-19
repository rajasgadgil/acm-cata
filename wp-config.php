<?php
define('WP_CACHE', true); // Added by WP Rocket
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clefs secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur 
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C'est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d'installation. Vous n'avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */
/** Mise à jour limite memory. RBA 080217 */
 define( 'WP_MEMORY_LIMIT', '256M' );
 define( 'WP_MAX_MEMORY_LIMIT', '512M' );
 
 /** forceSSL */
define('FORCE_SSL_ADMIN', false);
 
// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define('DB_NAME', 'acmcata803213');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'acmcata803213');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', 'ACMCata803213');

/** Adresse de l'hébergement MySQL. */
define('DB_HOST', 'acmcata803213.mysql.db');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8');

/** Type de collation de la base de données. 
  * N'y touchez que si vous savez ce que vous faites. 
  */
define('DB_COLLATE', '');

/**#@+
 * Clefs uniques d'authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant 
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n'importe quel moment, afin d'invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'C9ZY(PHHG~5>zPRGPx8S&N,vcRnhyeT1UT_h6>DqU|NT*cFYQX`GPy]^lS6OpdzF');
define('SECURE_AUTH_KEY',  'k86*x-753p!W]- GaP9uPo~(iHa10ELa0+o,ap{[`G|a]K@$(Fna[jtadZ@z[ID&');
define('LOGGED_IN_KEY',    '=DRhtD|;D%@4{aPUGj=iB#.NXjN(%+aBqY_<d4](+.Z,-%MAEDX$v)[La&Y[3}%#');
define('NONCE_KEY',        'HsJh|kW*Fo(phwc`T%QhR7`&|_Gf+Rw={*=1YIGq],,sk]4]~4e^2+G8j;=D-xM4');
define('AUTH_SALT',        '423=?x+M{VO|lTuHnZk6r0`%*.D@sQ:we<y[>} ITx|_![ J65{NN=EO)6wR1s0%');
define('SECURE_AUTH_SALT', '&7Aei2`r8)#r/%ttsO<p1&fH6<[y]#,xdC]J`kB <v8ZFZY$=2ey5[Ga5=w`HQc2');
define('LOGGED_IN_SALT',   '/]/nUkQL7A5{H+xFU~l{4;D#=4$?D45Ng[{`C.3?6yaC;{ZF~.-RJB:*~;Y$ w z');
define('NONCE_SALT',       '9+)0F:$}U-t66X*U9A6C%W{U?2|)a Gtx*V:`MF1)2L|)!0X!+8Pxu{@v+pXHzp,');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique. 
 * N'utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés!
 */
$table_prefix  = 'wp_';

/** 
 * Pour les développeurs : le mode deboguage de WordPress.
 * 
 * En passant la valeur suivante à "true", vous activez l'affichage des
 * notifications d'erreurs pendant votre essais.
 * Il est fortemment recommandé que les développeurs d'extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de 
 * développement.
 */ 
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', false);
define('WP_DEBUG_DISPLAY', false);
define('DISALLOW_FILE_EDIT',true);
define('DISALLOW_FILE_MODS',true);

/* C'est tout, ne touchez pas à ce qui suit ! Bon blogging ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');define('WP_CRON_LOCK_TIMEOUT', 300);
