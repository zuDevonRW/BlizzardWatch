// JavaScript Document
(function() {
    tinymce.create('tinymce.plugins.bw_question_display', {
        init : function(ed, url) {
            var newurl = url.substring(0, url.length -3);
            ed.addButton('bw_question_display', {
                title : 'Insert Blizzard Watch Question',
                image : '/wp-content/themes/blizzardwatch/q.png',
                onclick : function() {
                    ed.selection.setContent('[question][from][/from][body][/body][/question]');
                }
            });

        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('bw_question_display', tinymce.plugins.bw_question_display);
})();