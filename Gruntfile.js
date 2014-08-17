module.exports = function(grunt) {

	/* Load all tasks */
	require('load-grunt-tasks')(grunt);

	/* Project Configuration */
	grunt.initConfig({

		/* === Read package.json === */
		pkg: grunt.file.readJSON('package.json'),

		/* === grunt-wp-i18n : Create POT Language File === */
		makepot: {
			theme: {
				options: {
					domainPath: '/languages/',
					include: ['includes/.*'],
					potFilename: '<%= pkg.name %>.pot',
					type: 'wp-theme',
					updateTimestamp: true,
					processPot: function( pot, options ) {
						pot.headers['report-msgid-bugs-to'] = 'http://genbu.me/contact/';
						pot.headers['plural-forms'] = 'nplurals=2; plural=n != 1;';
						pot.headers['language-team'] = 'David Chandra <david@shellcreeper.com>\n';
						pot.headers['x-poedit-basepath'] = '../includes\n';
						pot.headers['x-poedit-language'] = 'English\n';
						pot.headers['x-poedit-country'] = 'UNITED STATES\n';
						pot.headers['x-poedit-sourcecharset'] = 'utf-8\n';
						pot.headers['x-poedit-searchpath-0'] = '.\n';
						pot.headers['x-poedit-keywordslist'] = '__;_e;esc_attr_e;esc_attr__;esc_html_e;esc_html__;__ngettext:1,2;_n:1,2;__ngettext_noop:1,2;_n_noop:1,2;_c;_nc:4c,1,2;_x:1,2c;_ex:1,2c;_nx:4c,1,2;_nx_noop:4c,1,2;\n';
						pot.headers['x-textdomain-support'] = 'yes\n';
						return pot;
					}
				}
			},
			library: {
				options: {
					domainPath: '/languages/',
					include: ['library/.*'],
					potFilename: 'hybrid-core.pot',
					type: 'wp-theme',
					updateTimestamp: true,
					processPot: function( pot, options ) {
						pot.headers['project-id-version'] = 'Hybrid Core 2';
						pot.headers['report-msgid-bugs-to'] = 'http://themehybrid.com/contact/';
						pot.headers['plural-forms'] = 'nplurals=2; plural=n != 1;';
						pot.headers['language-team'] = 'Justin Tadlock <justin@justintadlock.com>\n';
						pot.headers['x-poedit-basepath'] = '../library\n';
						pot.headers['x-poedit-language'] = 'English\n';
						pot.headers['x-poedit-country'] = 'UNITED STATES\n';
						pot.headers['x-poedit-sourcecharset'] = 'utf-8\n';
						pot.headers['x-poedit-searchpath-0'] = '.\n';
						pot.headers['x-poedit-keywordslist'] = '__;_e;esc_attr_e;esc_attr__;esc_html_e;esc_html__;__ngettext:1,2;_n:1,2;__ngettext_noop:1,2;_n_noop:1,2;_c;_nc:4c,1,2;_x:1,2c;_ex:1,2c;_nx:4c,1,2;_nx_noop:4c,1,2;\n';
						pot.headers['x-textdomain-support'] = 'yes\n';
						return pot;
					}
				}
			},
		}, // end grunt-wp-i18n

		/* === grunt-contrib-uglify : Minify JavaScripts === */
		uglify: {
			alljs: {
				files: {
					'js/fitvids.min.js': ['js/fitvids.js'],
					'js/flexslider.min.js': ['js/flexslider.js'],
					'js/html5shiv.min.js': ['js/html5shiv.js'],
					'js/imagesloaded.min.js': ['js/imagesloaded.js'],
					'js/respond.min.js': ['js/respond.js'],
					'js/theme.min.js': ['js/theme.js'],
					'js/webfontloader.min.js': ['js/webfontloader.js'],
				}
			},
		}, // end grunt-contrib-uglify

		/* === grunt-contrib-cssmin : Minify CSS === */
		cssmin : {
			css: {
				expand: true,
				cwd: 'css/',
				dest: 'css/',
				src: ['*.css', '!*.min.css', '!debug-media-queries.css'],
				ext: '.min.css'
			},
			style: {
				src: 'style.css',
				dest: 'style.min.css'
			},
			mediaqueries: {
				src: 'media-queries.css',
				dest: 'media-queries.min.css'
			},
			theme: {
				files: {
					'theme.css': ['css/reset.css', 'css/menus.css', 'style.css', 'media-queries.css' ]
				}
			},
			editor: {
				files: {
					'editor-style.css': ['css/reset.css', 'style.css' ]
				}
			}
		}, // end grunt-contrib-cssmin


		/* ===== SAVE THEME AS ZIP FILE IN "_ZIP" FOLDER ===== */

		/* === grunt-contrib-clean : Delete everything in "_zip/{themename}" folder === */
		clean: {
			main: ['_zip/<%= pkg.name %>']
		}, // end grunt-contrib-clean

		/* === grunt-contrib-copy : Copy all theme files in "_zip/{themename}" folder === */
		copy: {
			main: {
				src: [
					'**',
					'!node_modules/**',
					'!_zip/**',
					'!.git/**',
					'!.svn/**',
					'!*.bat',
					'!Gruntfile.js',
					'!package.json',
					'!.gitignore',
					'!.gitmodules',
					'!**/Gruntfile.js',
					'!**/package.json',
					'!**/*~'
				],
			dest: '_zip/<%= pkg.name %>/'
		  }
		}, // end grunt-contrib-copy


		/* === grunt-contrib-compress : make zip file of "_zip/{themename}" folder to "_zip/{themename}.{version}.zip" === */
		compress: {
			main: {
				expand: true,
				cwd: '_zip/<%= pkg.name %>/',
				src: ['**/*'],
				dest: '<%= pkg.name %>/',
				options: {
					mode: 'zip',
					archive: './_zip/<%= pkg.name %>.<%= pkg.version %>.zip'
				},
			}
		}, // end grunt-contrib-compress

	}); //end Project Configuration

	/* Register Default Task: Do All */
	grunt.registerTask( 'default', [ 'uglify', 'cssmin', 'clean', 'copy', 'compress' ] );

	/* Register Minification Task */
	grunt.registerTask( 'minify', [ 'uglify', 'cssmin' ] );

	/* Register ZIP Build Task */
	grunt.registerTask( 'zip', [ 'clean', 'copy', 'compress' ] );

	/* Register Clone Build Task */
	grunt.registerTask( 'cleancopy', [ 'clean', 'copy' ] );

};