module.exports = function(grunt) {
	'use strict';

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		concat: {
			options: {
				separator: ';',
			},
			dist: {
				src: [
					'webroot/js/src/dropdown.js',
					'webroot/js/src/tooltip.js',
					'webroot/js/src/script.js',
				],
				dest: 'webroot/js/build/scripts.js'
			}
		},
		uglify: {
			options: {
				mangle: false
			},
			my_target: {
				files: {
					'webroot/js/build/scripts.min.js': ['webroot/js/build/scripts.js']
				}
			}
		},
		less:	{
			my_target: {
				files: {
					'webroot/css/styles.css': 'webroot/css/styles.less'
				}
			}
		},
                watch: {
			jss: {
			    files: ['webroot/js/src/*.js'],
			    tasks: ['concat', 'uglify']
			},
			css: {
				files: ['webroot/css/styles.less'],
				tasks: ['less']
			}
                },
	});

	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-uglify');
        grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-less');

	grunt.registerTask('default', ['concat:dist', 'uglify']);
};