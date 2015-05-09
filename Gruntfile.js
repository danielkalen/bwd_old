module.exports = function(grunt) {

// 1. All configuration goes here 
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

exec: {
      get_grunt_sitemap: {
        command: 'curl --silent --output sitemap.json http://shopperbarn.dev:8888/?show_sitemap'
      }
    },

uncss: {
      dist: {
        options: {
          ignore       : ['.hidden-xs'],
          stylesheets  : ['wp-content/themes/dt-the7/js/plugins/validator/validationEngine.jquery.css'],
          ignoreSheets : [/fonts.googleapis/],
          urls         : [], //Overwritten in load_sitemap_and_uncss task
        },
        files: {
          'wp-content/themes/dt-the7/js/plugins/validator/validationEngine.clean.css': ['**/*.php']
        }
      }
    }

  });

// 3. Where we tell Grunt we plan to use this plug-in.
     grunt.loadNpmTasks('grunt-exec');   
     grunt.loadNpmTasks('grunt-uncss');

// 4. Where we tell Grunt what to do when we type "grunt" into the terminal.
     grunt.registerTask('load_sitemap_json', function() {
     var sitemap_urls = grunt.file.readJSON('./sitemap.json');
     grunt.config.set('uncss.dist.options.urls', sitemap_urls);
  });

grunt.registerTask('deploy', ['exec:get_grunt_sitemap','load_sitemap_json','uncss:dist']);  
};