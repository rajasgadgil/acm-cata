/**
 * Required
 * - install grunt
 *      sudo npm install -g grunt-cli
 * - install node-wp-i18n
 *      sudo npm install -g node-wp-i18n
 */

const potInfo = {
	potFilename: 'yith-woocommerce-badges-management.pot',
	potHeaders : {
		poedit                 : true, // Includes common Poedit headers.
		'x-poedit-keywordslist': true, // Include a list of all possible gettext functions.
		'report-msgid-bugs-to' : 'YITH <plugins@yithemes.com>',
		'language-team'        : 'YITH <info@yithemes.com>'
	}
};

module.exports = function ( grunt ) {
	'use strict';

	grunt.initConfig( {
						  dirs: {
							  css: 'assets/css',
							  js : 'assets/js'
						  },

						  uglify: {
							  options: {
								  ie8   : true,
								  parse : {
									  strict: false
								  },
								  output: {
									  comments: /@license|@preserve|^!/
								  }
							  },
							  common : {
								  files: [{
									  expand: true,
									  cwd   : '<%= dirs.js %>/',
									  src   : [
										  '*.js',
										  '!*.min.js'
									  ],
									  dest  : '<%= dirs.js %>/',
									  ext   : '.min.js'
								  }]
							  }
						  },

						  jshint : {
							  options: {
								  jshintrc: '.jshintrc'
							  },
							  all    : [
								  '<%= dirs.js %>/**/*.js',
								  '!<%= dirs.js %>/**/*.min.js'
							  ]
						  },
						  makepot: {
							  options: {
								  type         : 'wp-plugin',
								  domainPath   : 'languages',
								  potHeaders   : potInfo.potHeaders,
								  updatePoFiles: true,
								  processPot   : function ( pot ) {
									  // Exclude plugin meta
									  var translation,
										  excluded_meta = [
											  'Plugin Name of the plugin/theme',
											  'Plugin URI of the plugin/theme',
											  'Author of the plugin/theme',
											  'Author URI of the plugin/theme'
										  ];

									  for ( translation in pot.translations[ '' ] ) {
										  if ( 'undefined' !== typeof pot.translations[ '' ][ translation ].comments.extracted ) {
											  if ( excluded_meta.indexOf( pot.translations[ '' ][ translation ].comments.extracted ) >= 0 ) {
												  console.log( 'Excluded meta: ' + pot.translations[ '' ][ translation ].comments.extracted );
												  delete pot.translations[ '' ][ translation ];
											  }
										  }
									  }

									  return pot;
								  }
							  },
							  dist   : {
								  options: {
									  potFilename: potInfo.potFilename,
									  exclude    : [
										  'plugin-fw/.*',
										  'mode_modules/.*',
										  'tmp/.*'
									  ]
								  }
							  }
						  },

						  // PHP Code Sniffer.
						  phpcs: {
							  options: {
								  bin     : 'vendor/bin/phpcs',
								  standard: 'WordPress'
							  },
							  dist   : {
								  src: [
									  '**/*.php', // Include all php files.
									  '!node_modules/**',
									  '!tmp/**',
									  '!plugin-fw/**',
									  '!plugin-upgrade/**',
									  '!vendor/**'
								  ]
							  }
						  }

					  } );

	// Load NPM tasks to be used here.
	grunt.loadNpmTasks( 'grunt-wp-i18n' );
	grunt.loadNpmTasks( 'grunt-contrib-uglify' );
	grunt.loadNpmTasks( 'grunt-contrib-jshint' );
	grunt.loadNpmTasks( 'grunt-shell' );
	grunt.loadNpmTasks( 'grunt-phpcs' );

	// Register tasks.
	grunt.registerTask( 'default', [
		'js',
		'i18n'
	] );

	grunt.registerTask( 'js', ['uglify'] );
	grunt.registerTask( 'i18n', ['makepot'] );
};
