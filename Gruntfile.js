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
					'webroot/js/src/bootstrap-dropdown.js',
					'webroot/js/src/bootstrap-tooltip.js',
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
                watch: {
                    jss: {
                        files: ['webroot/js/src/*.js'],
                        tasks: ['concat', 'uglify']
                    }
                }
	});

	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-uglify');
        grunt.loadNpmTasks('grunt-contrib-watch');

	grunt.registerTask('default', ['concat:dist', 'uglify']);
};