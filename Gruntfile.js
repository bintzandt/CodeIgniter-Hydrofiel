const grunt = require( 'grunt' );

require( 'load-grunt-tasks' )(grunt);

// Load Grunt
grunt.initConfig({
  pkg: grunt.file.readJSON('package.json'),
  cssmin: {
    sitecss: {
      files: {
        'assets/hydrofiel.css': [
          'css/hydrofiel.css',
        ],
      }
    }
  },
  // Tasks
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
      files: [ 'css/*.css' ],
      tasks: [ 'cssmin' ],
    },
    js: {
      files: ['js/*.js', 'js/components/*.js'],
      tasks: ['uglify'],
    }
  }
});
// Load Grunt plugins
grunt.loadNpmTasks('grunt-contrib-uglify');
grunt.loadNpmTasks('grunt-contrib-watch');
grunt.loadNpmTasks('grunt-contrib-cssmin');

// Register Grunt tasks
grunt.registerTask('default', ['watch']);
grunt.registerTask('build', ['uglify', 'cssmin']);
