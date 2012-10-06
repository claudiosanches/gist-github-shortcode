(function() {
    tinymce.create('tinymce.plugins.dfwGist', {
        init : function(ed, url) {
            ed.addCommand('gistCommand', function() {
                ed.windowManager.open({
                    file : url + '/dialog.html',
                    width : 150 + parseInt(ed.getLang('gist.delta_width', 0)),
                    height : 150 + parseInt(ed.getLang('gist.delta_height', 0)),
                    inline : 1
                }, {
                    plugin_url : url
                });
            });
            ed.addButton('gist', {
                title : 'Gist',
                image : url + '/github.png',
                cmd : 'gistCommand'
            });
        },
        getInfo : function() {
            return {
                longname : "Gist Shortcode",
                author : 'Claudio Sanches',
                authorurl : 'http://claudiosmweb.com/',
                infourl : 'http://claudiosmweb.com/',
                version : "1.0"
            };
        }
    });
    tinymce.PluginManager.add('gist', tinymce.plugins.dfwGist);
})();
