require([
    'jquery',
    'codemirror/lib/codemirror',
    'codemirror/mode/htmlmixed/htmlmixed',
    'codemirror/addon/mode/overlay'
], function($, CodeMirror){

    var editor;

    CodeMirror.defineMode("mustache", function(config, parserConfig) {
      var mustacheOverlay = {
        token: function(stream, state) {
          var ch;
          if (stream.match("{{")) {
            while ((ch = stream.next()) != null)
              if (ch == "}" && stream.next() == "}") {
                stream.eat("}");
                return "mustache";
              }
          }
          while (stream.next() != null && !stream.match("{{", false)) {}
          return null;
        }
      };
      return CodeMirror.overlayMode(CodeMirror.getMode(config, parserConfig.backdrop || "xml"), mustacheOverlay);
    });

    function renderCodeMirror(){
        editor = CodeMirror.fromTextArea($('textarea[name="feed_template[code]"]').get(0), {
            lineNumbers: true,
            mode: "mustache",
            theme: "dracula"
        });
        editor.on('change', function(){
            $('textarea[name="feed_template[code]"]').val(editor.getValue()).trigger('change');
        })
        editor.setSize(null, 500);
    }

    // wait for textarea render
    var waitForTextarea = setInterval(function(){
        if($('textarea[name="feed_template[code]"]').length){
            clearInterval(waitForTextarea);
            renderCodeMirror();
        }
    }, 10);
})
