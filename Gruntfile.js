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
  terser: {
    your_target: {
      files: [{
        expand: true,
        cwd: 'js',
        src: '**/*.js',
        dest: 'assets'
      }]
    }
  },
  // Tasks
  watch: { // Compile everything into one task with Watch Plugin
    css: {
      files: [ 'css/*.css' ],
      tasks: [ 'cssmin' ],
    },
    js: {
      files: ['js/*.js', 'js/components/*.js'],
      tasks: ['terser'],
    }
  }
});
// Load Grunt plugins
grunt.loadNpmTasks('grunt-contrib-watch');
grunt.loadNpmTasks('grunt-contrib-cssmin');
grunt.loadNpmTasks('grunt-terser');

// Register Grunt tasks
grunt.registerTask('default', ['watch']);
grunt.registerTask('build', ['terser', 'cssmin']);
