/*!
 * Twitter Account Box WP-plugin Gruntfile
 * http://tab.jannejuhani.net
 * @author Janne Saarela
 */

'use strict';

module.exports = function(grunt) {

  require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    /**
     * Set project info
     * theme_name is related to wp-theme name
     * template is related to wp-template (if you are developing a child theme to wp)
     */
    project: {
      src: 'src',
      app: '',
      css: [
        '<%= project.src %>/master.styl'
      ],
      js: [
        'src/*.js'
      ],
      styles: {
        description: "Styles for Twitter Account Box WP-plugin"
      }
    },

    /**
     * Project banner
     * Dynamically appended banner to JS files and css to CSS files
     * Inherits text from package.json and project-info
     */
    tag: {
      banner: '/*!\n' +
        ' * <%= pkg.name %>\n' +
        ' * <%= pkg.url %>\n' +
        ' * @author <%= pkg.author %>\n' +
        ' * Copyright <%= pkg.copyright %>. <%= pkg.license %> licensed.\n' +
        ' */\n',
      css: '/*\n' +
        ' * Description: <%= project.styles.description %>\n' +
        ' * Plugin site: <%= pkg.url %>\n' +
        ' * Author: <%= pkg.author %>\n' +
        ' * Copyright <%= pkg.copyright %>. <%= pkg.license %> licensed.' +
        ' */\n\n'
    },
    /**
     * Concatenate JavaScript files
     * https://github.com/gruntjs/grunt-contrib-concat
     * Imports all .js files and appends project banner
     */
    concat: {
      dev: {
        files: {
          'public/js/twitter-account-box.min.js': '<%= project.js %>'
        }
      },
      options: {
        stripBanners: true,
        nonull: true,
        banner: '<%= tag.banner %>'
      }
    },
    /**
     * Uglify (minify) JavaScript files
     * https://github.com/gruntjs/grunt-contrib-uglify
     * Compresses and minifies all JavaScript files into one
     */
    uglify: {
      options: {
        banner: '<%= tag.banner %>'
      },
      build: {
        files: {
          'public/js/twitter-account-box.min.js': '<%= project.src %>/twitter-account-box.js'
        }
      }
    },
    stylus: {
      compile_dev: {
        options: {
          paths: ['.'],
          compress: false,
          import: ['nib'],
          urlfunc: 'embedurl', // use embedurl('test.png') in our code to trigger Data URI embedding
          banner: '<%= tag.css %>'
        },
        files: {
          'public/styles/twitteraccountbox.css': '<%= project.src %>/master.styl'
        }
      },
      compile: {
        options: {
          paths: ['.'],
          import: ['nib'],
          urlfunc: 'embedurl', // use embedurl('test.png') in our code to trigger Data URI embedding
          banner: '<%= tag.css %>'
        },
        files: {
          'public/styles/twitteraccountbox.css': '<%= project.src %>/master.styl'
        }
      }
    },
    /**
     * Runs tasks against changed watched files
     * https://github.com/gruntjs/grunt-contrib-watch
     * Watching development files and run concat/compile tasks
     */
    watch: {
      concat: {
        files: '<%= project.src %>/{,*/}*.js',
        tasks: ['concat:dev']
      },
      stylus: {
        files: '<%= project.src %>/master.styl',
        tasks: ['stylus:compile_dev']
      }
    }
  });

  /**
   * Default task
   * Run `grunt` on the command line
   */
  grunt.registerTask('default', [
    'stylus:compile_dev',
    'concat:dev',
    'watch'
  ]);

  /**
   * Build task
   * Run `grunt build` on the command line
   * Then compress all JS/CSS files
   */
  grunt.registerTask('build', [
    'stylus:compile',
    'uglify'
  ]);

};