// Load Grunt
module.exports = function (grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    // Tasks
    sass: { // Begin Sass Plugin
      dist: {
        options: {
          sourcemap: 'none'
        },
        files: [{
          expand: true,
          cwd: 'sass',
          src: ['**/*.sass'],
          dest: 'assets',
          ext: '.css'
      }]
      }
    },
    uglify: {
    	my_target: {
      		files: [{
        		expand: true,
        		cwd: 'js',
        		src: '**/*.js',
        		dest: 'assets'
      		}]
    	}
    },
    watch: { // Compile everything into one task with Watch Plugin
      css: {
        files: '**/*.sass',
        tasks: ['sass']
      },
      js: {
        files: ['js/*.js', 'js/components/*.js'],
        tasks: ['uglify'],
      }
    }
  });
  // Load Grunt plugins
  grunt.loadNpmTasks('grunt-contrib-sass');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-contrib-watch');

  // Register Grunt tasks
  grunt.registerTask('default', ['watch']);
};
