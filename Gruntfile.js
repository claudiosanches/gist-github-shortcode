module.exports = function(grunt) {
"use strict";

    grunt.initConfig({

        // gets the package vars
        pkg: grunt.file.readJSON("package.json"),
        svn_settings: {
            path: "../../../../wp_plugins/<%= pkg.name %>",
            tag: "<%= svn_settings.path %>/tags/<%= pkg.version %>",
            trunk: "<%= svn_settings.path %>/trunk",
            exclude: [
                ".editorconfig",
                ".git/",
                ".gitignore",
                "node_modules/",
                "Gruntfile.js",
                "README.md",
                "package.json",
                "*.zip"
            ]
        },

        // rsync commands used to take the files to svn repository
        rsync: {
            tag: {
                src: "./",
                dest: "<%= svn_settings.tag %>",
                recursive: true,
                exclude: "<%= svn_settings.exclude %>"
            },
            trunk: {
                src: "./",
                dest: "<%= svn_settings.trunk %>",
                recursive: true,
                exclude: "<%= svn_settings.exclude %>"
            }
        },

        // shell command to commit the new version of the plugin
        shell: {
            svn_add: {
                command: 'svn add --force * --auto-props --parents --depth infinity -q',
                options: {
                    stdout: true,
                    stderr: true,
                    execOptions: {
                        cwd: "<%= svn_settings.path %>"
                    }
                }
            },
            svn_commit: {
                command: "svn commit -m 'updated the plugin version to <%= pkg.version %>'",
                options: {
                    stdout: true,
                    stderr: true,
                    execOptions: {
                        cwd: "<%= svn_settings.path %>"
                    }
                }
            }
        },

        // creates a zip of the plugin
        zipdir: {
            "gist-github-shortcode": {
                src: ["./"],
                dest: "./<%= pkg.name %>.zip",
                exclude: "<%= svn_settings.exclude %>"
            }
        }

    });

    // load tasks
    grunt.loadNpmTasks("grunt-rsync");
    grunt.loadNpmTasks("grunt-shell");
    grunt.loadNpmTasks("grunt-wx-zipdir");

    // deploy task
    grunt.registerTask("deploy", [
        "rsync:tag",
        "rsync:trunk",
        "shell:svn_add",
        "shell:svn_commit"
    ]);

    // zip task
    grunt.registerTask("zip", [
        "default",
        "zipdir"
    ]);
};
