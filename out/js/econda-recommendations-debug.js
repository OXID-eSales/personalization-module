(function() {
  var rsplit = function(string, regex) {
    var result = regex.exec(string), retArr = new Array, first_idx, last_idx, first_bit;
    while(result != null) {
      first_idx = result.index;
      last_idx = regex.lastIndex;
      if(first_idx != 0) {
        first_bit = string.substring(0, first_idx);
        retArr.push(string.substring(0, first_idx));
        string = string.slice(first_idx)
      }
      retArr.push(result[0]);
      string = string.slice(result[0].length);
      result = regex.exec(string)
    }
    if(!string == "") {
      retArr.push(string)
    }
    return retArr
  }, chop = function(string) {
    return string.substr(0, string.length - 1)
  }, extend = function(d, s) {
    for(var n in s) {
      if(s.hasOwnProperty(n)) {
        d[n] = s[n]
      }
    }
  };
  EJS = function(options) {
    options = typeof options == "string" ? {view:options} : options;
    this.set_options(options);
    if(options.precompiled) {
      this.template = {};
      this.template.process = options.precompiled;
      EJS.update(this.name, this);
      return
    }
    if(options.element) {
      if(typeof options.element == "string") {
        var name = options.element;
        options.element = document.getElementById(options.element);
        if(options.element == null) {
          throw name + "does not exist!";
        }
      }
      if(options.element.value) {
        this.text = options.element.value
      }else {
        this.text = options.element.innerHTML
      }
      this.name = options.element.id;
      this.type = "["
    }else {
      if(options.url) {
        options.url = EJS.endExt(options.url, this.extMatch);
        this.name = this.name ? this.name : options.url;
        var url = options.url;
        var template = EJS.get(this.name, this.cache);
        if(template) {
          return template
        }
        if(template == EJS.INVALID_PATH) {
          return null
        }
        try {
          this.text = EJS.request(url + (this.cache ? "" : "?" + Math.random()))
        }catch(e) {
        }
        if(this.text == null) {
          throw{type:"EJS", message:"There is no template at " + url};
        }
      }
    }
    var template = new EJS.Compiler(this.text, this.type);
    template.compile(options, this.name);
    EJS.update(this.name, this);
    this.template = template
  };
  EJS.prototype = {render:function(object, extra_helpers) {
    object = object || {};
    this._extra_helpers = extra_helpers;
    var v = new EJS.Helpers(object, extra_helpers || {});
    return this.template.process.call(object, object, v)
  }, update:function(element, options) {
    if(typeof element == "string") {
      element = document.getElementById(element)
    }
    if(options == null) {
      _template = this;
      return function(object) {
        EJS.prototype.update.call(_template, element, object)
      }
    }
    if(typeof options == "string") {
      params = {};
      params.url = options;
      _template = this;
      params.onComplete = function(request) {
        var object = eval(request.responseText);
        EJS.prototype.update.call(_template, element, object)
      };
      EJS.ajax_request(params)
    }else {
      element.innerHTML = this.render(options)
    }
  }, out:function() {
    return this.template.out
  }, set_options:function(options) {
    this.type = options.type || EJS.type;
    this.cache = options.cache != null ? options.cache : EJS.cache;
    this.text = options.text || null;
    this.name = options.name || null;
    this.ext = options.ext || EJS.ext;
    this.extMatch = new RegExp(this.ext.replace(/\./, "."))
  }};
  EJS.endExt = function(path, match) {
    if(!path) {
      return null
    }
    match.lastIndex = 0;
    return path + (match.test(path) ? "" : this.ext)
  };
  EJS.Scanner = function(source, left, right) {
    extend(this, {left_delimiter:left + "%", right_delimiter:"%" + right, double_left:left + "%%", double_right:"%%" + right, left_equal:left + "%=", left_comment:left + "%#"});
    this.SplitRegexp = left == "[" ? /(\[%%)|(%%\])|(\[%=)|(\[%#)|(\[%)|(%\]\n)|(%\])|(\n)/ : new RegExp("(" + this.double_left + ")|(%%" + this.double_right + ")|(" + this.left_equal + ")|(" + this.left_comment + ")|(" + this.left_delimiter + ")|(" + this.right_delimiter + "\n)|(" + this.right_delimiter + ")|(\n)");
    this.source = source;
    this.stag = null;
    this.lines = 0
  };
  EJS.Scanner.to_text = function(input) {
    if(input == null || input === undefined) {
      return""
    }
    if(input instanceof Date) {
      return input.toDateString()
    }
    if(input.toString) {
      return input.toString()
    }
    return""
  };
  EJS.Scanner.prototype = {scan:function(block) {
    scanline = this.scanline;
    regex = this.SplitRegexp;
    if(!this.source == "") {
      var source_split = rsplit(this.source, /\n/);
      for(var i = 0;i < source_split.length;i++) {
        var item = source_split[i];
        this.scanline(item, regex, block)
      }
    }
  }, scanline:function(line, regex, block) {
    this.lines++;
    var line_split = rsplit(line, regex);
    for(var i = 0;i < line_split.length;i++) {
      var token = line_split[i];
      if(token != null) {
        try {
          block(token, this)
        }catch(e) {
          throw{type:"EJS.Scanner", line:this.lines};
        }
      }
    }
  }};
  EJS.Buffer = function(pre_cmd, post_cmd) {
    this.line = new Array;
    this.script = "";
    this.pre_cmd = pre_cmd;
    this.post_cmd = post_cmd;
    for(var i = 0;i < this.pre_cmd.length;i++) {
      this.push(pre_cmd[i])
    }
  };
  EJS.Buffer.prototype = {push:function(cmd) {
    this.line.push(cmd)
  }, cr:function() {
    this.script = this.script + this.line.join("; ");
    this.line = new Array;
    this.script = this.script + "\n"
  }, close:function() {
    if(this.line.length > 0) {
      for(var i = 0;i < this.post_cmd.length;i++) {
        this.push(pre_cmd[i])
      }
      this.script = this.script + this.line.join("; ");
      line = null
    }
  }};
  EJS.Compiler = function(source, left) {
    this.pre_cmd = ["var ___ViewO = [];"];
    this.post_cmd = new Array;
    this.source = " ";
    if(source != null) {
      if(typeof source == "string") {
        source = source.replace(/\r\n/g, "\n");
        source = source.replace(/\r/g, "\n");
        this.source = source
      }else {
        if(source.innerHTML) {
          this.source = source.innerHTML
        }
      }
      if(typeof this.source != "string") {
        this.source = ""
      }
    }
    left = left || "<";
    var right = ">";
    switch(left) {
      case "[":
        right = "]";
        break;
      case "<":
        break;
      default:
        throw left + " is not a supported deliminator";break
    }
    this.scanner = new EJS.Scanner(this.source, left, right);
    this.out = ""
  };
  EJS.Compiler.prototype = {compile:function(options, name) {
    options = options || {};
    this.out = "";
    var put_cmd = "___ViewO.push(";
    var insert_cmd = put_cmd;
    var buff = new EJS.Buffer(this.pre_cmd, this.post_cmd);
    var content = "";
    var clean = function(content) {
      content = content.replace(/\\/g, "\\\\");
      content = content.replace(/\n/g, "\\n");
      content = content.replace(/"/g, '\\"');
      return content
    };
    this.scanner.scan(function(token, scanner) {
      if(scanner.stag == null) {
        switch(token) {
          case "\n":
            content = content + "\n";
            buff.push(put_cmd + '"' + clean(content) + '");');
            buff.cr();
            content = "";
            break;
          case scanner.left_delimiter:
          ;
          case scanner.left_equal:
          ;
          case scanner.left_comment:
            scanner.stag = token;
            if(content.length > 0) {
              buff.push(put_cmd + '"' + clean(content) + '")')
            }
            content = "";
            break;
          case scanner.double_left:
            content = content + scanner.left_delimiter;
            break;
          default:
            content = content + token;
            break
        }
      }else {
        switch(token) {
          case scanner.right_delimiter:
            switch(scanner.stag) {
              case scanner.left_delimiter:
                if(content[content.length - 1] == "\n") {
                  content = chop(content);
                  buff.push(content);
                  buff.cr()
                }else {
                  buff.push(content)
                }
                break;
              case scanner.left_equal:
                buff.push(insert_cmd + "(EJS.Scanner.to_text(" + content + ")))");
                break
            }
            scanner.stag = null;
            content = "";
            break;
          case scanner.double_right:
            content = content + scanner.right_delimiter;
            break;
          default:
            content = content + token;
            break
        }
      }
    });
    if(content.length > 0) {
      buff.push(put_cmd + '"' + clean(content) + '")')
    }
    buff.close();
    this.out = buff.script + ";";
    var to_be_evaled = "/*" + name + "*/this.process = function(_CONTEXT,_VIEW) { try { with(_VIEW) { with (_CONTEXT) {" + this.out + " return ___ViewO.join('');}}}catch(e){e.lineNumber=null;throw e;}};";
    try {
      eval(to_be_evaled)
    }catch(e) {
      if(typeof JSLINT != "undefined") {
        JSLINT(this.out);
        for(var i = 0;i < JSLINT.errors.length;i++) {
          var error = JSLINT.errors[i];
          if(error.reason != "Unnecessary semicolon.") {
            error.line++;
            var err = new Error;
            err.lineNumber = error.line;
            err.message = error.reason;
            if(options.view) {
              err.fileName = options.view
            }
            throw err;
          }
        }
      }else {
        throw e;
      }
    }
  }};
  EJS.config = function(options) {
    EJS.cache = options.cache != null ? options.cache : EJS.cache;
    EJS.type = options.type != null ? options.type : EJS.type;
    EJS.ext = options.ext != null ? options.ext : EJS.ext;
    var templates_directory = EJS.templates_directory || {};
    EJS.templates_directory = templates_directory;
    EJS.get = function(path, cache) {
      if(cache == false) {
        return null
      }
      if(templates_directory[path]) {
        return templates_directory[path]
      }
      return null
    };
    EJS.update = function(path, template) {
      if(path == null) {
        return
      }
      templates_directory[path] = template
    };
    EJS.INVALID_PATH = -1
  };
  EJS.config({cache:true, type:"<", ext:".htm"});
  EJS.Helpers = function(data, extras) {
    this._data = data;
    this._extras = extras;
    extend(this, extras)
  };
  EJS.Helpers.prototype = {view:function(options, data, helpers) {
    if(!helpers) {
      helpers = this._extras
    }
    if(!data) {
      data = this._data
    }
    return(new EJS(options)).render(data, helpers)
  }, to_text:function(input, null_text) {
    if(input == null || input === undefined) {
      return null_text || ""
    }
    if(input instanceof Date) {
      return input.toDateString()
    }
    if(input.toString) {
      return input.toString().replace(/\n/g, "<br />").replace(/''/g, "'")
    }
    return""
  }};
  EJS.newRequest = function() {
    var factories = [function() {
      return new ActiveXObject("Msxml2.XMLHTTP")
    }, function() {
      return new XMLHttpRequest
    }, function() {
      return new ActiveXObject("Microsoft.XMLHTTP")
    }];
    for(var i = 0;i < factories.length;i++) {
      try {
        var request = factories[i]();
        if(request != null) {
          return request
        }
      }catch(e) {
        continue
      }
    }
  };
  EJS.request = function(path) {
    var request = new EJS.newRequest;
    request.open("GET", path, false);
    try {
      request.send(null)
    }catch(e) {
      return null
    }
    if(request.status == 404 || request.status == 2 || request.status == 0 && request.responseText == "") {
      return null
    }
    return request.responseText
  };
  EJS.ajax_request = function(params) {
    params.method = params.method ? params.method : "GET";
    var request = new EJS.newRequest;
    request.onreadystatechange = function() {
      if(request.readyState == 4) {
        if(request.status == 200) {
          params.onComplete(request)
        }else {
          params.onComplete(request)
        }
      }
    };
    request.open(params.method, params.url);
    request.send(null)
  }
})();
var __extends = this && this.__extends || function() {
  var extendStatics = Object.setPrototypeOf || {__proto__:[]} instanceof Array && function(d, b) {
    d.__proto__ = b
  } || function(d, b) {
    for(var p in b) {
      if(b.hasOwnProperty(p)) {
        d[p] = b[p]
      }
    }
  };
  return function(d, b) {
    extendStatics(d, b);
    function __() {
      this.constructor = d
    }
    d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __)
  }
}();
var econda;
(function(econda) {
  var recengine;
  (function(recengine) {
    var Version = function() {
      function Version() {
      }
      Version.currentVersion = function() {
        return"3.0.10"
      };
      return Version
    }();
    recengine.Version = Version
  })(recengine = econda.recengine || (econda.recengine = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var util;
  (function(util) {
    var StringUtils = function() {
      function StringUtils() {
      }
      StringUtils.trim = function(text) {
        if(typeof String.prototype.trim !== "function") {
          this.trim = function(text) {
            return String(text).replace(/^\s+|\s+$/g, "")
          }
        }else {
          this.trim = function(text) {
            return String(text).trim()
          }
        }
        return this.trim(text)
      };
      StringUtils.html = function(text) {
        var me = this;
        return String(text).replace(/[&<>"'\/]/g, function(s) {
          return me._entityMap[s]
        })
      };
      StringUtils.truncate = function(text, options) {
        if(options === void 0) {
          options = null
        }
        options = options ? options : {};
        var maxLength = options.maxLength || 100;
        var wordBoundaries = options.wordBoundaries == true;
        var ellipsis = options.ellipsis || " ...";
        var minLength = options.minLength || 1;
        var maxTextLength = maxLength - ellipsis.length;
        var ret;
        if(text.length > maxLength) {
          ret = text.substr(0, maxTextLength);
          if(wordBoundaries) {
            var endIndex = ret.lastIndexOf(" ") - 1;
            if(endIndex >= minLength) {
              ret = ret.substr(0, endIndex)
            }
          }
          ret = ret + ellipsis
        }else {
          ret = text
        }
        return ret
      };
      StringUtils.ucFirst = function(text) {
        return text.charAt(0).toUpperCase() + text.slice(1)
      };
      StringUtils._entityMap = {"&":"&amp;", "<":"&lt;", ">":"&gt;", '"':"&quot;", "'":"&#39;", "/":"&#x2F;"};
      return StringUtils
    }();
    util.StringUtils = StringUtils
  })(util = econda.util || (econda.util = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var util;
  (function(util) {
    var DomHelper = function() {
      function DomHelper() {
      }
      DomHelper._useJQuery = function() {
        return this.useJQueryIfLoaded && typeof jQuery != "undefined"
      };
      DomHelper.update = function(element, html) {
        if(this._useJQuery()) {
          return jQuery(element).html(html)
        }else {
          return this._update(element, html)
        }
      };
      DomHelper.createFragmentFromHtml = function(html) {
        var frag = document.createDocumentFragment(), temp = document.createElement("div");
        temp.innerHTML = html;
        while(temp.firstChild) {
          frag.appendChild(temp.firstChild)
        }
        return frag
      };
      DomHelper.appendFromHtml = function(parentNode, html) {
        var temp = document.createElement("div");
        temp.innerHTML = html;
        while(temp.firstChild) {
          if(String(temp.firstChild.nodeName).toLowerCase() === "script") {
            var scriptText = temp.firstChild.textContent || temp.firstChild.innerHTML;
            var scriptNode = document.createElement("script");
            scriptNode.type = "text/javascript";
            if(temp.children[0].getAttribute("src")) {
              scriptNode.src = temp.children[0].getAttribute("src")
            }else {
              try {
                scriptNode.appendChild(document.createTextNode(scriptText))
              }catch(e) {
                scriptNode.text = scriptText
              }
            }
            parentNode.appendChild(scriptNode);
            temp.removeChild(temp.firstChild)
          }else {
            parentNode.appendChild(temp.firstChild)
          }
        }
      };
      DomHelper.empty = function(element) {
        var node = DomHelper.element(element);
        while(node.firstChild) {
          node.removeChild(node.firstChild)
        }
      };
      DomHelper._update = function(element, html) {
        var el = this.element(element);
        var ret = null;
        if(el) {
          el.innerHTML = html;
          this._handleJavascript(el);
          ret = el.innerHTML
        }
        return ret
      };
      DomHelper._handleJavascript = function(element) {
        var i, ii, scripts = element.getElementsByTagName("script");
        if(scripts.length > 0) {
          for(i = 0, ii = scripts.length;i < ii;i += 1) {
            var scriptNode = this._generateScriptNode(scripts[i].text || scripts[i].textContent || scripts[i].innerHTML || "");
            element.appendChild(scriptNode)
          }
        }
      };
      DomHelper._generateScriptNode = function(code) {
        var script = document.createElement("script");
        script.type = "text/javascript";
        script.appendChild(document.createTextNode(code));
        return script
      };
      DomHelper.isDocumentReady = function() {
        return document.readyState === "complete" || document.readyState === "interactive"
      };
      DomHelper.documentReady = function(callback) {
        if(DomHelper.isDocumentReady()) {
          callback();
          return
        }
        var called = false, isFrame = false;
        var doc = document, win = window;
        function ready() {
          if(called) {
            return
          }
          called = true;
          callback()
        }
        if(doc.addEventListener) {
          doc.addEventListener("DOMContentLoaded", ready, false)
        }else {
          if(doc.attachEvent) {
            try {
              isFrame = window.frameElement != null
            }catch(e) {
            }
            if(doc.documentElement && doc.documentElement.doScroll && !isFrame) {
              var tryScroll = function() {
                if(called) {
                  return
                }
                try {
                  doc.documentElement.doScroll("left");
                  ready()
                }catch(e) {
                  setTimeout(tryScroll, 10)
                }
              };
              tryScroll()
            }
            doc.attachEvent("onreadystatechange", function() {
              if(doc.readyState === "complete" || doc.readyState === "interactive") {
                ready()
              }
            })
          }
        }
        if(win.addEventListener) {
          win.addEventListener("load", ready, false)
        }else {
          if(win.attachEvent) {
            win.attachEvent("onload", ready)
          }else {
            var fn = win.onload;
            win.onload = function() {
              fn && fn(null);
              ready()
            }
          }
        }
      };
      DomHelper.remove = function(element) {
        var el = this.element(element);
        if(el) {
          el.parentNode.removeChild(el)
        }
      };
      DomHelper.elements = function(selector) {
        var el = null;
        if(this._useJQuery()) {
          return jQuery(selector)
        }
        if(typeof selector == "string") {
          if(selector.substr(0, 1) == "#") {
            el = document.getElementById(selector.substr(1))
          }else {
            el = document.getElementById(selector)
          }
        }else {
          el = selector
        }
        if(el) {
          return[el]
        }
        return{length:0}
      };
      DomHelper.element = function(element) {
        var el = this.elements(element);
        if(el.length > 0) {
          return el[0]
        }else {
          return null
        }
      };
      DomHelper.useJQueryIfLoaded = true;
      return DomHelper
    }();
    util.DomHelper = DomHelper
  })(util = econda.util || (econda.util = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var util;
  (function(util) {
    var LogItem = function() {
      function LogItem(timestamp, type, message, data) {
        this.timestamp = null;
        this.type = "info";
        this.message = null;
        this.data = null;
        this.timestamp = timestamp;
        this.type = type;
        this.message = message;
        this.data = data
      }
      LogItem.TYPE_INFO = "info";
      LogItem.TYPE_WARNING = "warning";
      LogItem.TYPE_ERROR = "error";
      return LogItem
    }();
    var LogViewer = function() {
      function LogViewer() {
        this.container = null;
        this.autoScroll = true;
        this.queue = [];
        this.timeout = null
      }
      LogViewer.prototype.log = function(message, data) {
        var item = new LogItem(new Date, LogItem.TYPE_INFO, message, data);
        this.queue.push(item);
        this.writeQueue()
      };
      LogViewer.prototype.warn = function(message, data) {
        var item = new LogItem(new Date, LogItem.TYPE_WARNING, message, data);
        this.queue.push(item);
        this.writeQueue()
      };
      LogViewer.prototype.error = function(message, data) {
        var item = new LogItem(new Date, LogItem.TYPE_ERROR, message, data);
        this.queue.push(item);
        this.writeQueue()
      };
      LogViewer.prototype.writeQueue = function() {
        var container = econda.util.DomHelper.element(this.container);
        if(container != null) {
          var item = null;
          while(item = this.queue.shift()) {
            this.writeItemToContainer(item, container)
          }
        }else {
          if(this.timeout == null) {
            var cmp = this;
            setTimeout(function() {
              cmp.writeQueue()
            }, 250)
          }
        }
      };
      LogViewer.prototype.writeItemToContainer = function(item, container) {
        var minutes = item.timestamp.getMinutes();
        var mStr = minutes < 10 ? "0" + minutes.toString() : minutes.toString();
        var html = [item.timestamp.getHours().toString(), ":", mStr, ".", item.timestamp.getSeconds().toString(), " - ", item.message];
        var domItem = document.createElement("p");
        domItem.innerHTML = html.join("");
        domItem.style.margin = "0";
        domItem.style.padding = "2px";
        domItem.style.fontFamily = "Fixed, monospace";
        domItem.style.fontSize = "12px";
        switch(item.type) {
          case LogItem.TYPE_ERROR:
            domItem.style.backgroundColor = "#FF9999";
            break;
          case LogItem.TYPE_WARNING:
            domItem.style.backgroundColor = "#FFFF99";
            break
        }
        container.appendChild(domItem);
        if(this.autoScroll) {
          container.scrollTop = container.scrollHeight
        }
      };
      LogViewer.prototype.getContainerElement = function() {
        var container = null;
        if(this.container instanceof HTMLElement) {
          container = this.container
        }else {
          container = document.getElementById(this.container)
        }
        return container
      };
      return LogViewer
    }();
    util.LogViewer = LogViewer
  })(util = econda.util || (econda.util = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var net;
  (function(net) {
    var Uri = function() {
      function Uri(uri) {
        this.uri = null;
        this.scheme = null;
        this.host = null;
        this.path = null;
        this.query = null;
        this.hash = null;
        if(uri instanceof Uri) {
          return uri
        }else {
          this.uri = uri;
          if(this.uri) {
            this.parseUri()
          }
        }
      }
      Uri.prototype.getScheme = function() {
        if(this.scheme) {
          return this.scheme.toLowerCase()
        }else {
          return null
        }
      };
      Uri.prototype.setScheme = function(scheme) {
        var scheme = (new String(scheme)).toLocaleLowerCase();
        if(this.uri !== null) {
          this.uri = this.uri.replace(/^\w*\:/, scheme + ":")
        }
        this.resetComponents();
        this.parseUri()
      };
      Uri.prototype.getHost = function() {
        return this.host
      };
      Uri.prototype.getPath = function() {
        return this.path
      };
      Uri.prototype.getFilename = function() {
        var path = this.path;
        if(typeof path == "string" && path.lastIndexOf("/") > -1) {
          return path.substr(path.lastIndexOf("/") + 1)
        }else {
          return null
        }
      };
      Uri.prototype.getQuery = function() {
        return this.query
      };
      Uri.prototype.getHash = function() {
        return this.hash
      };
      Uri.prototype.parseUri = function() {
        var uri = this.uri;
        var regex = /^(?:([^:/?#]+):)?(?:\/\/([^/?#]*))?([^?#]*)(?:\?([^#]*))?(?:#(.*))?/;
        var matches = uri.match(regex);
        this.scheme = matches[1] || null;
        this.host = matches[2] || null;
        this.path = matches[3] || null;
        this.query = matches[4] || null;
        this.hash = matches[5] || null;
        return this
      };
      Uri.prototype.resetComponents = function() {
        this.scheme = null;
        this.host = null;
        this.path = null;
        this.query = null;
        this.hash = null;
        return this
      };
      Uri.prototype.getParam = function(name) {
        var parts = [];
        if(typeof this.query === "string") {
          parts = this.query.split("&")
        }
        var params = {};
        for(var n = 0;n < parts.length;n++) {
          var itemParts = String(parts[n]).split("=");
          params[itemParts[0]] = itemParts.length >= 2 ? itemParts[1] : ""
        }
        return params[name] || null
      };
      Uri.prototype.appendParams = function(params) {
        var uri = this.uri;
        var hashpos = uri.lastIndexOf("#"), baseUri = hashpos > -1 ? uri.substring(0, hashpos) : uri, hash = hashpos > -1 ? uri.substr(hashpos) : "", hasParams = uri.indexOf("?") > -1, ret;
        ret = baseUri + (hasParams ? "&" : "?") + Uri.concatParams(params) + hash;
        this.uri = ret;
        this.resetComponents();
        this.parseUri();
        return this
      };
      Uri.prototype.match = function(pattern) {
        return this.uri.match(pattern)
      };
      Uri.prototype.clone = function() {
        var clone = new Uri(this.uri);
        return clone
      };
      Uri.concatParams = function(params) {
        var parts = [];
        for(var name in params) {
          parts.push(name + "=" + encodeURIComponent(params[name]))
        }
        return parts.join("&")
      };
      Uri.detectProtocol = function() {
        return typeof location.protocol === "string" && location.protocol === "https:" ? "https" : "http"
      };
      Uri.prototype.toString = function() {
        return this.uri
      };
      Uri.SCHEME_HTTP = "http";
      Uri.SCHEME_HTTPS = "https";
      Uri.SCHEME_FTP = "ftp";
      return Uri
    }();
    net.Uri = Uri
  })(net = econda.net || (econda.net = {}))
})(econda || (econda = {}));
if(typeof window["econdaConfig"] == "undefined") {
  window["econdaConfig"] = {}
}
var econda;
(function(econda) {
  var debug = function() {
    function debug() {
    }
    debug.setEnabled = function(enabled) {
      econdaConfig.debug = enabled;
      return this
    };
    debug.getEnabled = function() {
      return econdaConfig.debug
    };
    debug.setExceptionsOnError = function(enabled) {
      econdaConfig.exceptionsOnError = enabled;
      return this
    };
    debug.getExceptionsOnError = function() {
      return econdaConfig.exceptionsOnError || false
    };
    debug.setOutputContainer = function(htmlElement) {
      econdaConfig.debugOutputContainer = htmlElement;
      this.logViewerInstance = null;
      return this
    };
    debug.error = function() {
      var args = [];
      for(var _i = 0;_i < arguments.length;_i++) {
        args[_i] = arguments[_i]
      }
      if(econdaConfig.debug != true) {
        return this
      }
      var data = [];
      for(var n = 1;n < arguments.length;n++) {
        data.push(arguments[n])
      }
      if(typeof console != "undefined" && console.error) {
        console.error("[ec] " + arguments[0], data)
      }
      if(econdaConfig.debugOutputContainer != null) {
        this.setupLogViewer();
        this.logViewerInstance.error(arguments[0], data)
      }
      if(econdaConfig.exceptionsOnError) {
        throw new Error(arguments[0]);
      }
      return this
    };
    debug.warn = function() {
      var args = [];
      for(var _i = 0;_i < arguments.length;_i++) {
        args[_i] = arguments[_i]
      }
      if(econdaConfig.debug != true) {
        return this
      }
      var data = [];
      for(var n = 1;n < arguments.length;n++) {
        data.push(arguments[n])
      }
      if(typeof console != "undefined" && console.warn) {
        console.warn("[ec] " + arguments[0], data)
      }
      if(econdaConfig.debugOutputContainer != null) {
        this.setupLogViewer();
        this.logViewerInstance.warn(arguments[0], data)
      }
      return this
    };
    debug.log = function() {
      var args = [];
      for(var _i = 0;_i < arguments.length;_i++) {
        args[_i] = arguments[_i]
      }
      if(econdaConfig.debug != true) {
        return this
      }
      var data = [];
      for(var n = 1;n < arguments.length;n++) {
        data.push(arguments[n])
      }
      if(typeof console != "undefined" && console.log) {
        data.length > 0 ? console.log("[ec] " + arguments[0], data) : console.log("[ec] " + arguments[0])
      }
      if(econdaConfig.debugOutputContainer != null) {
        this.setupLogViewer();
        this.logViewerInstance.log(arguments[0], data)
      }
      return this
    };
    debug.setupLogViewer = function() {
      if(this.logViewerInstance == null) {
        this.logViewerInstance = new econda.util.LogViewer;
        this.logViewerInstance.container = econdaConfig.debugOutputContainer
      }
    };
    debug.logViewerInstance = null;
    return debug
  }();
  econda.debug = debug
})(econda || (econda = {}));
var econda;
(function(econda) {
  var util;
  (function(util) {
    var ArrayUtils = function() {
      function ArrayUtils() {
      }
      ArrayUtils.indexOf = function(arr, needle) {
        if(Array.prototype.indexOf) {
          return arr.indexOf(needle)
        }else {
          for(var n = 0;n < arr.length;n++) {
            if(arr[n] == needle) {
              return n
            }
          }
          return-1
        }
      };
      ArrayUtils.contains = function(arr, needle) {
        return ArrayUtils.indexOf(arr, needle) > -1
      };
      ArrayUtils.isArray = function(obj) {
        if(!this.isArrayFn) {
          this.isArrayFn = typeof Array.isArray != "undefined" ? Array.isArray : function(obj) {
            return Object.prototype.toString.call(obj) === "[object Array]"
          }
        }
        return this.isArrayFn(obj)
      };
      ArrayUtils.shuffle = function(arr) {
        var m = arr.length, t, i;
        while(m) {
          i = Math.floor(Math.random() * m--);
          t = arr[m];
          arr[m] = arr[i];
          arr[i] = t
        }
        return arr
      };
      ArrayUtils.remove = function(arr, itemOrItemsToRemove) {
        if(typeof itemOrItemsToRemove === "function") {
          return this._removeByFunction(arr, itemOrItemsToRemove)
        }
        var itemsToRemove = ArrayUtils.isArray(itemOrItemsToRemove) ? itemOrItemsToRemove : [itemOrItemsToRemove];
        return this._removeItems(arr, itemsToRemove)
      };
      ArrayUtils._removeItems = function(arr, itemsToRemove) {
        var itemsRemoved = [];
        for(var i = 0;i < itemsToRemove.length;i++) {
          var index = arr.indexOf(itemsToRemove[i]);
          if(index > -1) {
            itemsRemoved = itemsRemoved.concat(arr.splice(index, 1))
          }
        }
        return itemsRemoved
      };
      ArrayUtils._removeByFunction = function(arr, filterFunction) {
        var itemsRemoved = [];
        for(var n = 0;n < arr.length;n++) {
          if(filterFunction(arr[n], n, arr) === true) {
            itemsRemoved = itemsRemoved.concat(arr.splice(n, 1));
            n--
          }
        }
        return itemsRemoved
      };
      ArrayUtils.filter = function(arr, filterFunction) {
        if(typeof filterFunction !== "function") {
          return arr
        }
        if(typeof arr.filter === "function") {
          return arr.filter(filterFunction)
        }
        var ret = [];
        for(var n = 0;n < arr.length;n++) {
          if(filterFunction(arr[n], n, arr) === true) {
            ret.push(arr[n])
          }
        }
        return ret
      };
      ArrayUtils.isArrayFn = null;
      return ArrayUtils
    }();
    util.ArrayUtils = ArrayUtils
  })(util = econda.util || (econda.util = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var base;
  (function(base) {
    var BaseClass = function() {
      function BaseClass() {
        this.__defaultProperty = null
      }
      BaseClass.prototype.initConfig = function(cfg, defaultPropertyName) {
        if(defaultPropertyName === void 0) {
          defaultPropertyName = null
        }
        if(defaultPropertyName != null) {
          this.__defaultProperty = defaultPropertyName
        }
        if(typeof cfg != "undefined") {
          this.set(cfg)
        }
      };
      BaseClass.prototype.set = function(cfg, newValue) {
        var cmp = this;
        var propertyName, functionName;
        if(typeof cfg == "object") {
          for(propertyName in cfg) {
            functionName = cmp.getSetterName(propertyName);
            if(typeof this[functionName] != "undefined") {
              this[functionName](cfg[propertyName])
            }else {
              econda.debug.error("Cannot set " + propertyName + " in " + this._getClassName() + ": no setter defined.")
            }
          }
        }else {
          if(typeof cfg == "string" && arguments.length == 2) {
            propertyName = cfg;
            functionName = cmp.getSetterName(propertyName);
            if(typeof this[functionName] != "undefined") {
              this[functionName](newValue)
            }else {
              econda.debug.error("Cannot set " + propertyName + " in " + this._getClassName() + ": no setter defined.")
            }
          }else {
            if(typeof cfg != "undefined" && cmp.__defaultProperty) {
              functionName = cmp.getSetterName(cmp.__defaultProperty);
              cmp[functionName](cfg)
            }
          }
        }
        return this
      };
      BaseClass.prototype.get = function(propertyName) {
        var functionName = this.getGetterName(propertyName);
        if(typeof this[functionName] != "undefined") {
          return this[functionName]()
        }
        econda.debug.error("Cannot get " + propertyName + " in " + this._getClassName() + ": no getter defined.")
      };
      BaseClass.prototype._getClassName = function() {
        try {
          return this.constructor.name
        }catch(e) {
          return null
        }
      };
      BaseClass.prototype.getSetterName = function(propertyName) {
        return"set" + propertyName.substr(0, 1).toUpperCase() + propertyName.substr(1)
      };
      BaseClass.prototype.getGetterName = function(propertyName) {
        return"get" + propertyName.substr(0, 1).toUpperCase() + propertyName.substr(1)
      };
      BaseClass.prototype.setArray = function(fieldName, data, type, options) {
        if(type === void 0) {
          type = null
        }
        this[fieldName] = [];
        this.addArray(fieldName, data, type, options);
        return this
      };
      BaseClass.prototype.addArray = function(fieldName, data, type, options) {
        if(type === void 0) {
          type = null
        }
        if(typeof options === "undefined" || options === null) {
          options = {}
        }
        var collection = data;
        if(econda.util.ArrayUtils.isArray(data) == false) {
          collection = [data]
        }
        for(var n = 0;n < collection.length;n++) {
          var input;
          var item;
          if(typeof options.itemFilter === "function") {
            input = options.itemFilter.call(this, collection[n])
          }else {
            input = collection[n]
          }
          if(type === null || input instanceof type) {
            item = input
          }else {
            item = new type(input)
          }
          if(typeof options.callback === "function") {
            options.callback.call(this, item)
          }
          this[fieldName].push(item)
        }
        return this
      };
      BaseClass.prototype.clone = function() {
        var ret = new this.constructor;
        for(var key in this) {
          ret[key] = this[key]
        }
        return ret
      };
      return BaseClass
    }();
    base.BaseClass = BaseClass
  })(base = econda.base || (econda.base = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var recengine;
  (function(recengine) {
    var response;
    (function(response) {
      var Tracking = function(_super) {
        __extends(Tracking, _super);
        function Tracking(cfg) {
          if(cfg === void 0) {
            cfg = null
          }
          var _this = _super.call(this) || this;
          _this.emcs = null;
          _this.emcs0 = null;
          _this.emcs1 = null;
          if(cfg instanceof Tracking) {
            return cfg
          }
          if(cfg) {
            _this.initConfig(cfg)
          }
          return _this
        }
        Tracking.prototype.setEmcs = function(enabled) {
          this.emcs = enabled;
          return this
        };
        Tracking.prototype.getEmcs = function() {
          return this.emcs
        };
        Tracking.prototype.getEmcs0 = function() {
          return this.emcs0
        };
        Tracking.prototype.setEmcs0 = function(name) {
          this.emcs0 = name;
          return this
        };
        Tracking.prototype.getEmcs1 = function() {
          return this.emcs1
        };
        Tracking.prototype.setEmcs1 = function(position) {
          this.emcs1 = position;
          return this
        };
        return Tracking
      }(econda.base.BaseClass);
      response.Tracking = Tracking
    })(response = recengine.response || (recengine.response = {}))
  })(recengine = econda.recengine || (econda.recengine = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var recengine;
  (function(recengine) {
    var response;
    (function(response) {
      var WidgetDetails = function(_super) {
        __extends(WidgetDetails, _super);
        function WidgetDetails(cfg) {
          if(cfg === void 0) {
            cfg = null
          }
          var _this = _super.call(this) || this;
          _this._title = null;
          _this._disableOnEmpty = true;
          _this._tracking = null;
          _this._deeplinkFallbackUrl = null;
          if(cfg instanceof WidgetDetails) {
            return cfg
          }
          if(cfg) {
            _this.initConfig(cfg)
          }
          return _this
        }
        WidgetDetails.prototype.getTitle = function() {
          return this._title
        };
        WidgetDetails.prototype.setTitle = function(title) {
          this._title = title
        };
        WidgetDetails.prototype.getDisableOnEmpty = function() {
          return this._disableOnEmpty
        };
        WidgetDetails.prototype.setDisableonempty = function(disable) {
          this._disableOnEmpty = disable
        };
        WidgetDetails.prototype.setDisableOnEmpty = function(disable) {
          this._disableOnEmpty = disable
        };
        WidgetDetails.prototype.getTracking = function(returnEmptyObject) {
          if(returnEmptyObject === void 0) {
            returnEmptyObject = false
          }
          if(returnEmptyObject && this._tracking === null) {
            return new response.Tracking
          }
          return this._tracking
        };
        WidgetDetails.prototype.setTracking = function(trackingData) {
          this._tracking = new response.Tracking(trackingData)
        };
        WidgetDetails.prototype.setDeeplinkfallbackurl = function(url) {
          this._deeplinkFallbackUrl = url
        };
        WidgetDetails.prototype.setDeeplinkFallbackUrl = function(url) {
          this._deeplinkFallbackUrl = url
        };
        WidgetDetails.prototype.getDeeplinkFallbackUrl = function() {
          return this._deeplinkFallbackUrl
        };
        return WidgetDetails
      }(econda.base.BaseClass);
      response.WidgetDetails = WidgetDetails
    })(response = recengine.response || (recengine.response = {}))
  })(recengine = econda.recengine || (econda.recengine = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var recengine;
  (function(recengine) {
    var response;
    (function(response) {
      var WidgetDetails = econda.recengine.response.WidgetDetails;
      var Response = function(_super) {
        __extends(Response, _super);
        function Response(cfg) {
          var _this = _super.call(this) || this;
          _this._isError = false;
          _this._request = null;
          _this._startIndex = 0;
          _this._endIndex = null;
          _this.products = [];
          _this.widgetDetails = null;
          if(cfg instanceof Response) {
            return cfg
          }
          _this.initConfig(cfg);
          return _this
        }
        Response.prototype.getIsError = function() {
          return this._isError
        };
        Response.prototype.setIsError = function(isError) {
          this._isError = isError;
          return this
        };
        Response.prototype.getRequest = function() {
          return this._request
        };
        Response.prototype.setRequest = function(request) {
          this._request = request;
          return this
        };
        Response.prototype.getStartIndex = function() {
          return this._startIndex
        };
        Response.prototype.setStartIndex = function(index) {
          this._startIndex = index;
          return this
        };
        Response.prototype.getEndIndex = function() {
          return this._endIndex
        };
        Response.prototype.setEndIndex = function(index) {
          this._endIndex = index;
          return this
        };
        Response.prototype.getProducts = function() {
          return this.products
        };
        Response.prototype.setProducts = function(products) {
          this.products = products;
          return this
        };
        Response.prototype.setWidgetDetails = function(widgetDetails) {
          this.widgetDetails = new WidgetDetails(widgetDetails)
        };
        Response.prototype.getWidgetDetails = function(returnEmptyObject) {
          if(returnEmptyObject === void 0) {
            returnEmptyObject = false
          }
          if(returnEmptyObject && this.widgetDetails === null) {
            return new WidgetDetails
          }
          return this.widgetDetails
        };
        return Response
      }(econda.base.BaseClass);
      response.Response = Response
    })(response = recengine.response || (recengine.response = {}))
  })(recengine = econda.recengine || (econda.recengine = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var recengine;
  (function(recengine) {
    var decorator;
    (function(decorator) {
      var AbstractFieldDecorator = function(_super) {
        __extends(AbstractFieldDecorator, _super);
        function AbstractFieldDecorator() {
          var _this = _super !== null && _super.apply(this, arguments) || this;
          _this.request = null;
          _this.fields = {};
          _this.response = null;
          return _this
        }
        AbstractFieldDecorator.prototype.getRequest = function() {
          return this.request
        };
        AbstractFieldDecorator.prototype.setRequest = function(request) {
          this.request = request;
          return this
        };
        AbstractFieldDecorator.prototype.getFields = function() {
          var fieldNames = [];
          for(var name in this.fields) {
            fieldNames.push(name)
          }
          return this.fields
        };
        AbstractFieldDecorator.prototype.setFields = function(fieldNames) {
          if(econda.util.ArrayUtils.isArray(fieldNames)) {
            this.fields = fieldNames
          }else {
            this.fields = [fieldNames]
          }
          return this
        };
        AbstractFieldDecorator.prototype.decorate = function(response) {
          this.response = response;
          var products = response.getProducts();
          for(var i = 0;i < products.length;i++) {
            var product = products[i];
            this.decorateProduct(product);
            for(var n = 0;n < this.fields.length;n++) {
              if(typeof product[this.fields[n]] != "undefined") {
                product[this.fields[n]] = this.decorateField(product[this.fields[n]], product, this.fields[n])
              }
            }
          }
        };
        AbstractFieldDecorator.prototype.decorateProduct = function(product) {
        };
        AbstractFieldDecorator.prototype.decorateField = function(fieldValue, product, fieldName) {
        };
        return AbstractFieldDecorator
      }(econda.base.BaseClass);
      decorator.AbstractFieldDecorator = AbstractFieldDecorator
    })(decorator = recengine.decorator || (recengine.decorator = {}))
  })(recengine = econda.recengine || (econda.recengine = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var util;
  (function(util) {
    var UriUtils = function() {
      function UriUtils() {
      }
      UriUtils.appendParams = function(uri, params) {
        var baseUri = uri.indexOf("?") > 0 ? uri + "&" : uri + "?";
        return baseUri + UriUtils.concatParams(params)
      };
      UriUtils.concatParams = function(params) {
        var parts = [];
        for(var name in params) {
          parts.push(name + "=" + encodeURIComponent(params[name]))
        }
        return parts.join("&")
      };
      return UriUtils
    }();
    util.UriUtils = UriUtils
  })(util = econda.util || (econda.util = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var recengine;
  (function(recengine) {
    var decorator;
    (function(decorator) {
      var UriUtils = econda.util.UriUtils;
      var PerformanceTracking = function(_super) {
        __extends(PerformanceTracking, _super);
        function PerformanceTracking(cfg) {
          if(cfg === void 0) {
            cfg = null
          }
          var _this = _super.call(this) || this;
          _this.fields = ["deeplink"];
          _this.widgetName = null;
          _this.source = null;
          _this.position = null;
          if(cfg instanceof PerformanceTracking) {
            return cfg
          }
          _this.initConfig(cfg);
          return _this
        }
        PerformanceTracking.prototype.getWidgetName = function() {
          return this.widgetName
        };
        PerformanceTracking.prototype.setWidgetName = function(name) {
          this.widgetName = name;
          return this
        };
        PerformanceTracking.prototype.getSource = function() {
          return this.source
        };
        PerformanceTracking.prototype.setSource = function(name) {
          this.source = name;
          return this
        };
        PerformanceTracking.prototype.getPosition = function() {
          return this.position
        };
        PerformanceTracking.prototype.setPosition = function(name) {
          this.position = name;
          return this
        };
        PerformanceTracking.prototype._getTrackingParams = function(product) {
          var cmp = this;
          var w = this.getRequest();
          var widgetName = this.getWidgetName() || w.getWidgetId();
          return{emcs0:widgetName, emcs1:cmp.getPosition(), emcs2:cmp.getSource(), emcs3:product.id}
        };
        PerformanceTracking.prototype.decorateProduct = function(product) {
          product.trackingparameters = UriUtils.concatParams(this._getTrackingParams(product))
        };
        PerformanceTracking.prototype.decorateField = function(value, product, fieldName) {
          var uriWithParams = UriUtils.appendParams(value, this._getTrackingParams(product));
          return uriWithParams
        };
        return PerformanceTracking
      }(decorator.AbstractFieldDecorator);
      decorator.PerformanceTracking = PerformanceTracking
    })(decorator = recengine.decorator || (recengine.decorator = {}))
  })(recengine = econda.recengine || (econda.recengine = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var recengine;
  (function(recengine) {
    var decorator;
    (function(decorator) {
      var ArrayUtils = econda.util.ArrayUtils;
      var ProductListRandomizer = function(_super) {
        __extends(ProductListRandomizer, _super);
        function ProductListRandomizer(cfg) {
          if(cfg === void 0) {
            cfg = null
          }
          var _this = _super.call(this) || this;
          _this.request = null;
          if(cfg instanceof ProductListRandomizer) {
            return cfg
          }
          _this.initConfig(cfg);
          return _this
        }
        ProductListRandomizer.prototype.getRequest = function() {
          return this.request
        };
        ProductListRandomizer.prototype.setRequest = function(request) {
          this.request = request;
          return this
        };
        ProductListRandomizer.prototype.decorate = function(response) {
          var products = response.getProducts();
          ArrayUtils.shuffle(products)
        };
        return ProductListRandomizer
      }(econda.base.BaseClass);
      decorator.ProductListRandomizer = ProductListRandomizer
    })(decorator = recengine.decorator || (recengine.decorator = {}))
  })(recengine = econda.recengine || (econda.recengine = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var collection;
  (function(collection) {
    var ArrayUtils = econda.util.ArrayUtils;
    var List = function() {
      function List(cfg) {
        this._items = [];
        this._itemsById = {};
        this._idProperty = null;
        this.onChange = [];
        if(cfg instanceof List) {
          return cfg
        }
        if(typeof cfg === "object") {
          this._idProperty = cfg.idProperty || null;
          this._items = [];
          if(typeof cfg.items !== "undefined") {
            this._setItems(cfg.items)
          }
          if(typeof cfg.onChange !== "undefined") {
            this.setOnChange(cfg.onChange)
          }
        }
      }
      List.prototype.setOnChange = function(callback) {
        ArrayUtils.isArray(callback) ? this.onChange = callback : this.onChange = [callback]
      };
      List.prototype._fireOnChangeEvent = function(item) {
        for(var i = 0;i < this.onChange.length;i++) {
          this.onChange[i](item)
        }
      };
      List.prototype.add = function(item) {
        this._items.push(item);
        if(this._idProperty !== null && typeof item[this._idProperty] != "undefined") {
          this._itemsById["" + item[this._idProperty]] = item
        }
        this._collectionChanged();
        this._fireOnChangeEvent(item);
        return this
      };
      List.prototype.contains = function(needle) {
        return ArrayUtils.contains(this._items, needle)
      };
      List.prototype.length = function() {
        return this._items.length
      };
      List.prototype.sort = function(compareFunctionOrProperty) {
        var compareFunction = typeof compareFunctionOrProperty === "string" ? this._createCompareFunction(compareFunctionOrProperty) : compareFunctionOrProperty;
        return this._items.sort(compareFunction)
      };
      List.prototype._createCompareFunction = function(propertyName) {
        return function(a, b) {
          if(a[propertyName] > b[propertyName]) {
            return 1
          }
          if(a[propertyName] < b[propertyName]) {
            return-1
          }
          return 0
        }
      };
      List.prototype.indexOf = function(needle) {
        return ArrayUtils.indexOf(this._items, needle)
      };
      List.prototype.getItemById = function(id) {
        if(typeof this._itemsById["" + id] === "undefined") {
          return null
        }
        return this._itemsById["" + id]
      };
      List.prototype.get = function(index) {
        return typeof this._items[index] !== "undefined" ? this._items[index] : null
      };
      List.prototype.getAll = function() {
        return this._items
      };
      List.prototype.getFilteredItems = function(filter) {
        var matches = [];
        if(typeof filter.match === "function") {
          for(var n = 0;n < this._items.length;n++) {
            if(filter.match(this._items[n]) === true) {
              matches.push(this._items[n])
            }
          }
        }else {
          for(var n = 0;n < this._items.length;n++) {
            if(filter(this._items[n]) === true) {
              matches.push(this._items[n])
            }
          }
        }
        return matches
      };
      List.prototype.forEach = function(callback, scope) {
        for(var n = 0;n < this._items.length;n++) {
          callback.call(scope, this._items[n])
        }
      };
      List.prototype.clear = function() {
        this._setItems(null);
        return this
      };
      List.prototype._setItems = function(items) {
        this._items = [];
        this._itemsById = {};
        if(items === null) {
          return
        }
        if(typeof items.length !== "undefined") {
          this._items = items
        }else {
          if(typeof items === "object") {
            for(var propertyName in items) {
              if(items.hasOwnProperty(propertyName)) {
                this._items.push(items[propertyName])
              }
            }
          }
        }
        if(this._idProperty !== null) {
          for(var n = 0;n < this._items.length;n++) {
            this._itemsById["" + this._items[n][this._idProperty]] = this._items[n]
          }
        }
        this._collectionChanged();
        this.forEach(this._fireOnChangeEvent, this)
      };
      List.prototype._collectionChanged = function() {
      };
      return List
    }();
    collection.List = List
  })(collection = econda.collection || (econda.collection = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var recengine;
  (function(recengine) {
    var event;
    (function(event) {
      var AbstractEvent = function(_super) {
        __extends(AbstractEvent, _super);
        function AbstractEvent() {
          var _this = _super !== null && _super.apply(this, arguments) || this;
          _this._timestamp = new Date;
          return _this
        }
        AbstractEvent.prototype.getType = function() {
          return this.constructor["TYPE"]
        };
        AbstractEvent.prototype.getTimestamp = function() {
          return this._timestamp
        };
        AbstractEvent.prototype.setTimestamp = function(timestamp) {
          this._timestamp = timestamp
        };
        AbstractEvent.TYPE = null;
        return AbstractEvent
      }(econda.base.BaseClass);
      event.AbstractEvent = AbstractEvent
    })(event = recengine.event || (recengine.event = {}))
  })(recengine = econda.recengine || (econda.recengine = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var recengine;
  (function(recengine) {
    var context;
    (function(context) {
      var ProductReference = function(_super) {
        __extends(ProductReference, _super);
        function ProductReference(cfg) {
          var _this = _super.call(this) || this;
          _this._id = null;
          _this._sku = null;
          if(cfg instanceof ProductReference) {
            return cfg
          }else {
            _this.initConfig(cfg)
          }
          return _this
        }
        ProductReference.prototype.getId = function() {
          return this._id
        };
        ProductReference.prototype.setId = function(id) {
          this._id = id;
          return this
        };
        ProductReference.prototype.getSku = function() {
          return this._sku
        };
        ProductReference.prototype.setSku = function(sku) {
          this._sku = sku;
          return this
        };
        ProductReference.prototype.getObjectData = function() {
          return{className:"econda.recengine.context.ProductReference", data:{id:this._id, sku:this._sku}}
        };
        ProductReference.prototype.setObjectData = function(data) {
          if(typeof data === "object" && data !== null) {
            this._id = data.id || null;
            this._sku = data.sku || null
          }
        };
        return ProductReference
      }(econda.base.BaseClass);
      context.ProductReference = ProductReference
    })(context = recengine.context || (recengine.context = {}))
  })(recengine = econda.recengine || (econda.recengine = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var recengine;
  (function(recengine) {
    var event;
    (function(event) {
      var AbstractEvent = econda.recengine.event.AbstractEvent;
      var ProductReference = econda.recengine.context.ProductReference;
      var ProductAddToCartEvent = function(_super) {
        __extends(ProductAddToCartEvent, _super);
        function ProductAddToCartEvent(cfg) {
          var _this = _super.call(this) || this;
          _this._count = 1;
          _this._product = null;
          if(cfg instanceof ProductAddToCartEvent) {
            return cfg
          }
          _this.initConfig(cfg);
          return _this
        }
        ProductAddToCartEvent.prototype.getCount = function() {
          return this._count
        };
        ProductAddToCartEvent.prototype.setCount = function(count) {
          this._count = +count
        };
        ProductAddToCartEvent.prototype.getProduct = function() {
          return this._product
        };
        ProductAddToCartEvent.prototype.setProduct = function(productReference) {
          this._product = new ProductReference(productReference)
        };
        ProductAddToCartEvent.prototype.getObjectData = function() {
          return{className:"econda.recengine.event.ProductAddToCartEvent", data:{timestamp:this.getTimestamp(), count:this._count, product:this._product}}
        };
        ProductAddToCartEvent.prototype.setObjectData = function(data) {
          if(typeof data === "object" && data !== null) {
            this.setTimestamp(data.timestamp);
            this._count = data.count || 1;
            this._product = data.product || null
          }
        };
        ProductAddToCartEvent.TYPE = "product:add";
        return ProductAddToCartEvent
      }(AbstractEvent);
      event.ProductAddToCartEvent = ProductAddToCartEvent
    })(event = recengine.event || (recengine.event = {}))
  })(recengine = econda.recengine || (econda.recengine = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var recengine;
  (function(recengine) {
    var event;
    (function(event) {
      var AbstractEvent = econda.recengine.event.AbstractEvent;
      var ProductReference = econda.recengine.context.ProductReference;
      var ProductBuyEvent = function(_super) {
        __extends(ProductBuyEvent, _super);
        function ProductBuyEvent(cfg) {
          var _this = _super.call(this) || this;
          _this._count = 1;
          _this._product = null;
          if(cfg instanceof ProductBuyEvent) {
            return cfg
          }
          _this.initConfig(cfg);
          return _this
        }
        ProductBuyEvent.prototype.getCount = function() {
          return this._count
        };
        ProductBuyEvent.prototype.setCount = function(count) {
          this._count = +count
        };
        ProductBuyEvent.prototype.getProduct = function() {
          return this._product
        };
        ProductBuyEvent.prototype.setProduct = function(productReference) {
          this._product = new ProductReference(productReference)
        };
        ProductBuyEvent.prototype.getObjectData = function() {
          return{className:"econda.recengine.event.ProductBuyEvent", data:{timestamp:this.getTimestamp(), count:this._count, product:this._product}}
        };
        ProductBuyEvent.prototype.setObjectData = function(data) {
          if(typeof data === "object" && data !== null) {
            this.setTimestamp(data.timestamp);
            this._count = data.count || 1;
            this._product = data.product || null
          }
        };
        ProductBuyEvent.TYPE = "product:buy";
        return ProductBuyEvent
      }(AbstractEvent);
      event.ProductBuyEvent = ProductBuyEvent
    })(event = recengine.event || (recengine.event = {}))
  })(recengine = econda.recengine || (econda.recengine = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var recengine;
  (function(recengine) {
    var event;
    (function(event) {
      var AbstractEvent = econda.recengine.event.AbstractEvent;
      var ProductReference = econda.recengine.context.ProductReference;
      var ProductViewEvent = function(_super) {
        __extends(ProductViewEvent, _super);
        function ProductViewEvent(cfg) {
          var _this = _super.call(this) || this;
          _this._product = null;
          if(cfg instanceof ProductViewEvent) {
            return cfg
          }
          _this.initConfig(cfg);
          return _this
        }
        ProductViewEvent.prototype.getProduct = function() {
          return this._product
        };
        ProductViewEvent.prototype.setProduct = function(productReference) {
          this._product = new ProductReference(productReference)
        };
        ProductViewEvent.prototype.getObjectData = function() {
          return{className:"econda.recengine.event.ProductViewEvent", data:{timestamp:this.getTimestamp(), product:this._product}}
        };
        ProductViewEvent.prototype.setObjectData = function(data) {
          if(typeof data === "object" && data !== null) {
            this.setTimestamp(data.timestamp);
            this._product = data.product || null
          }
        };
        ProductViewEvent.TYPE = "product:view";
        return ProductViewEvent
      }(AbstractEvent);
      event.ProductViewEvent = ProductViewEvent
    })(event = recengine.event || (recengine.event = {}))
  })(recengine = econda.recengine || (econda.recengine = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var cookie;
  (function(cookie) {
    var Cookie = function(_super) {
      __extends(Cookie, _super);
      function Cookie(cfg) {
        var _this = _super.call(this) || this;
        _this._name = "";
        _this._value = "";
        _this._domain = "";
        _this._path = "/";
        _this._expires = null;
        _this._secure = false;
        if(cfg instanceof Cookie) {
          return cfg
        }
        _this.initConfig(cfg);
        return _this
      }
      Cookie.prototype.getName = function() {
        return this._name
      };
      Cookie.prototype.setName = function(name) {
        this._name = name;
        return this
      };
      Cookie.prototype.getValue = function() {
        return this._value
      };
      Cookie.prototype.setValue = function(value) {
        this._value = value;
        return this
      };
      Cookie.prototype.getDomain = function() {
        return this._domain
      };
      Cookie.prototype.setDomain = function(domainName) {
        this._domain = domainName;
        return this
      };
      Cookie.prototype.getPath = function() {
        return this._path
      };
      Cookie.prototype.setPath = function(path) {
        this._path = path;
        return this
      };
      Cookie.prototype.getExpires = function() {
        return this._expires
      };
      Cookie.prototype.setExpires = function(dateOrDays) {
        var expirationDate;
        if(typeof dateOrDays == "number") {
          expirationDate = new Date;
          expirationDate.setDate(expirationDate.getDate() + dateOrDays)
        }else {
          expirationDate = dateOrDays
        }
        this._expires = expirationDate;
        return this
      };
      Cookie.prototype.getSecure = function() {
        return this._secure
      };
      Cookie.prototype.setSecure = function(isSecure) {
        this._secure = isSecure;
        return this
      };
      return Cookie
    }(econda.base.BaseClass);
    cookie.Cookie = Cookie
  })(cookie = econda.cookie || (econda.cookie = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var cookie;
  (function(cookie_1) {
    var Store = function() {
      function Store() {
      }
      Store.contains = function(cookieName) {
        if(typeof document.cookie === "undefined") {
          return false
        }
        return(new RegExp("(?:;\\s*|^)" + encodeURIComponent(cookieName) + "=")).test(document.cookie)
      };
      Store.find = function(query) {
        if(typeof document.cookie === "undefined") {
          return{}
        }
        var pairs = document.cookie.split(";"), pair, result = {};
        for(var index = 0, len = pairs.length;index < len;++index) {
          pair = pairs[index].split("=");
          var name = pair[0].replace(/^\s+|\s+$/, "");
          var value = pair.slice(1).join("=");
          if(query instanceof RegExp == false || query.test(name)) {
            result[decodeURIComponent(name)] = decodeURIComponent(value)
          }
        }
        return result
      };
      Store.getValue = function(name) {
        if(econda.cookie.Store.contains(name)) {
          return econda.cookie.Store.find(name)[name]
        }else {
          return null
        }
      };
      Store.set = function(data, encodeValue) {
        if(encodeValue === void 0) {
          encodeValue = true
        }
        if(typeof document.cookie === "undefined") {
          return this
        }
        var cookie = new econda.cookie.Cookie(data);
        var name = cookie.getName();
        var value = cookie.getValue();
        var path = cookie.getPath();
        var domain = cookie.getDomain();
        var expires = cookie.getExpires();
        var secure = cookie.getSecure();
        var def = [encodeURIComponent(name) + "=" + (encodeValue ? encodeURIComponent(value) : value)];
        if(path) {
          def.push("path=" + path)
        }
        if(domain) {
          def.push("domain=" + domain)
        }
        if(expires) {
          def.push("expires=" + expires.toUTCString())
        }
        if(secure) {
          def.push("secure")
        }
        document.cookie = def.join(";");
        return this
      };
      Store.remove = function(data) {
        if(typeof document.cookie === "undefined") {
          return this
        }
        var cookie;
        if(typeof data === "string") {
          cookie = new econda.cookie.Cookie({name:data})
        }else {
          cookie = new econda.cookie.Cookie(data)
        }
        var name = cookie.getName();
        var path = cookie.getPath();
        var domain = cookie.getDomain();
        var secure = cookie.getSecure();
        var def = [encodeURIComponent(name) + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT"];
        if(path) {
          def.push("path=" + path)
        }
        if(domain) {
          def.push("domain=" + domain)
        }
        if(secure) {
          def.push("secure")
        }
        document.cookie = def.join(";");
        return this
      };
      return Store
    }();
    cookie_1.Store = Store
  })(cookie = econda.cookie || (econda.cookie = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var tracking;
  (function(tracking) {
    var CookieStore = econda.cookie.Store;
    var EmosConfig = function() {
      function EmosConfig(globalConfigProperties) {
        this.globalConfigProperties = globalConfigProperties;
        for(var attrname in globalConfigProperties) {
          this[attrname] = globalConfigProperties[attrname]
        }
      }
      EmosConfig.prototype.getPidIndex = function() {
        return this.PRODUCT_ID_IN_EC_EVENT
      };
      EmosConfig.prototype.getClientCookieLifetime = function() {
        return this.CLIENT_COOKIE_LIFETIME
      };
      EmosConfig.prototype.getCookieDomain = function() {
        return this.globalConfigProperties.COOKIE_DOMAIN || this.getDefaultCookieDomainFor(window.location.hostname)
      };
      EmosConfig.prototype.getDefaultCookieDomainFor = function(domainName) {
        var domainAr = domainName.split(".");
        var topLevelDomain = domainAr[domainAr.length - 1];
        var secondLevelDomain = domainAr[domainAr.length - 2];
        var anz = topLevelDomain == "uk" || topLevelDomain == "tr" || topLevelDomain == "br" || topLevelDomain == "at" && secondLevelDomain == "co" || topLevelDomain == "jp" && (secondLevelDomain == "co" || secondLevelDomain == "ac" || secondLevelDomain == "go" || secondLevelDomain == "ne" || secondLevelDomain == "or") ? 3 : 2;
        var defaultDomain;
        if(isNaN(parseInt(topLevelDomain, 10)) && domainAr.length >= anz) {
          defaultDomain = "";
          for(var i = domainAr.length - anz;i < domainAr.length;i++) {
            defaultDomain += "." + domainAr[i]
          }
        }else {
          defaultDomain = domainName
        }
        return defaultDomain
      };
      EmosConfig.prototype.doNotTrack = function() {
        if(window.emosDoNotTrack) {
          return true
        }
        if(this.DO_NOT_TRACK) {
          return true
        }
        if(this.COOKIE_DNT) {
          var value = CookieStore.getValue(this.COOKIE_DNT);
          if(value === "1") {
            return true
          }
        }
        return false
      };
      EmosConfig.prototype.isTrackThirdParty = function() {
        return typeof this.TRACK_THIRD_PARTY == "undefined" || this.TRACK_THIRD_PARTY == true
      };
      EmosConfig.prototype.isSyncCacheId = function() {
        return this.JSID == true
      };
      return EmosConfig
    }();
    tracking.EmosConfig = EmosConfig
  })(tracking = econda.tracking || (econda.tracking = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var tracking;
  (function(tracking) {
    var EmosConfig = econda.tracking.EmosConfig;
    var PluginManager = function() {
      function PluginManager() {
      }
      PluginManager.registerPlugin = function(plugin) {
        if(PluginManager._plugins.indexOf(plugin) === -1) {
          PluginManager._plugins.push(plugin)
        }
      };
      PluginManager.unregisterPlugin = function(plugin) {
        typeof plugin === "function" ? this.removeAllPlugInsOfType(plugin) : this.removePlugInByInstance(plugin)
      };
      PluginManager.removeAllPlugInsOfType = function(type) {
        for(var n = 0;n < this._plugins.length;n++) {
          if(this._plugins[n] instanceof type) {
            this._plugins.splice(n--, 1)
          }
        }
      };
      PluginManager.removePlugInByInstance = function(plugin) {
        for(var n = 0;n < this._plugins.length;n++) {
          if(this._plugins[n] === plugin) {
            this._plugins.splice(n, 1)
          }
        }
      };
      PluginManager.clearAll = function() {
        PluginManager._plugins = []
      };
      PluginManager.getRegisteredPlugins = function() {
        return PluginManager._plugins
      };
      PluginManager.handleTrackingRequestEvent = function(properties, globalConfigProperties, config) {
        if(properties === void 0) {
          properties = {}
        }
        if(config === void 0) {
          config = {}
        }
        var globalConfig = new EmosConfig(globalConfigProperties);
        if(typeof config["cb"] != "object" || config["cb"] === null) {
          config.cb = []
        }
        config.cb.push(function() {
          PluginManager.handleTrackingAfterRequestCallback(properties, globalConfig, config)
        });
        for(var n = 0;n < PluginManager._plugins.length;n++) {
          var plugin = PluginManager._plugins[n];
          if(typeof plugin.onRequest === "function") {
            plugin.onRequest(properties, globalConfig, config)
          }
        }
      };
      PluginManager.handleTrackingAfterRequestCallback = function(properties, globalConfig, config) {
        if(properties === void 0) {
          properties = {}
        }
        if(config === void 0) {
          config = {}
        }
        for(var n = 0;n < PluginManager._plugins.length;n++) {
          var plugin = PluginManager._plugins[n];
          if(typeof plugin.onAfterRequest === "function") {
            plugin.onAfterRequest(properties, globalConfig, config)
          }
        }
      };
      PluginManager.registerManager = function() {
        var emos3 = window["emos3"];
        if(typeof emos3 !== "object" || emos3 === null) {
          emos3 = window.emos3 = {}
        }
        if(typeof emos3["plugins"] !== "object" || emos3.plugins === null) {
          emos3["plugins"] = []
        }
        for(var i = 0;i < emos3.plugins.length;i++) {
          if(emos3.plugins[i] === PluginManager.handleTrackingRequestEvent) {
            return
          }
        }
        emos3.plugins.push(PluginManager.handleTrackingRequestEvent)
      };
      PluginManager._plugins = [];
      return PluginManager
    }();
    tracking.PluginManager = PluginManager;
    econda.tracking.PluginManager.registerManager()
  })(tracking = econda.tracking || (econda.tracking = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var recengine;
  (function(recengine) {
    var history;
    (function(history) {
      var TrackingPluginManager = econda.tracking.PluginManager;
      var TrackingListener = function() {
        function TrackingListener() {
        }
        TrackingListener.getInstance = function() {
          if(TrackingListener._instance === null) {
            TrackingListener._instance = new TrackingListener
          }
          return TrackingListener._instance
        };
        TrackingListener.prototype.onRequest = function(emosProperties, cfg) {
          var events = this.createEventsFromTrackingData(emosProperties, cfg.getPidIndex());
          for(var i = 0;i < events.length;i++) {
            econda["data"].visitor.getHistory().add(events[i])
          }
          if(econda.util.ArrayUtils.isArray(emosProperties.arpprops)) {
            this.storeArpProps(emosProperties.arpprops)
          }
        };
        TrackingListener.prototype.storeArpProps = function(arpProps) {
          for(var _i = 0, arpProps_1 = arpProps;_i < arpProps_1.length;_i++) {
            var arpProp = arpProps_1[_i];
            var arpKey = arpProp[0];
            var arpValue = arpProp[1];
            econda["data"].visitor.setCustomProperty(arpKey, arpValue)
          }
        };
        TrackingListener.prototype.createEventsFromTrackingData = function(emosProperties, pidIndex) {
          var events = [];
          if(typeof emosProperties.ec_Event === "object") {
            for(var i = 0;i < emosProperties.ec_Event.length;i++) {
              var e = this.createEventFromEcEventItem(emosProperties.ec_Event[i], pidIndex);
              if(e) {
                events.push(e)
              }
            }
          }
          return events
        };
        TrackingListener.prototype.createEventFromEcEventItem = function(ecEvent, pidIndex) {
          var createEvent = function(ecEventType, eventConfig) {
            if(eventConfig === void 0) {
              eventConfig = null
            }
            var event = null;
            switch(String(ecEventType).toLowerCase()) {
              case "view":
                event = new econda.recengine.event.ProductViewEvent(eventConfig);
                break;
              case "c_add":
                event = new econda.recengine.event.ProductAddToCartEvent(eventConfig);
                break;
              case "buy":
                event = new econda.recengine.event.ProductBuyEvent(eventConfig);
                break
            }
            return event
          };
          var event = null;
          if(typeof ecEvent.type !== "undefined") {
            event = createEvent(ecEvent.type, {product:{id:ecEvent.pid || null, sku:ecEvent.sku || null}})
          }else {
            if(typeof ecEvent[1] !== "undefined") {
              event = createEvent(ecEvent[0], {product:{id:ecEvent[pidIndex], sku:null}})
            }
          }
          return event
        };
        TrackingListener.enable = function() {
          TrackingPluginManager.registerPlugin(TrackingListener.getInstance())
        };
        TrackingListener.disable = function() {
          TrackingPluginManager.unregisterPlugin(TrackingListener.getInstance())
        };
        TrackingListener._instance = null;
        return TrackingListener
      }();
      history.TrackingListener = TrackingListener;
      if(typeof econdaConfig.crosssellTrackEvents === "undefined" || econdaConfig.crosssellTrackEvents == true) {
        TrackingListener.enable()
      }
    })(history = recengine.history || (recengine.history = {}))
  })(recengine = econda.recengine || (econda.recengine = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var recengine;
  (function(recengine) {
    var history;
    (function(history) {
      var VisitorHistory = function(_super) {
        __extends(VisitorHistory, _super);
        function VisitorHistory() {
          var _this = _super !== null && _super.apply(this, arguments) || this;
          _this._maxLength = 30;
          return _this
        }
        VisitorHistory.prototype.getMaxLength = function() {
          return this._maxLength
        };
        VisitorHistory.prototype.setMaxLength = function(maxLength) {
          this._maxLength = +maxLength
        };
        VisitorHistory.prototype.cleanup = function() {
          this.sort("timestamp");
          if(this._items.length > this._maxLength) {
            var numberOfItemsToRemove = this._items.length - this._maxLength;
            this._items.splice(0, numberOfItemsToRemove)
          }
        };
        VisitorHistory.prototype.getObjectData = function() {
          return{className:"econda.recengine.history.VisitorHistory", data:{items:this._items}}
        };
        VisitorHistory.prototype.setObjectData = function(data) {
          if(typeof data === "object" && data !== null) {
            if(typeof data.items !== "undefined") {
              this._items = data.items
            }
          }
        };
        VisitorHistory.prototype._collectionChanged = function() {
          this.cleanup()
        };
        return VisitorHistory
      }(econda.collection.List);
      history.VisitorHistory = VisitorHistory
    })(history = recengine.history || (recengine.history = {}))
  })(recengine = econda.recengine || (econda.recengine = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var env;
  (function(env) {
    var AbstractStorage = function() {
      function AbstractStorage() {
      }
      AbstractStorage.prototype.isAvailable = function() {
        if(typeof this.storage() != "undefined" && typeof this.storage().getItem == "function") {
          try {
            this.storage().setItem("isAvailableTest", "someValue");
            this.storage().removeItem("isAvailableTest")
          }catch(e) {
            if(e.message && e.message.toLowerCase().indexOf("quota") > -1) {
              return false
            }
          }
          return true
        }else {
          return false
        }
      };
      AbstractStorage.prototype.setItem = function(key, data) {
        this.isAvailable() && this.storage().setItem(key, data)
      };
      AbstractStorage.prototype.getItem = function(key) {
        if(this.isAvailable()) {
          return this.storage().getItem(key)
        }
        return null
      };
      AbstractStorage.prototype.removeItem = function(key) {
        this.isAvailable() && this.storage().removeItem(key)
      };
      return AbstractStorage
    }();
    env.AbstractStorage = AbstractStorage
  })(env = econda.env || (econda.env = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var env;
  (function(env) {
    var SessionStorage = function(_super) {
      __extends(SessionStorage, _super);
      function SessionStorage() {
        return _super !== null && _super.apply(this, arguments) || this
      }
      SessionStorage.prototype.storage = function() {
        return window.sessionStorage
      };
      SessionStorage.isAvailable = function() {
        return(new SessionStorage).isAvailable()
      };
      SessionStorage.setItem = function(key, data) {
        (new SessionStorage).setItem(key, data)
      };
      SessionStorage.getItem = function(key) {
        return(new SessionStorage).getItem(key)
      };
      SessionStorage.removeItem = function(key) {
        (new SessionStorage).removeItem(key)
      };
      return SessionStorage
    }(econda.env.AbstractStorage);
    env.SessionStorage = SessionStorage
  })(env = econda.env || (econda.env = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var env;
  (function(env) {
    var LocalStorage = function(_super) {
      __extends(LocalStorage, _super);
      function LocalStorage() {
        return _super !== null && _super.apply(this, arguments) || this
      }
      LocalStorage.prototype.storage = function() {
        return window.localStorage
      };
      LocalStorage.isAvailable = function() {
        return(new LocalStorage).isAvailable()
      };
      LocalStorage.setItem = function(key, data) {
        (new LocalStorage).setItem(key, data)
      };
      LocalStorage.getItem = function(key) {
        return(new LocalStorage).getItem(key)
      };
      LocalStorage.removeItem = function(key) {
        (new LocalStorage).removeItem(key)
      };
      return LocalStorage
    }(econda.env.AbstractStorage);
    env.LocalStorage = LocalStorage
  })(env = econda.env || (econda.env = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var util;
  (function(util) {
    var Json = function() {
      function Json() {
      }
      Json.stringify = function(obj) {
        if(typeof JSON != "undefined" && JSON.stringify) {
          Json.stringify = JSON.stringify
        }else {
          Json.stringify = Json._stringify
        }
        return Json.stringify(obj)
      };
      Json._stringify = function(obj) {
        var t = typeof obj, v, json = [], arr = obj && obj.constructor == Array;
        if(t != "object" || obj === null) {
          if(t == "string") {
            obj = '"' + obj + '"'
          }
          return String(obj)
        }else {
          for(var n in obj) {
            v = obj[n];
            t = typeof v;
            if(t == "string") {
              v = '"' + v + '"'
            }else {
              if(t == "object" && v !== null) {
                v = this.stringify(v)
              }
            }
            json.push((arr ? "" : '"' + n + '":') + String(v))
          }
          return(arr ? "[" : "{") + String(json) + (arr ? "]" : "}")
        }
      };
      Json.parse = function(json) {
        if(typeof JSON != "undefined" && JSON.parse) {
          Json.parse = JSON.parse
        }else {
          Json.parse = Json._parse
        }
        return Json.parse(json)
      };
      Json._parse = function(json) {
        var cx = Json.cx;
        var j;
        json = String(json);
        cx.lastIndex = 0;
        if(cx.test(json)) {
          json = json.replace(cx, function(a) {
            return"\\u" + ("0000" + a.charCodeAt(0).toString(16)).slice(-4)
          })
        }
        if(/^[\],:{}\s]*$/.test(json.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, "@").replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, "]").replace(/(?:^|:|,)(?:\s*\[)+/g, ""))) {
          j = eval("(" + json + ")");
          return j
        }
        throw new SyntaxError("JSON.parse");
      };
      Json.cx = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g;
      return Json
    }();
    util.Json = Json
  })(util = econda.util || (econda.util = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var serialization;
  (function(serialization) {
    var JsonSerializer = function() {
      function JsonSerializer() {
      }
      JsonSerializer.serialize = function(data) {
        var serializer = new JsonSerializer;
        return serializer.serialize(data)
      };
      JsonSerializer.deserialize = function(data, targetObj) {
        if(targetObj === void 0) {
          targetObj = null
        }
        var serializer = new JsonSerializer;
        return serializer.deserialize(data, targetObj)
      };
      JsonSerializer.prototype.serialize = function(obj) {
        var data = this._serializeNestedObjects(obj);
        return econda.util.Json.stringify(data)
      };
      JsonSerializer.prototype._serializeNestedObjects = function(data) {
        var ret;
        if(econda.util.ArrayUtils.isArray(data)) {
          ret = [];
          for(var n = 0;n < data.length;n++) {
            ret[n] = this._serializeNestedObjects(data[n])
          }
          return ret
        }
        if(typeof data === "object" && data !== null) {
          if(typeof data["getObjectData"] !== "undefined") {
            ret = data.getObjectData();
            ret["$serialized-object$"] = true;
            ret.data = this._serializeNestedObjects(ret.data || null);
            return ret
          }else {
            if(data instanceof Date) {
              ret = {className:"Date", "$serialized-object$":true, data:data.toISOString()};
              return ret
            }else {
              ret = {};
              for(var propertyName in data) {
                ret[propertyName] = this._serializeNestedObjects(data[propertyName])
              }
              return ret
            }
          }
        }
        return data
      };
      JsonSerializer.prototype.deserialize = function(json, targetObj) {
        if(targetObj === void 0) {
          targetObj = null
        }
        if(!json) {
          return targetObj || null
        }
        var info = econda.util.Json.parse(json);
        var ret;
        if(targetObj !== null) {
          this._applyData(info.data, targetObj);
          ret = targetObj
        }else {
          ret = this._resolveNestedSerializedObjects(info)
        }
        return ret
      };
      JsonSerializer.prototype._applyData = function(data, targetObj) {
        if(typeof targetObj.setObjectData !== "function") {
          throw"setObjectData() not implemented. Cannot deserialize object";
        }
        data = this._resolveNestedSerializedObjects(data);
        targetObj.setObjectData(data)
      };
      JsonSerializer.prototype._resolveNestedSerializedObjects = function(data) {
        if(econda.util.ArrayUtils.isArray(data)) {
          for(var n = 0;n < data.length;n++) {
            data[n] = this._resolveNestedSerializedObjects(data[n])
          }
        }else {
          if(typeof data === "object" && data !== null) {
            if(typeof data["$serialized-object$"] !== "undefined") {
              data = this._deserializeNestedObject(data)
            }else {
              for(var propertyName in data) {
                data[propertyName] = this._resolveNestedSerializedObjects(data[propertyName])
              }
            }
          }
        }
        return data
      };
      JsonSerializer.prototype._deserializeNestedObject = function(info) {
        var obj;
        if(info.className === "Date") {
          obj = new Date(info.data)
        }else {
          try {
            obj = eval("new " + info.className + "();");
            if(typeof info.data !== "undefined") {
              this._applyData(info.data, obj)
            }
          }catch(e) {
            econda.debug.error("deserialization faild with exception:", e)
          }
        }
        return obj
      };
      return JsonSerializer
    }();
    serialization.JsonSerializer = JsonSerializer
  })(serialization = econda.serialization || (econda.serialization = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var recengine;
  (function(recengine) {
    var visitor;
    (function(visitor) {
      var LocalStorage = econda.env.LocalStorage;
      var VisitorData = function(_super) {
        __extends(VisitorData, _super);
        function VisitorData(cfg) {
          var _this = _super.call(this) || this;
          _this._ids = {};
          _this._properties = {};
          _this._loginState = null;
          _this._history = null;
          _this._isInTransaction = false;
          _this._triggerSaveTimeout = null;
          if(cfg instanceof VisitorData) {
            return cfg
          }
          _this.initConfig(cfg);
          return _this
        }
        VisitorData.prototype.setId = function(typeOrDataObject, value) {
          if(value === void 0) {
            value = null
          }
          if(typeof typeOrDataObject === "string") {
            this._ids[typeOrDataObject] = value
          }else {
            for(var idType in typeOrDataObject) {
              this._ids[idType] = typeOrDataObject[idType]
            }
          }
          this._triggerSaveIfNotInTransaction();
          return this
        };
        VisitorData.prototype.setIds = function(typeOrDataObject, value) {
          if(value === void 0) {
            value = null
          }
          this.setId(typeOrDataObject, value)
        };
        VisitorData.prototype.getId = function(typeName) {
          return this._ids[typeName] || null
        };
        VisitorData.prototype.getIds = function() {
          return this._ids
        };
        VisitorData.prototype.clearIds = function() {
          this._ids = {};
          this._triggerSaveIfNotInTransaction()
        };
        VisitorData.prototype.getProperty = function(name) {
          return this._properties[name] || null
        };
        VisitorData.prototype.setProperty = function(nameOrDataObject, value) {
          if(typeof nameOrDataObject === "string") {
            this._properties[nameOrDataObject] = value
          }else {
            if(typeof nameOrDataObject === "object" && nameOrDataObject !== null) {
              for(var propertyName in nameOrDataObject) {
                this._properties[propertyName] = nameOrDataObject[propertyName]
              }
            }
          }
          this._triggerSaveIfNotInTransaction()
        };
        VisitorData.prototype.getProperties = function(name) {
          if(typeof name === "string") {
            return this._properties[name] || null
          }
          return this._properties
        };
        VisitorData.prototype.setProperties = function(nameOrDataObject, value) {
          if(value === void 0) {
            value = null
          }
          this.setProperty(nameOrDataObject, value)
        };
        VisitorData.prototype.clearProperties = function() {
          this._properties = {};
          this._triggerSaveIfNotInTransaction()
        };
        VisitorData.prototype.setLoginState = function(state) {
          this._loginState = state;
          this._triggerSaveIfNotInTransaction()
        };
        VisitorData.prototype.getLoginState = function() {
          return this._loginState
        };
        VisitorData.prototype.getHistory = function() {
          return this._history
        };
        VisitorData.prototype.setHistory = function(visitorHistory) {
          var _this = this;
          this._history = visitorHistory;
          this._triggerSaveIfNotInTransaction();
          if(this._history !== null) {
            this._history.setOnChange(function(item) {
              return _this._triggerSaveIfNotInTransaction()
            })
          }
        };
        VisitorData.prototype.beginTransaction = function() {
          this._isInTransaction = true
        };
        VisitorData.prototype.commitTransaction = function() {
          this._isInTransaction = false;
          this.saveInBrowser()
        };
        VisitorData.prototype._triggerSaveIfNotInTransaction = function() {
          this._isInTransaction || this._triggerSaveInBrowser()
        };
        VisitorData.prototype._triggerSaveInBrowser = function() {
          var cmp = this;
          if(this._triggerSaveTimeout !== null) {
            clearTimeout(this._triggerSaveTimeout)
          }
          this._triggerSaveTimeout = setTimeout(function() {
            cmp.saveInBrowser()
          }, 50)
        };
        VisitorData.prototype.saveInBrowser = function() {
          if(this._triggerSaveTimeout !== null) {
            clearTimeout(this._triggerSaveTimeout)
          }
          var s = new econda.serialization.JsonSerializer;
          LocalStorage.setItem(VisitorData.STORAGE_KEY, s.serialize(this))
        };
        VisitorData.prototype.loadFromBrowser = function() {
          if(this._triggerSaveTimeout !== null) {
            clearTimeout(this._triggerSaveTimeout)
          }
          var s = new econda.serialization.JsonSerializer;
          var item = LocalStorage.getItem(VisitorData.STORAGE_KEY);
          if(item) {
            s.deserialize(item, this)
          }
        };
        VisitorData.prototype.getObjectData = function() {
          return{className:"econda.recengine.visitor.VisitorData", data:{ids:this._ids, properties:this._properties, loginState:this._loginState, history:this._history}}
        };
        VisitorData.prototype.setObjectData = function(data) {
          if(typeof data === "object" && data !== null) {
            if(typeof data.properties === "object" && data.properties !== null) {
              this._properties = data.properties
            }
            if(typeof data.ids === "object" && data.ids !== null) {
              this._ids = data.ids
            }
            if(typeof data.loginState !== "undefined") {
              this._loginState = data.loginState
            }
            if(typeof data.history !== "undefined") {
              this.setHistory(data.history)
            }
          }
        };
        VisitorData.STORAGE_KEY = "econda.recengine.VisitorData";
        return VisitorData
      }(econda.base.BaseClass);
      visitor.VisitorData = VisitorData
    })(visitor = recengine.visitor || (recengine.visitor = {}))
  })(recengine = econda.recengine || (econda.recengine = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var tracking;
  (function(tracking) {
    var CookieStore = econda.cookie.Store;
    var StringUtils = econda.util.StringUtils;
    var VisitorId = function() {
      function VisitorId() {
      }
      VisitorId.get = function() {
        var cookieValue = CookieStore.getValue("emos_jcvid");
        if(cookieValue !== null) {
          return cookieValue.split(":", 2)[0]
        }
        return null
      };
      VisitorId.update = function(visitorId, cookieOptions) {
        if(cookieOptions === void 0) {
          cookieOptions = {}
        }
        var cookieConfig = function(config, sessionOnly) {
          for(var attrname in cookieOptions) {
            if(sessionOnly == false || attrname != "expires") {
              config[attrname] = cookieOptions[attrname]
            }
          }
          return config
        };
        if(typeof visitorId !== "string") {
          econda.debug.error("Invalid visitor id given in VisitorId::update().");
          return
        }
        visitorId = StringUtils.trim(visitorId);
        var emos3 = window["emos3"];
        if(typeof emos3 === "object") {
          emos3.emos_vid = visitorId
        }
        var visitorCookieValue = CookieStore.getValue("emos_jcvid");
        var newVisitorCookieValue;
        if(typeof visitorCookieValue === "string" && visitorCookieValue.indexOf(":") !== -1) {
          newVisitorCookieValue = visitorId + visitorCookieValue.substring(visitorCookieValue.indexOf(":"))
        }else {
          newVisitorCookieValue = visitorId
        }
        CookieStore.set(cookieConfig({name:"emos_jcvid", value:newVisitorCookieValue}, false), false)
      };
      return VisitorId
    }();
    tracking.VisitorId = VisitorId
  })(tracking = econda.tracking || (econda.tracking = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var recengine;
  (function(recengine) {
    var VisitorHistory = econda.recengine.history.VisitorHistory;
    var VisitorData = econda.recengine.visitor.VisitorData;
    var ArrayUtils = econda.util.ArrayUtils;
    var VisitorId = econda.tracking.VisitorId;
    var SessionStorage = econda.env.SessionStorage;
    var VisitorProfile = function(_super) {
      __extends(VisitorProfile, _super);
      function VisitorProfile(cfg) {
        if(cfg === void 0) {
          cfg = null
        }
        var _this = _super.call(this) || this;
        _this._persistentData = null;
        _this.initConfig(cfg);
        _this._initPersistentData();
        var storageKey = "econda.recengine.VisitorProfile.initialized";
        if(SessionStorage.getItem(storageKey) === null) {
          _this._onFirstInitOfSession()
        }
        SessionStorage.setItem(storageKey, (new Date).toString());
        _this._initRecipientId();
        return _this
      }
      VisitorProfile.prototype.beginTransaction = function() {
        this._persistentData.beginTransaction()
      };
      VisitorProfile.prototype.commitTransaction = function() {
        this._persistentData.commitTransaction()
      };
      VisitorProfile.prototype.getVisitorId = function() {
        return VisitorId.get()
      };
      VisitorProfile.prototype.getRecipientId = function() {
        return this._persistentData.getId("recipientId")
      };
      VisitorProfile.prototype.setRecipientId = function(recipientId) {
        this._persistentData.setId("recipientId", recipientId)
      };
      VisitorProfile.prototype.getCustomerId = function() {
        return this._persistentData.getId("customerId")
      };
      VisitorProfile.prototype.setCustomerId = function(customerId) {
        this._persistentData.setId("customerId", customerId)
      };
      VisitorProfile.prototype.getUserId = function() {
        return this._persistentData.getId("userId")
      };
      VisitorProfile.prototype.setUserId = function(userId) {
        this._persistentData.setId("userId", userId)
      };
      VisitorProfile.prototype.getEmail = function() {
        return this._persistentData.getId("email")
      };
      VisitorProfile.prototype.setEmail = function(email) {
        this._persistentData.setId("email", email)
      };
      VisitorProfile.prototype.getEmailHash = function() {
        return this._persistentData.getId("emailHash")
      };
      VisitorProfile.prototype.setEmailHash = function(emailHash) {
        this._persistentData.setId("emailHash", emailHash)
      };
      VisitorProfile.prototype.hasIds = function() {
        if(this.getVisitorId()) {
          return true
        }
        var ids = this._persistentData.getIds();
        if(ids && Object.keys(ids).length > 0) {
          return true
        }
        return false
      };
      VisitorProfile.prototype.getLoginState = function() {
        return this._persistentData.getLoginState() || VisitorProfile.LOGIN_STATE_UNKNOWN
      };
      VisitorProfile.prototype.setLoginState = function(state) {
        if(!ArrayUtils.contains([VisitorProfile.LOGIN_STATE_PREVIOUS_SESSION, VisitorProfile.LOGIN_STATE_SIGNED_IN, VisitorProfile.LOGIN_STATE_SIGNED_OUT, VisitorProfile.LOGIN_STATE_UNKNOWN], state)) {
          econda.debug.error("Invalid login state: " + state)
        }
        this._persistentData.setLoginState(state)
      };
      VisitorProfile.prototype.login = function(userData) {
        if(userData === void 0) {
          userData = null
        }
        this._persistentData.setLoginState(VisitorProfile.LOGIN_STATE_SIGNED_IN);
        userData = userData || {};
        if(typeof userData.ids === "object" && userData.ids !== null) {
          this._persistentData.setIds({customerId:userData.ids.customerId || null, userId:userData.ids.userId || null, email:userData.ids.email || null, emailHash:userData.ids.emailHash || null})
        }
        this._persistentData.clearProperties();
        if(typeof userData.properties === "object" && userData.properties !== null) {
          this.setProperties(userData.properties)
        }
        return this
      };
      VisitorProfile.prototype.logout = function() {
        this._persistentData.setLoginState(VisitorProfile.LOGIN_STATE_SIGNED_OUT);
        this.clearAll();
        return this
      };
      VisitorProfile.prototype.clearAll = function() {
        this._persistentData.clearIds();
        this._persistentData.clearProperties();
        return this
      };
      VisitorProfile.prototype.getProperties = function() {
        return this._persistentData.getProperties()
      };
      VisitorProfile.prototype.setProperties = function(data) {
        this._persistentData.setProperties(data)
      };
      VisitorProfile.prototype.setProperty = function(name, value) {
        this._persistentData.setProperty(name, value)
      };
      VisitorProfile.prototype.getProperty = function(name) {
        return this._persistentData.getProperty(name)
      };
      VisitorProfile.prototype.setCustomProperty = function(name, value) {
        this.setProperty(VisitorProfile.CUSTOM_PROFILE_PROPERTIES_PREFIX + name, value)
      };
      VisitorProfile.prototype.getCustomProperty = function(name) {
        return this.getProperty(VisitorProfile.CUSTOM_PROFILE_PROPERTIES_PREFIX + name)
      };
      VisitorProfile.prototype.getVisitorHistory = function() {
        return this.getHistory()
      };
      VisitorProfile.prototype.getHistory = function() {
        return this._persistentData.getHistory()
      };
      VisitorProfile.prototype._onFirstInitOfSession = function() {
        this.setRecipientId(null);
        if(this.getLoginState() === VisitorProfile.LOGIN_STATE_SIGNED_IN) {
          this.setLoginState(VisitorProfile.LOGIN_STATE_PREVIOUS_SESSION)
        }
        return this
      };
      VisitorProfile.prototype._initRecipientId = function() {
        var uri = new econda.net.Uri(window.location.href);
        var recipientId = uri.getParam("ecmUid");
        if(recipientId) {
          this.setRecipientId(recipientId)
        }
        return this
      };
      VisitorProfile.prototype._initPersistentData = function() {
        this._persistentData = new VisitorData;
        this._persistentData.loadFromBrowser();
        if(this.getHistory() instanceof VisitorHistory === false) {
          this._persistentData.setHistory(new VisitorHistory)
        }
        return this
      };
      VisitorProfile.CUSTOM_PROFILE_PROPERTIES_PREFIX = "cu:";
      VisitorProfile.LOGIN_STATE_SIGNED_IN = "signed_in";
      VisitorProfile.LOGIN_STATE_PREVIOUS_SESSION = "previous_session";
      VisitorProfile.LOGIN_STATE_SIGNED_OUT = "signed_out";
      VisitorProfile.LOGIN_STATE_UNKNOWN = "unknown";
      return VisitorProfile
    }(econda.base.BaseClass);
    recengine.VisitorProfile = VisitorProfile
  })(recengine = econda.recengine || (econda.recengine = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var storage;
  (function(storage) {
    var Variable = function(_super) {
      __extends(Variable, _super);
      function Variable(cfg) {
        var _this = _super.call(this) || this;
        _this._name = null;
        _this._value = null;
        _this._permanent = false;
        _this._pageViewsToLive = null;
        _this._expires = null;
        _this._invalidationAction = Variable.INVALIDATION_ACTION_REMOVE;
        _this._isValid = true;
        if(cfg instanceof Variable) {
          return cfg
        }
        _this.initConfig(cfg);
        return _this
      }
      Variable.prototype.setName = function(name) {
        this._name = name;
        return this
      };
      Variable.prototype.getName = function() {
        return this._name
      };
      Variable.prototype.setValue = function(data) {
        this._value = data;
        return this
      };
      Variable.prototype.getValue = function() {
        return this._value
      };
      Variable.prototype.setPermanent = function(permanent) {
        this._permanent = permanent;
        return this
      };
      Variable.prototype.getPermanent = function() {
        return this._permanent
      };
      Variable.prototype.setPageViewsToLive = function(numberOfPageViews) {
        this._pageViewsToLive = numberOfPageViews;
        return this
      };
      Variable.prototype.getPageViewsToLive = function() {
        return this._pageViewsToLive
      };
      Variable.prototype.setExpires = function(date) {
        this._expires = date;
        return this
      };
      Variable.prototype.getExpires = function() {
        return this._expires
      };
      Variable.prototype.setTtl = function(seconds) {
        var d = new Date;
        d.setSeconds(d.getSeconds() + seconds);
        this._expires = d;
        return this
      };
      Variable.prototype.setInvalidationAction = function(action) {
        this._invalidationAction = action;
        return this
      };
      Variable.prototype.getInvalidationAction = function() {
        return this._invalidationAction
      };
      Variable.prototype.getIsValid = function() {
        return this._isValid
      };
      Variable.prototype._setIsValid = function(isValid) {
        this._isValid = isValid;
        return this
      };
      Variable.prototype.getObjectData = function() {
        var ret = {className:"econda.storage.Variable", data:{name:this._name, value:this._value, expires:this._expires, pageViewsToLive:this._pageViewsToLive, permanent:this._permanent, invalidationAction:this._invalidationAction}};
        return ret
      };
      Variable.prototype.setObjectData = function(data) {
        this.set(data)
      };
      Variable.INVALIDATION_ACTION_REMOVE = "remove";
      Variable.INVALIDATION_ACTION_KEEP = "keep";
      return Variable
    }(econda.base.BaseClass);
    storage.Variable = Variable
  })(storage = econda.storage || (econda.storage = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var storage;
  (function(storage) {
    var Variable = econda.storage.Variable;
    var SessionStorage = econda.env.SessionStorage;
    var LocalStorage = econda.env.LocalStorage;
    var ClientBag = function() {
      function ClientBag(cfg) {
        this._triggerSaveTimeout = null;
        this._lastSaved = null;
        this._name = "default";
        this._autoSave = true;
        this._variables = {};
        this._variableStates = {};
        if(cfg instanceof ClientBag) {
          return cfg
        }
        if(typeof cfg === "object" && cfg !== null) {
          if(typeof cfg.name !== "undefined") {
            this.setName(cfg.name)
          }
          if(typeof cfg.autoSave !== "undefined") {
            this.setAutoSave(cfg.autoSave)
          }
        }
      }
      ClientBag.prototype.setName = function(name) {
        this._name = name;
        return this
      };
      ClientBag.prototype.getName = function() {
        return this._name
      };
      ClientBag.prototype.setAutoSave = function(autoSave) {
        this._autoSave = autoSave;
        return this
      };
      ClientBag.prototype.getAutoSave = function() {
        return this._autoSave
      };
      ClientBag.prototype.getStorageKey = function() {
        return"econda.storage.ClientBag." + this._name
      };
      ClientBag.prototype.getAllVariables = function() {
        var ret = [];
        for(var variableName in this._variables) {
          ret.push(this._variables[variableName])
        }
        return ret
      };
      ClientBag.prototype.get = function(variableName) {
        if(typeof this._variables[variableName] === "undefined") {
          return null
        }else {
          return this._variables[variableName]
        }
      };
      ClientBag.prototype.getState = function(variableName) {
        if(typeof this._variableStates[variableName] === "undefined") {
          return null
        }else {
          return this._variableStates[variableName]
        }
      };
      ClientBag.prototype.set = function(variable, replace) {
        if(replace === void 0) {
          replace = false
        }
        variable = new Variable(variable);
        if(!variable.getName()) {
          throw"Cannot set variable. Name attribute is required but empty.";
        }
        var variableName = variable.getName();
        if(replace === true || typeof this._variables[variableName] === "undefined") {
          this._variables[variableName] = variable
        }
        this._variableStates[variableName] = {added:new Date, pageViews:0};
        if(this._autoSave) {
          this._triggerSaveInBrowser()
        }
        return this
      };
      ClientBag.prototype.update = function(variableName, properties) {
        var c = this.get(variableName);
        if(c !== null) {
          c.set(properties)
        }
        if(this._autoSave) {
          this._triggerSaveInBrowser()
        }
        return this
      };
      ClientBag.prototype._triggerSaveInBrowser = function() {
        var cmp = this;
        if(this._triggerSaveTimeout !== null) {
          clearTimeout(this._triggerSaveTimeout)
        }
        this._triggerSaveTimeout = setTimeout(function() {
          cmp.saveInBrowser()
        }, 100)
      };
      ClientBag.prototype.saveInBrowser = function() {
        var forSessionStorage = {state:{}, variables:[]};
        var forLocalStorage = {state:{}, variables:[]};
        for(var variableName in this._variables) {
          var c = this._variables[variableName];
          if(c.getPermanent()) {
            forLocalStorage.variables.push(c);
            if(typeof this._variableStates[variableName] !== "undefined") {
              forLocalStorage.state[variableName] = this._variableStates[variableName]
            }
          }else {
            forSessionStorage.variables.push(c);
            if(typeof this._variableStates[variableName] !== "undefined") {
              forSessionStorage.state[variableName] = this._variableStates[variableName]
            }
          }
        }
        var s = new econda.serialization.JsonSerializer;
        LocalStorage.setItem(this.getStorageKey(), s.serialize(forLocalStorage));
        SessionStorage.setItem(this.getStorageKey(), s.serialize(forSessionStorage));
        this._lastSaved = new Date;
        return this
      };
      ClientBag.prototype.loadFromBrowser = function() {
        var dataFromSessionStorage;
        var dataFromLocalStorage;
        var s = new econda.serialization.JsonSerializer;
        dataFromLocalStorage = LocalStorage.getItem(this.getStorageKey());
        dataFromSessionStorage = SessionStorage.getItem(this.getStorageKey());
        if(dataFromLocalStorage) {
          var objectsFromLocalStorage = s.deserialize(dataFromLocalStorage);
          if(typeof objectsFromLocalStorage.state === "undefined") {
            objectsFromLocalStorage.state = {}
          }
          for(var n = 0;n < objectsFromLocalStorage.variables.length;n++) {
            var variableName = objectsFromLocalStorage.variables[n].getName();
            this._variables[variableName] = objectsFromLocalStorage.variables[n];
            if(typeof objectsFromLocalStorage.state[variableName] === "object" && objectsFromLocalStorage.state[variableName] !== null) {
              this._variableStates[variableName] = objectsFromLocalStorage.state[variableName]
            }
          }
        }
        if(dataFromSessionStorage) {
          var objectsFromSessionStorage = s.deserialize(dataFromSessionStorage);
          if(typeof objectsFromSessionStorage.state === "undefined") {
            objectsFromSessionStorage.state = {}
          }
          for(var n = 0;n < objectsFromSessionStorage.variables.length;n++) {
            var variableName = objectsFromSessionStorage.variables[n].getName();
            this._variables[variableName] = objectsFromSessionStorage.variables[n];
            if(typeof objectsFromSessionStorage.state[variableName] === "object" && objectsFromSessionStorage.state[variableName] !== null) {
              this._variableStates[variableName] = objectsFromSessionStorage.state[variableName]
            }
          }
        }
        return this
      };
      ClientBag.prototype.init = function() {
        this.loadFromBrowser()._initVariablesAndState().saveInBrowser();
        return this
      };
      ClientBag.prototype._initVariablesAndState = function() {
        for(var variableName in this._variables) {
          this._updateVariableState(this._variables[variableName])
        }
        for(var variableName in this._variableStates) {
          if(typeof this._variables[variableName] === "undefined") {
            delete this._variableStates[variableName]
          }
        }
        return this
      };
      ClientBag.prototype._updateVariableState = function(variable) {
        var variableName = variable.getName();
        var state = typeof this._variableStates[variableName] !== "undefined" ? this._variableStates[variableName] : {added:new Date, pageViews:0};
        state.pageViews++;
        var isValid = true;
        var maxPageViews = variable.getPageViewsToLive();
        isValid = isValid && (maxPageViews === null || state.pageViews <= maxPageViews);
        var expires = variable.getExpires();
        isValid = isValid && (expires === null || expires >= new Date);
        if(isValid === false) {
          this._invalidateVariable(variableName)
        }
        return this
      };
      ClientBag.prototype._invalidateVariable = function(variableName) {
        var c = this._variables[variableName];
        switch(c.getInvalidationAction()) {
          case Variable.INVALIDATION_ACTION_REMOVE:
            this.destroy(variableName);
            break;
          case Variable.INVALIDATION_ACTION_KEEP:
            c._setIsValid(false);
            break;
          default:
            throw"Invalid invalidation action.";
        }
        return this
      };
      ClientBag.prototype.destroy = function(variableName) {
        delete this._variables[variableName];
        delete this._variableStates[variableName];
        return this
      };
      return ClientBag
    }();
    storage.ClientBag = ClientBag
  })(storage = econda.storage || (econda.storage = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var env;
  (function(env) {
    var Uri = econda.net.Uri;
    var PageView = function(_super) {
      __extends(PageView, _super);
      function PageView(cfg) {
        var _this = _super.call(this) || this;
        _this._uri = null;
        _this._timestamp = null;
        _this._viewTime = null;
        if(cfg instanceof PageView) {
          return cfg
        }
        _this.initConfig(cfg);
        return _this
      }
      PageView.prototype.getUri = function() {
        return this._uri
      };
      PageView.prototype.setUri = function(uri) {
        this._uri = new Uri(uri);
        return this
      };
      PageView.prototype.getTimestamp = function() {
        return this._timestamp
      };
      PageView.prototype.setTimestamp = function(timestamp) {
        this._timestamp = timestamp;
        return this
      };
      PageView.prototype.getViewTime = function() {
        return this._viewTime
      };
      PageView.prototype.setViewTime = function(milliseconds) {
        this._viewTime = milliseconds;
        return this
      };
      PageView.prototype.getObjectData = function() {
        return{className:"econda.env.PageView", data:{uri:this._uri ? this._uri.toString() : null, timestamp:this._timestamp, viewTime:this._viewTime}}
      };
      PageView.prototype.setObjectData = function(data) {
        if(typeof data === "object" && data !== null) {
          this._uri = data.uri || null;
          this._timestamp = data.timestamp || null;
          this._viewTime = data.viewTime || null
        }
      };
      return PageView
    }(econda.base.BaseClass);
    env.PageView = PageView
  })(env = econda.env || (econda.env = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var env;
  (function(env) {
    var JsonSerializer = econda.serialization.JsonSerializer;
    var Session = function(_super) {
      __extends(Session, _super);
      function Session(cfg) {
        var _this = _super.call(this) || this;
        _this._startDate = null;
        _this._history = [];
        if(cfg instanceof Session) {
          return cfg
        }
        _this.initConfig(cfg);
        return _this
      }
      Session.isSupported = function() {
        if(typeof window.sessionStorage === "undefined") {
          return false
        }
        var result = null;
        try {
          window.sessionStorage.setItem("ectest", "ok");
          result = window.sessionStorage.getItem("ectest")
        }finally {
          return result == "ok"
        }
      };
      Session.prototype.getStartDate = function() {
        return this._startDate
      };
      Session.prototype.getPageViewCount = function() {
        return this.getHistory().length
      };
      Session.prototype.getHistory = function() {
        return this._history
      };
      Session.prototype.getObjectData = function() {
        return{className:"econda.env.Session", data:{startDate:this._startDate, history:this._history}}
      };
      Session.prototype.setObjectData = function(data) {
        this._startDate = data.startDate || null;
        this._history = data.history || []
      };
      Session.prototype.init = function() {
        this._loadFromSessionStorage();
        if(this._startDate === null) {
          this._initNewSession()
        }
        this._addCurrentPageView();
        this._saveToSessionStorage()
      };
      Session.prototype._initNewSession = function() {
        this._startDate = new Date
      };
      Session.prototype._loadFromSessionStorage = function() {
        var data = window.sessionStorage.getItem("econda.env.Session");
        if(data) {
          JsonSerializer.deserialize(data, this);
          return true
        }else {
          return false
        }
      };
      Session.prototype._saveToSessionStorage = function() {
        window.sessionStorage.setItem("econda.env.Session", JsonSerializer.serialize(this))
      };
      Session.prototype._addCurrentPageView = function() {
        var pv = new econda.env.PageView({timestamp:new Date, uri:document.location.href});
        this._history.push(pv);
        return this
      };
      return Session
    }(econda.base.BaseClass);
    env.Session = Session
  })(env = econda.env || (econda.env = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var data = function() {
    function data() {
    }
    data.visitor = null;
    data.session = null;
    data.clientBag = null;
    return data
  }();
  econda.data = data
})(econda || (econda = {}));
econda.data.visitor = new econda.recengine.VisitorProfile;
if(typeof econdaConfig === "object" && typeof econdaConfig.sessionStart !== "undefined" && econdaConfig.sessionStart && econda.env.Session.isSupported()) {
  econda.data.session = new econda.env.Session;
  econda.data.session.init()
}
if(typeof econdaConfig === "object" && typeof econdaConfig.clientBag !== "undefined" && econdaConfig.clientBag === true) {
  econda.data.clientBag = (new econda.storage.ClientBag).init()
}
var econda;
(function(econda) {
  var recengine;
  (function(recengine) {
    var context;
    (function(context) {
      var CategoryReference = function(_super) {
        __extends(CategoryReference, _super);
        function CategoryReference(cfg) {
          if(cfg === void 0) {
            cfg = null
          }
          var _this = _super.call(this) || this;
          _this.type = "productcategory";
          _this.id = null;
          _this.variant = null;
          _this.path = null;
          _this.initConfig(cfg);
          return _this
        }
        CategoryReference.prototype.getType = function() {
          return this.type
        };
        CategoryReference.prototype.setType = function(type) {
          this.type = type;
          return this
        };
        CategoryReference.prototype.getId = function() {
          return this.id
        };
        CategoryReference.prototype.setId = function(id) {
          this.id = id;
          return this
        };
        CategoryReference.prototype.getVariant = function() {
          return this.variant
        };
        CategoryReference.prototype.setVariant = function(key) {
          this.variant = key;
          return this
        };
        CategoryReference.prototype.getPath = function() {
          return this.path
        };
        CategoryReference.prototype.setPath = function(path) {
          if(typeof path == "string") {
            path = {"path":path, "delimiter":"/"}
          }
          if(typeof path == "object" && typeof path.path != "undefined") {
            if(typeof path.delimiter == "undefined") {
              path.delimiter = "/"
            }
            path = path.path.split(path.delimiter)
          }
          this.path = path;
          return this
        };
        return CategoryReference
      }(econda.base.BaseClass);
      context.CategoryReference = CategoryReference
    })(context = recengine.context || (recengine.context = {}))
  })(recengine = econda.recengine || (econda.recengine = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var recengine;
  (function(recengine) {
    var context;
    (function(context) {
      var CategoryReference = econda.recengine.context.CategoryReference;
      var ProductReference = econda.recengine.context.ProductReference;
      var VisitorHistory = econda.recengine.history.VisitorHistory;
      var Context = function(_super) {
        __extends(Context, _super);
        function Context(cfg) {
          var _this = _super.call(this) || this;
          _this.products = [];
          _this.visitorHistory = null;
          _this.categories = [];
          _this.attributes = {};
          _this.profileProperties = {};
          _this.visitorId = null;
          _this.customerId = null;
          _this.recipientId = null;
          _this.userId = null;
          _this.email = null;
          _this.emailHash = null;
          _this.productsExcluded = [];
          _this._appendVisitorData = null;
          var cfg = cfg || {};
          if(cfg instanceof Context) {
            return cfg
          }else {
            if(typeof cfg["appendVisitorData"] != "undefined") {
              _this._appendVisitorData = cfg["appendVisitorData"] === true;
              delete cfg["appendVisitorData"]
            }
            _this.initConfig(cfg)
          }
          if(_this._appendVisitorData === true || _this._appendVisitorData === null && (typeof econdaConfig.crosssellAppendVisitorData === "undefined" || econdaConfig.crosssellAppendVisitorData === true)) {
            _this._doAppendVisitorData()
          }
          return _this
        }
        Context.prototype.getProducts = function() {
          return this.products
        };
        Context.prototype.setProducts = function(products) {
          this.products = [];
          this.addProducts(products);
          return this
        };
        Context.prototype.addProducts = function(products) {
          return this.addArray("products", products, ProductReference, {itemFilter:this._productReferenceInputFilter})
        };
        Context.prototype.addProduct = function(product) {
          return this.addProducts(product)
        };
        Context.prototype.getVisitorHistory = function() {
          return this.visitorHistory
        };
        Context.prototype.setVisitorHistory = function(visitorHistory) {
          this.visitorHistory = new VisitorHistory(visitorHistory)
        };
        Context.prototype.getCategories = function() {
          return this.categories
        };
        Context.prototype.setCategories = function(categories) {
          return this.setArray("categories", categories, CategoryReference)
        };
        Context.prototype.addCategories = function(categories) {
          return this.addArray("categories", categories, CategoryReference)
        };
        Context.prototype.addCategory = function(category) {
          return this.addCategories(category)
        };
        Context.prototype.getAttributes = function() {
          return this.attributes
        };
        Context.prototype.setAttributes = function(attributes, value) {
          if(value === void 0) {
            value = null
          }
          this.attributes = {};
          return this.addAttributes(attributes, value)
        };
        Context.prototype.addAttributes = function(attributes, value) {
          if(value === void 0) {
            value = null
          }
          if(typeof attributes == "string") {
            this.attributes[attributes] = value
          }else {
            for(var property in attributes) {
              this.attributes[property] = attributes[property]
            }
          }
          return this
        };
        Context.prototype.setProfileProperties = function(profileProperties) {
          this.profileProperties = profileProperties
        };
        Context.prototype.getProfileProperties = function() {
          return this.profileProperties
        };
        Context.prototype.setVisitorId = function(visitorId) {
          this.visitorId = visitorId;
          return this
        };
        Context.prototype.getVisitorId = function() {
          return this.visitorId
        };
        Context.prototype.setCustomerId = function(customerId) {
          this.customerId = customerId;
          return this
        };
        Context.prototype.getCustomerId = function() {
          return this.customerId
        };
        Context.prototype.setRecipientId = function(recipientId) {
          this.recipientId = recipientId;
          return this
        };
        Context.prototype.getRecipientId = function() {
          return this.recipientId
        };
        Context.prototype.setUserId = function(userId) {
          this.userId = userId;
          return this
        };
        Context.prototype.getUserId = function() {
          return this.userId
        };
        Context.prototype.setEmail = function(email) {
          this.email = email;
          return this
        };
        Context.prototype.getEmail = function() {
          return this.email
        };
        Context.prototype.setEmailHash = function(emailHash) {
          this.emailHash = emailHash;
          return this
        };
        Context.prototype.getEmailHash = function() {
          return this.emailHash
        };
        Context.prototype.getProductsExcluded = function() {
          return this.productsExcluded
        };
        Context.prototype.setProductsExcluded = function(products) {
          return this.setArray("productsExcluded", products, ProductReference, {itemFilter:this._productReferenceInputFilter})
        };
        Context.prototype.addProductsExcluded = function(products) {
          return this.addArray("productsExcluded", products, ProductReference, {itemFilter:this._productReferenceInputFilter})
        };
        Context.prototype._productReferenceInputFilter = function(product) {
          if(product instanceof ProductReference) {
            return product
          }else {
            return{id:product.id || null, sku:product.sku || null}
          }
        };
        Context.prototype._doAppendVisitorData = function() {
          try {
            var visitorProfile = econda.data.visitor;
            this.setVisitorId(visitorProfile.getVisitorId());
            this.setCustomerId(visitorProfile.getCustomerId());
            this.setUserId(visitorProfile.getUserId());
            this.setRecipientId(visitorProfile.getRecipientId());
            this.setEmail(visitorProfile.getEmail());
            this.setEmailHash(visitorProfile.getEmailHash());
            this.setProfileProperties(visitorProfile.getProperties());
            this.setVisitorHistory(visitorProfile.getHistory())
          }catch(e) {
            econda.debug.error("Could not append visitor profile data to cross sell request due to an internal exception: " + e)
          }
        };
        return Context
      }(econda.base.BaseClass);
      context.Context = Context
    })(context = recengine.context || (recengine.context = {}))
  })(recengine = econda.recengine || (econda.recengine = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var util;
  (function(util) {
    var DateUtils = function() {
      function DateUtils() {
      }
      DateUtils.isDate = function(obj) {
        return"[object Date]" == Object.prototype.toString.call(obj)
      };
      DateUtils.toUtcIsoString = function(date) {
        function pad(number) {
          if(number < 10) {
            return"0" + number
          }
          return"" + number
        }
        if(date instanceof Date) {
          return date.getUTCFullYear() + "-" + pad(date.getUTCMonth() + 1) + "-" + pad(date.getUTCDate()) + "T" + pad(date.getUTCHours()) + ":" + pad(date.getUTCMinutes()) + ":" + pad(date.getUTCSeconds()) + "Z"
        }
        return null
      };
      return DateUtils
    }();
    util.DateUtils = DateUtils
  })(util = econda.util || (econda.util = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var env;
  (function(env) {
    var UserAgent = function() {
      function UserAgent() {
      }
      UserAgent.getUserAgent = function() {
        if(this.userAgent == null) {
          this.userAgent = window.navigator.userAgent
        }
        return this.userAgent
      };
      UserAgent.getName = function() {
        if(this._name == null) {
          this.init()
        }
        return this._name
      };
      UserAgent.getVersion = function() {
        if(this._version == null) {
          this.init()
        }
        return this._version
      };
      UserAgent.isMobile = function() {
        if(this._isMobile == null) {
          this.init()
        }
        return this._isMobile
      };
      UserAgent.init = function() {
        this.initNameAndVersion();
        this.initIsMobile()
      };
      UserAgent.initIsMobile = function() {
        this._isMobile = navigator.userAgent.match(/mobile/i) != null
      };
      UserAgent.initNameAndVersion = function() {
        var uas = this.getUserAgent();
        if(this._name == null && /;\s*MSIE\s+(\d+\.\d+)*/.test(uas)) {
          this._name = this.INTERNET_EXPLORER;
          this._version = +RegExp.$1
        }
        if(this._name == null && /Firefox\s*\/\s*(\d+\.\d+)*/.test(uas)) {
          this._name = this.FIREFOX;
          this._version = +RegExp.$1
        }
        if(this._name == null && /Chrome\s*\/\s*(\d+\.\d+)*/.test(uas)) {
          this._name = this.CHROME;
          this._version = +RegExp.$1
        }
        if(this._name == null && /Version\s*\/\s*(\d+\.\d+)*.*Safari/.test(uas)) {
          this._name = this.SAFARI;
          this._version = +RegExp.$1
        }
      };
      UserAgent.INTERNET_EXPLORER = "internet explorer";
      UserAgent.CHROME = "chrome";
      UserAgent.FIREFOX = "firefox";
      UserAgent.SAFARI = "safari";
      UserAgent.userAgent = null;
      UserAgent._name = null;
      UserAgent._version = null;
      UserAgent._isMobile = null;
      return UserAgent
    }();
    env.UserAgent = UserAgent
  })(env = econda.env || (econda.env = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var ajax;
  (function(ajax) {
    var Response = function() {
      function Response() {
        this.status = null;
        this.responseText = null;
        this.isError = null
      }
      return Response
    }();
    ajax.Response = Response
  })(ajax = econda.ajax || (econda.ajax = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var ajax;
  (function(ajax) {
    var writer;
    (function(writer) {
      var JsonWriter = function(_super) {
        __extends(JsonWriter, _super);
        function JsonWriter() {
          var _this = _super !== null && _super.apply(this, arguments) || this;
          _this._data = null;
          return _this
        }
        JsonWriter.prototype.setData = function(data) {
          this._data = data;
          return this
        };
        JsonWriter.prototype.getHeaders = function() {
          return{"Content-Type":"application/json; charset=UTF-8"}
        };
        JsonWriter.prototype.getBody = function() {
          var arr = [], str;
          str = econda.util.Json.stringify(this._data);
          return str
        };
        return JsonWriter
      }(econda.base.BaseClass);
      writer.JsonWriter = JsonWriter
    })(writer = ajax.writer || (ajax.writer = {}))
  })(ajax = econda.ajax || (econda.ajax = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var ajax;
  (function(ajax) {
    var writer;
    (function(writer) {
      var FormEncodedWriter = function(_super) {
        __extends(FormEncodedWriter, _super);
        function FormEncodedWriter() {
          var _this = _super !== null && _super.apply(this, arguments) || this;
          _this._data = null;
          return _this
        }
        FormEncodedWriter.prototype.setData = function(data) {
          this._data = data;
          return this
        };
        FormEncodedWriter.prototype.getHeaders = function() {
          return{"Content-Type":"application/x-www-form-urlencoded; charset=UTF-8"}
        };
        FormEncodedWriter.prototype.getBody = function() {
          var arr = [], str, data = this._data;
          for(var name in data) {
            if(econda.util.ArrayUtils.isArray(data[name])) {
              for(var i = 0;i < data[name].length;i++) {
                arr.push(name + "=" + encodeURIComponent(data[name][i]))
              }
            }else {
              arr.push(name + "=" + encodeURIComponent(data[name]))
            }
          }
          str = arr.join("&");
          return str
        };
        return FormEncodedWriter
      }(econda.base.BaseClass);
      writer.FormEncodedWriter = FormEncodedWriter
    })(writer = ajax.writer || (ajax.writer = {}))
  })(ajax = econda.ajax || (econda.ajax = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var ajax;
  (function(ajax) {
    var reader;
    (function(reader) {
      var JsonReader = function() {
        function JsonReader() {
        }
        JsonReader.prototype.read = function(xhrResponseText) {
          var response = null;
          if(xhrResponseText) {
            try {
              response = econda.util.Json.parse(xhrResponseText)
            }catch(error) {
              response = null
            }
          }
          return response
        };
        return JsonReader
      }();
      reader.JsonReader = JsonReader
    })(reader = ajax.reader || (ajax.reader = {}))
  })(ajax = econda.ajax || (econda.ajax = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var ajax;
  (function(ajax) {
    var transport;
    (function(transport) {
      var Response = econda.ajax.Response;
      var Post = function() {
        function Post() {
          this._initialized = false;
          this._allHeaders = {}
        }
        Post.prototype.setRequest = function(request) {
          this._request = request
        };
        Post.prototype.getRequest = function() {
          return this._request
        };
        Post.prototype.hasHeaders = function() {
          if(Object.keys) {
            return Object.keys(this._allHeaders).length > 0
          }else {
            var keys = [], k;
            for(k in this._allHeaders) {
              if(Object.prototype.hasOwnProperty.call(this._allHeaders, k)) {
                keys.push(k)
              }
            }
            return keys.length > 0
          }
        };
        Post.prototype.init = function() {
          var cmp = this;
          this.initWriterInstance();
          this.initXmlHttpRequestInstance();
          this.appendCallbacks();
          this._xhr.open("POST", this.getRequestUriWithParams(), true);
          if(this._request.isXDomainRequest() && this._request.getWithCredentials() && this._xhr instanceof XMLHttpRequest) {
            this._xhr.withCredentials = true
          }
          this.appendHeaders();
          this._initialized = true
        };
        Post.prototype.send = function() {
          var cmp = this;
          if(!this._initialized) {
            this.init()
          }
          setTimeout(function() {
            cmp._xhr.send(cmp.getEncodedRequestData());
            var callback = cmp._request.getAfterSend();
            if(callback) {
              callback()
            }
          }, 20)
        };
        Post.prototype.initWriterInstance = function() {
          var data = this._request.getData();
          var writer = this._request.getWriter();
          if(writer == null && data) {
            if(typeof data == "object") {
              writer = new econda.ajax.writer.FormEncodedWriter
            }
          }
          if(writer) {
            writer.setData(this._request.getData());
            this._writer = writer
          }
        };
        Post.prototype.appendHeaders = function() {
          var headers = {"Content-Type":"text/plain"};
          if(this._request.isXDomainRequest() == false) {
            headers["X-Requested-With"] = "XMLHttpRequest"
          }
          if(this._writer) {
            var headersFromWriter = this._writer.getHeaders();
            if(headersFromWriter) {
              for(var name in headersFromWriter) {
                headers[name] = headersFromWriter[name]
              }
            }
          }
          var headersFromRequest = this._request.getHeaders();
          if(headersFromRequest) {
            for(var name in headersFromRequest) {
              headers[name] = headersFromRequest[name]
            }
          }
          this._allHeaders = headers;
          if(typeof this._xhr.setRequestHeader != "undefined") {
            for(var name in headers) {
              this._xhr.setRequestHeader(name, headers[name])
            }
          }
          return this
        };
        Post.prototype.getEncodedRequestData = function() {
          var ret = "";
          if(this._writer) {
            ret = this._writer.getBody()
          }else {
            ret = "" + this._request.getData()
          }
          return ret
        };
        Post.prototype.isSupportedRequest = function() {
          this.initXmlHttpRequestInstance();
          var isXDomainRequest = this._request.isXDomainRequest();
          var ok = true;
          ok = ok && (isXDomainRequest == false || this._isOldIE() == false);
          ok = ok && this.hasHeaders() == false || typeof this._xhr["setRequestHeader"] != "undefined" && this._isOldIE() == false;
          ok = ok && (this._request.getWithCredentials() == false || this._xhr instanceof XMLHttpRequest);
          return ok
        };
        Post.prototype._isOldIE = function() {
          var ua = econda.env.UserAgent;
          if(ua.getName() != ua.INTERNET_EXPLORER) {
            return false
          }
          if(ua.getVersion() >= 8) {
            return false
          }
          return true
        };
        Post.prototype.initXmlHttpRequestInstance = function() {
          var isXDomainRequest = this._request.isXDomainRequest();
          if(!this._xhr) {
            if(isXDomainRequest && typeof window["XDomainRequest"] != "undefined") {
              this._xhr = new window["XDomainRequest"]
            }else {
              if(typeof XMLHttpRequest != "undefined") {
                this._xhr = new XMLHttpRequest
              }else {
                this._xhr = new ActiveXObject("Microsoft.XMLHTTP")
              }
            }
          }
          this._xhr.timeout = this._request.getTimeoutMilliseconds()
        };
        Post.prototype.getRequestUriWithParams = function() {
          var uri = this._request.getUri();
          var params = this._request.getParams();
          return uri.clone().appendParams(params).toString()
        };
        Post.prototype.appendCallbacks = function() {
          var cmp = this;
          this._xhr.onreadystatechange = function() {
            cmp.onReadyStateChange.apply(cmp, arguments)
          };
          return this
        };
        Post.prototype.onReadyStateChange = function() {
          var xhr = this._xhr;
          var request = this._request;
          if(xhr.readyState == 4) {
            var result = new Response;
            result.responseText = xhr.responseText;
            result.status = xhr.status;
            result.isError = xhr.status != 200;
            request.handleResponse(result)
          }
        };
        return Post
      }();
      transport.Post = Post
    })(transport = ajax.transport || (ajax.transport = {}))
  })(ajax = econda.ajax || (econda.ajax = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var ajax;
  (function(ajax) {
    var transport;
    (function(transport) {
      var Response = econda.ajax.Response;
      var Get = function() {
        function Get() {
          this._initialized = false;
          this._allHeaders = {}
        }
        Get.prototype.setRequest = function(request) {
          this._request = request
        };
        Get.prototype.getRequest = function() {
          return this._request
        };
        Get.prototype.isSupportedRequest = function() {
          return true
        };
        Get.prototype.hasHeaders = function() {
          if(Object.keys) {
            return Object.keys(this._allHeaders).length > 0
          }else {
            var keys = [], k;
            for(k in this._allHeaders) {
              if(Object.prototype.hasOwnProperty.call(this._allHeaders, k)) {
                keys.push(k)
              }
            }
            return keys.length > 0
          }
        };
        Get.prototype.init = function() {
          var cmp = this;
          this._xhr = this.createXmlHttpRequestInstance();
          this._xhr.onreadystatechange = function() {
            cmp.onReadyStateChange.apply(cmp, arguments)
          };
          this._xhr.open("GET", this.getRequestUriWithParams(), true);
          this.appendHeaders();
          this._initialized = true
        };
        Get.prototype.appendHeaders = function() {
          var headers = {"Content-Type":"text/plain"};
          if(this._request.isXDomainRequest() == false) {
            headers["X-Requested-With"] = "XMLHttpRequest"
          }
          var headersFromRequest = this._request.getHeaders();
          if(headersFromRequest) {
            for(var name in headersFromRequest) {
              headers[name] = headersFromRequest[name]
            }
          }
          this._allHeaders = headers;
          if(typeof this._xhr.setRequestHeader != "undefined") {
            for(var name in headers) {
              this._xhr.setRequestHeader(name, headers[name])
            }
          }
          return this
        };
        Get.prototype.send = function(request) {
          var cmp = this;
          if(typeof request != "undefined") {
            this.setRequest(request)
          }
          if(!this._initialized) {
            this.init()
          }
          setTimeout(function() {
            cmp._xhr.send()
          }, 20)
        };
        Get.prototype.createXmlHttpRequestInstance = function() {
          var xhr = null;
          if(typeof XMLHttpRequest != "undefined") {
            xhr = new XMLHttpRequest
          }else {
            xhr = new ActiveXObject("Microsoft.XMLHTTP")
          }
          return xhr
        };
        Get.prototype.getRequestUriWithParams = function() {
          var uri = this._request.getUri();
          var params = this._request.getParams();
          return uri.clone().appendParams(params).toString()
        };
        Get.prototype.onReadyStateChange = function() {
          var xhr = this._xhr;
          var request = this._request;
          if(xhr.readyState == 4) {
            var result = new Response;
            result.responseText = xhr.responseText;
            result.status = xhr.status;
            result.isError = xhr.status != 200;
            request.handleResponse(result)
          }
        };
        return Get
      }();
      transport.Get = Get
    })(transport = ajax.transport || (ajax.transport = {}))
  })(ajax = econda.ajax || (econda.ajax = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var ajax;
  (function(ajax) {
    var transport;
    (function(transport) {
      var Response = econda.ajax.Response;
      var Uri = econda.net.Uri;
      var WindowName = function() {
        function WindowName() {
          this._iframe = null;
          this._request = null;
          this._instanceIndex = null;
          this._state = 0;
          this._instanceIndex = WindowName._instanceCount++
        }
        WindowName.setBlankUri = function(uri) {
          econdaConfig.blankUri = new Uri(uri)
        };
        WindowName.getBlankUri = function() {
          if(typeof econdaConfig != "undefined") {
            var c = econdaConfig;
            if(typeof c.blankUri != "undefined") {
              return new Uri(c.blankUri)
            }
          }
          return new Uri(WindowName.defaultBlankUri)
        };
        WindowName.prototype.setRequest = function(request) {
          this._request = request
        };
        WindowName.prototype.init = function() {
        };
        WindowName.prototype.send = function(request) {
          var uri = this._request.getUri();
          uri = uri.appendParams({windowname:"true"});
          uri = uri.appendParams(this._request.getParams());
          this.setupIFrame();
          econda.debug.log("Sending request using window.name transport to uri: " + uri);
          this.navigateIFrameToTarget(uri)
        };
        WindowName.prototype.isSupportedRequest = function() {
          return true
        };
        WindowName.prototype.navigateIFrameToTarget = function(uri) {
          this._iframe.contentWindow.location.href = uri.toString();
          this._state = WindowName.STATE_LOADING
        };
        WindowName.prototype.navigateIFrameToOwnHost = function() {
          this._iframe.contentWindow.location.href = WindowName.getBlankUri().toString()
        };
        WindowName.prototype.handleOnLoad = function() {
          switch(this._state) {
            case WindowName.STATE_LOADING:
              this._state = WindowName.STATE_LOADED;
              this.navigateIFrameToOwnHost();
              break;
            case WindowName.STATE_LOADED:
              var response = new Response;
              try {
                response.responseText = this._iframe.contentWindow.name;
                response.status = 200;
                response.isError = false
              }catch(e) {
                econda.debug.error("Could not read content from iframe for request to " + this._request.getUri().toString(), {exception:e, iframe:this._iframe});
                response.responseText = "";
                response.status = 0;
                response.isError = true
              }
              this._request.handleResponse(response);
              this.removeIFrame();
              break
          }
        };
        WindowName.prototype.removeIFrame = function() {
          this._iframe.parentElement.removeChild(this._iframe)
        };
        WindowName.prototype.setupIFrame = function() {
          var cmp = this;
          var body = document.getElementsByTagName("body")[0];
          var iframe;
          iframe = document.createElement("iframe");
          iframe.style.display = "none";
          iframe.style.width = "1px";
          iframe.style.height = "1px";
          var onloadHandler = function() {
            cmp.handleOnLoad.apply(cmp, arguments)
          };
          if(typeof iframe.attachEvent === "function") {
            iframe.attachEvent("onload", onloadHandler)
          }else {
            iframe.onload = onloadHandler
          }
          body.appendChild(iframe);
          this._iframe = iframe;
          this._state = WindowName.STATE_INITIALIZED
        };
        WindowName.defaultBlankUri = "/favicon.ico";
        WindowName._instanceCount = 0;
        WindowName.STATE_UNINITIALIZED = 0;
        WindowName.STATE_INITIALIZED = 1;
        WindowName.STATE_LOADING = 2;
        WindowName.STATE_LOADED = 3;
        return WindowName
      }();
      transport.WindowName = WindowName
    })(transport = ajax.transport || (ajax.transport = {}))
  })(ajax = econda.ajax || (econda.ajax = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var ajax;
  (function(ajax) {
    var UserAgent = econda.env.UserAgent;
    var Uri = econda.net.Uri;
    var Request = function(_super) {
      __extends(Request, _super);
      function Request(cfg) {
        var _this = _super.call(this) || this;
        _this._uri = null;
        _this._method = "get";
        _this._params = null;
        _this._headers = {};
        _this._data = null;
        _this._writer = null;
        _this._reader = null;
        _this._success = null;
        _this._error = null;
        _this._callback = null;
        _this._afterSend = null;
        _this.timeoutMilliseconds = 0;
        _this._withCredentials = false;
        _this._transport = null;
        _this._inititalized = false;
        if(cfg instanceof Request) {
          return cfg
        }else {
          _this.initConfig(cfg)
        }
        return _this
      }
      Request.prototype.getUri = function() {
        return this._uri
      };
      Request.prototype.setUri = function(uri) {
        this._uri = new Uri(uri);
        return this
      };
      Request.prototype.getMethod = function() {
        return this._method
      };
      Request.prototype.setMethod = function(method) {
        this._method = method.toLocaleLowerCase();
        return this
      };
      Request.prototype.getParams = function() {
        return this._params
      };
      Request.prototype.setParams = function(params) {
        this._params = params;
        return this
      };
      Request.prototype.addParams = function(data, value) {
        if(value === void 0) {
          value = null
        }
        if(typeof data == "string") {
          this._params[data] = value
        }else {
          for(var name in data) {
            this._params[name] = data[name]
          }
        }
        return this
      };
      Request.prototype.getHeaders = function() {
        return this._headers
      };
      Request.prototype.setHeaders = function(headers, value) {
        this._headers = {};
        return this.addHeaders.apply(this, arguments)
      };
      Request.prototype.addHeaders = function(headers, value) {
        if(typeof headers == "string") {
          this._headers[headers] = value
        }else {
          for(var name in headers) {
            this._headers[name] = headers[name]
          }
        }
        return this
      };
      Request.prototype.hasHeaders = function() {
        if(Object.keys) {
          return Object.keys(this._headers).length > 0
        }else {
          var k;
          for(k in this._headers) {
            if(Object.prototype.hasOwnProperty.call(this._headers, k)) {
              return true
            }
          }
          return false
        }
      };
      Request.prototype.getData = function() {
        return this._data
      };
      Request.prototype.setData = function(data) {
        this._data = data;
        return this
      };
      Request.prototype.getWriter = function() {
        return this._writer
      };
      Request.prototype.setWriter = function(writer) {
        if(typeof writer == "string") {
          switch(writer) {
            case "form-encoded":
              this._writer = new econda.ajax.writer.FormEncodedWriter;
              break;
            case "json":
              this._writer = new econda.ajax.writer.JsonWriter;
              break;
            default:
              throw"Unsupported writer: " + writer;
          }
        }else {
          this._writer = writer
        }
        return this
      };
      Request.prototype.getReader = function() {
        return this._reader
      };
      Request.prototype.setReader = function(reader) {
        if(typeof reader == "string") {
          switch(reader) {
            case "json":
              this._reader = new econda.ajax.reader.JsonReader;
              break;
            default:
              throw"Unsupported response reader: " + reader;
          }
        }else {
          this._reader = reader
        }
        return this
      };
      Request.prototype.getSuccess = function() {
        return this._success
      };
      Request.prototype.setSuccess = function(callback) {
        this._success = callback;
        return this
      };
      Request.prototype.getError = function() {
        return this._error
      };
      Request.prototype.setError = function(callback) {
        this._error = callback;
        return this
      };
      Request.prototype.getCallback = function() {
        return this._callback
      };
      Request.prototype.setCallback = function(callback) {
        this._callback = callback;
        return this
      };
      Request.prototype.getAfterSend = function() {
        return this._afterSend
      };
      Request.prototype.setAfterSend = function(callback) {
        this._afterSend = callback;
        return this
      };
      Request.prototype.getTimeoutMilliseconds = function() {
        return this.timeoutMilliseconds
      };
      Request.prototype.setTimeoutMilliseconds = function(seconds) {
        this.timeoutMilliseconds = seconds;
        return this
      };
      Request.prototype.setWithCredentials = function(withCredentials) {
        this._withCredentials = withCredentials;
        return this
      };
      Request.prototype.getWithCredentials = function() {
        return this._withCredentials
      };
      Request.prototype.setTransport = function(transport) {
        if(typeof transport === "string") {
          switch(transport.toLowerCase()) {
            case "post":
              this._transport = new econda.ajax.transport.Post;
              break;
            case "get":
              this._transport = new econda.ajax.transport.Get;
              break;
            case "windowname":
              this._transport = new econda.ajax.transport.WindowName;
              break;
            default:
              throw new Error("unknown transport: " + transport);
          }
        }else {
          this._transport = transport
        }
        this._transport.setRequest(this);
        return this
      };
      Request.prototype.getTransport = function() {
        return this._transport
      };
      Request.prototype.init = function() {
        if(this.getUri() == null) {
          econda.debug.error("Trying to send request, but request has empty uri.")
        }
        if(!this._transport) {
          this.autoDetectAndSetTransport()
        }
        this._transport.init();
        this._inititalized = true
      };
      Request.prototype.send = function() {
        if(this._inititalized == false) {
          this.init()
        }
        this._transport.send(this)
      };
      Request.prototype.handleResponse = function(response) {
        var reader = this.getReader();
        if(reader) {
          response.data = reader.read(response.responseText)
        }else {
          response.data = response.responseText
        }
        if(response.isError) {
          if(this._error) {
            this._error.call(this, response)
          }
        }else {
          if(this._success) {
            this._success.apply(this, [response.data])
          }
        }
        if(this._callback) {
          this._callback.call(this, response)
        }
      };
      Request.prototype.autoDetectAndSetTransport = function() {
        if(this._transport) {
          return
        }
        var transport;
        switch(this._method) {
          case "get":
            if(this.isXDomainRequest() && UserAgent.getName() == UserAgent.INTERNET_EXPLORER && UserAgent.getVersion() < 10) {
              transport = new econda.ajax.transport.WindowName
            }else {
              transport = new econda.ajax.transport.Get
            }
            break;
          case "post":
            transport = new econda.ajax.transport.Post;
            break;
          default:
            throw"Unsupported request method: " + this._method;
        }
        this.setTransport(transport)
      };
      Request.prototype.isSupportedRequest = function() {
        if(!this._transport) {
          this.autoDetectAndSetTransport()
        }
        return this._transport.isSupportedRequest()
      };
      Request.prototype.isXDomainRequest = function() {
        var uri = this._uri;
        var isXDomain = false;
        var matches = uri.match(/^https?\:\/\/([^\/:?#]+)(?:[\/:?#]|$)/i);
        var domain = matches && matches[1];
        if(domain && domain != location.host) {
          isXDomain = true
        }
        return isXDomain
      };
      return Request
    }(econda.base.BaseClass);
    ajax.Request = Request
  })(ajax = econda.ajax || (econda.ajax = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var ajax;
  (function(ajax) {
    var reader;
    (function(reader) {
      var TextReader = function() {
        function TextReader() {
        }
        TextReader.prototype.read = function(xhrResponse) {
          return xhrResponse
        };
        return TextReader
      }();
      reader.TextReader = TextReader
    })(reader = ajax.reader || (ajax.reader = {}))
  })(ajax = econda.ajax || (econda.ajax = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var ajax;
  (function(ajax) {
    var Ajax = function() {
      function Ajax() {
      }
      Ajax.createRequest = function(config) {
        return new econda.ajax.Request(config)
      };
      Ajax.request = function(config) {
        var rec = this.createRequest(config);
        rec.send();
        return rec
      };
      return Ajax
    }();
    ajax.Ajax = Ajax
  })(ajax = econda.ajax || (econda.ajax = {}))
})(econda || (econda = {}));
econda["Ajax"] = econda.ajax.Ajax;
var econda;
(function(econda) {
  var recengine;
  (function(recengine) {
    var proxy;
    (function(proxy) {
      var Response = econda.recengine.response.Response;
      var ProductAddToCartEvent = econda.recengine.event.ProductAddToCartEvent;
      var ProductBuyEvent = econda.recengine.event.ProductBuyEvent;
      var ProductViewEvent = econda.recengine.event.ProductViewEvent;
      var DateUtils = econda.util.DateUtils;
      var AjaxProxy = function(_super) {
        __extends(AjaxProxy, _super);
        function AjaxProxy(cfg) {
          if(cfg === void 0) {
            cfg = null
          }
          var _this = _super.call(this) || this;
          _this.uri = null;
          _this.request = null;
          _this._ajaxRequest = null;
          if(cfg instanceof AjaxProxy) {
            return cfg
          }
          _this.initConfig(cfg);
          return _this
        }
        AjaxProxy.prototype.getUri = function() {
          return this.uri
        };
        AjaxProxy.prototype.setUri = function(uri) {
          this.uri = uri
        };
        AjaxProxy.prototype.setRequest = function(request) {
          this.request = request
        };
        AjaxProxy.prototype.getRequest = function() {
          return this.request
        };
        AjaxProxy.prototype.getAjaxRequest = function() {
          return this._ajaxRequest
        };
        AjaxProxy.detectProtocol = function() {
          return typeof location.protocol === "string" && location.protocol === "https:" ? "https" : "http"
        };
        AjaxProxy.prototype._isCookieRequired = function() {
          var cookieRequired = false;
          cookieRequired = cookieRequired || this.request.getAutoContext();
          return cookieRequired
        };
        AjaxProxy.prototype.send = function() {
          var req = this.request;
          var cmp = this;
          var protocol = AjaxProxy.detectProtocol();
          var uri = this.uri;
          if(!uri) {
            uri = [protocol, "://widgets.crosssell.info/eps/crosssell/recommendations/", req.getAccountId(), ".do"].join("")
          }
          var params = this.getRecommendationServiceFormFieldParametersFromRequest();
          var request = this._ajaxRequest = econda.ajax.Ajax.createRequest({uri:uri, method:"post", writer:"form-encoded", reader:"json", data:params, withCredentials:cmp._isCookieRequired(), timeoutMilliseconds:req.getTimeoutMilliseconds(), success:function(ajaxResponse) {
            cmp.handleSuccess(ajaxResponse)
          }, error:function() {
            cmp.handleError()
          }});
          request.init();
          if(request.isSupportedRequest()) {
            request.send()
          }else {
            var request = this._ajaxRequest = econda.ajax.Ajax.createRequest({uri:uri, method:"get", reader:"json", params:params, withCredentials:cmp._isCookieRequired(), timeoutMilliseconds:req.getTimeoutMilliseconds(), success:function(ajaxResponse) {
              cmp.handleSuccess(ajaxResponse)
            }, error:function() {
              cmp.handleError()
            }});
            request.send()
          }
        };
        AjaxProxy.prototype.handleError = function() {
          var response = new Response({isError:true});
          this.request.handleResponse(response)
        };
        AjaxProxy.prototype.handleSuccess = function(responseData) {
          if(!responseData) {
            this.handleError();
            return
          }
          if(typeof responseData.products === "undefined") {
            responseData.products = responseData.items || [];
            delete responseData.items
          }
          if(typeof responseData.widgetdetails !== "undefined") {
            responseData.widgetDetails = responseData.widgetdetails;
            delete responseData.widgetdetails
          }
          if(typeof responseData.start !== "undefined") {
            responseData.startIndex = responseData.start;
            delete responseData.start
          }
          if(typeof responseData.end !== "undefined") {
            responseData.endIndex = responseData.end;
            delete responseData.end
          }
          delete responseData.size;
          var response = new Response(responseData);
          response.setRequest(this.getRequest());
          this.request.handleResponse(response)
        };
        AjaxProxy.prototype.getRecommendationServiceFormFieldParametersFromRequest = function() {
          var request = this.request;
          var params = {};
          params["wid"] = request.getWidgetId();
          params["aid"] = request.getAccountId();
          params["type"] = request.getType();
          params["start"] = request.getStartIndex();
          params["widgetdetails"] = true;
          var csize = request.getChunkSize();
          if(csize) {
            params["csize"] = csize
          }
          var productIds = [];
          if(request.getAutoContext()) {
            productIds = this._getAutoContextParamValue()
          }
          var context = request.getContext();
          if(context) {
            var products = context.getProducts();
            for(var n = 0;n < products.length;n++) {
              if(products[n].getSku()) {
                productIds.push("sku:" + products[n].getSku())
              }else {
                productIds.push(products[n].getId())
              }
            }
            var categories = context.getCategories();
            if(categories.length > 0) {
              for(var i = 0;i < categories.length;i++) {
                var cat = categories[i];
                var prefix = "ctxcat";
                if(i > 0) {
                  prefix = prefix + i
                }
                var typeParam = prefix + ".ct";
                params[typeParam] = cat.getType();
                if(cat.getId()) {
                  var byIdParam = prefix + ".cid";
                  params[byIdParam] = cat.getId()
                }
                if(cat.getPath()) {
                  var pathAsArrayParam = prefix + ".paa";
                  params[pathAsArrayParam] = cat.getPath()
                }
                if(cat.getVariant()) {
                  var variantParam = prefix + ".pv";
                  params[variantParam] = cat.getVariant()
                }
              }
            }
            var attributes = context.getAttributes();
            for(var name in attributes) {
              switch(attributes[name]) {
                case null:
                  params["ctxcustom." + name] = "";
                  break;
                default:
                  params["ctxcustom." + name] = "" + attributes[name]
              }
            }
            if(context.getVisitorId()) {
              params["emvid"] = context.getVisitorId()
            }
            if(context.getCustomerId()) {
              params["emcid"] = context.getCustomerId()
            }
            if(context.getRecipientId()) {
              params["emrid"] = context.getRecipientId()
            }
            if(context.getUserId()) {
              params["emuid"] = context.getUserId()
            }
            if(context.getEmail()) {
              params["ememail"] = context.getEmail()
            }
            if(context.getEmailHash()) {
              params["ememailhash"] = context.getEmailHash()
            }
            var profileProperties = context.getProfileProperties();
            for(var key in profileProperties) {
              if(profileProperties.hasOwnProperty(key)) {
                params["p." + key] = profileProperties[key]
              }
            }
            var history = econda.data.visitor.getHistory();
            params["p.ec:productBasketAddList"] = this._convertProductEventListToJsonParamValue(history.getFilteredItems(function(e) {
              return e instanceof ProductAddToCartEvent
            }));
            params["p.ec:productBuyList"] = this._convertProductEventListToJsonParamValue(history.getFilteredItems(function(e) {
              return e instanceof ProductBuyEvent
            }));
            params["p.ec:productViewList"] = this._convertProductEventListToJsonParamValue(history.getFilteredItems(function(e) {
              return e instanceof ProductViewEvent
            }));
            params["timestamp"] = this._currentDateAsUtcIsoString();
            var productsExcluded = context.getProductsExcluded();
            params["excl"] = this._convertProductReferencesListToParamValue(productsExcluded)
          }
          params["pid"] = productIds;
          return params
        };
        AjaxProxy.prototype._getAutoContextParamValue = function() {
          var productIds = [];
          var productViewedEvents = econda.data.visitor.getHistory().getFilteredItems(function(e) {
            return e instanceof ProductViewEvent
          });
          var productReference;
          for(var n = 0;n < productViewedEvents.length;n++) {
            if(productReference = productViewedEvents[n].getProduct()) {
              if(productReference.getSku()) {
                productIds.push("sku:" + productReference.getSku())
              }else {
                productIds.push(productReference.getId())
              }
            }
          }
          return productIds.slice(-5).reverse()
        };
        AjaxProxy.prototype._currentDateAsUtcIsoString = function() {
          return DateUtils.toUtcIsoString(new Date)
        };
        AjaxProxy.prototype._convertProductEventListToJsonParamValue = function(productEvents) {
          var resultData = [];
          for(var i = 0;i < productEvents.length;i++) {
            var productReference;
            if(productReference = productEvents[i].getProduct()) {
              if(productReference.getSku() !== null) {
                resultData.push({t:DateUtils.toUtcIsoString(productEvents[i].getTimestamp()), sku:productReference.getSku()})
              }else {
                resultData.push({t:DateUtils.toUtcIsoString(productEvents[i].getTimestamp()), pid:productReference.getId()})
              }
            }
          }
          var sortedProductList = resultData.reverse();
          return econda.util.Json.stringify(sortedProductList)
        };
        AjaxProxy.prototype._convertProductReferencesListToParamValue = function(productReferences, readerFunction) {
          var productIds = [];
          var read = typeof readerFunction === "function" ? readerFunction : function(item) {
            return item
          };
          for(var i = 0;i < productReferences.length;i++) {
            var productReference = read(productReferences[i]);
            if(productReference.getSku() !== null) {
              productIds.push("sku:" + productReference.getSku())
            }else {
              productIds.push(productReference.getId())
            }
          }
          return productIds
        };
        return AjaxProxy
      }(econda.base.BaseClass);
      proxy.AjaxProxy = AjaxProxy
    })(proxy = recengine.proxy || (recengine.proxy = {}))
  })(recengine = econda.recengine || (econda.recengine = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var recengine;
  (function(recengine) {
    var Context = econda.recengine.context.Context;
    var Request = function(_super) {
      __extends(Request, _super);
      function Request(cfg) {
        if(cfg === void 0) {
          cfg = null
        }
        var _this = _super.call(this) || this;
        _this.widgetId = null;
        _this.accountId = null;
        _this.startIndex = 0;
        _this.chunkSize = null;
        _this.context = null;
        _this.defaultContextEnabled = true;
        _this.autoContext = false;
        _this.type = Request.TYPE_CROSS_SELL;
        _this.decorators = [];
        _this.proxy = null;
        _this.success = null;
        _this._error = null;
        _this.timeoutMilliseconds = 0;
        _this._callback = null;
        _this.requestWasSuccessful = null;
        if(cfg instanceof Request) {
          return cfg
        }else {
          _this.initConfig(cfg)
        }
        return _this
      }
      Request.prototype.getWidgetId = function() {
        return this.widgetId
      };
      Request.prototype.setWidgetId = function(widgetId) {
        this.widgetId = widgetId;
        return this
      };
      Request.prototype.getAccountId = function() {
        return this.accountId || econdaConfig.crosssellAccountId
      };
      Request.prototype.setAccountId = function(accountId) {
        this.accountId = accountId;
        return this
      };
      Request.prototype.getStartIndex = function() {
        return this.startIndex
      };
      Request.prototype.setStartIndex = function(index) {
        this.startIndex = index;
        return this
      };
      Request.prototype.getChunkSize = function() {
        return this.chunkSize
      };
      Request.prototype.setChunkSize = function(count) {
        this.chunkSize = count;
        return this
      };
      Request.prototype.getContext = function() {
        return this.defaultContextEnabled ? this.context || new Context : this.context
      };
      Request.prototype.setContext = function(context) {
        this.context = new Context(context);
        return this
      };
      Request.prototype.getDefaultContextEnabled = function() {
        return this.defaultContextEnabled
      };
      Request.prototype.setDefaultContextEnabled = function(enabled) {
        this.defaultContextEnabled = enabled === true
      };
      Request.prototype.getAutoContext = function() {
        return this.autoContext
      };
      Request.prototype.setAutoContext = function(autoContext) {
        this.autoContext = autoContext;
        return this
      };
      Request.prototype.getType = function() {
        return this.type
      };
      Request.prototype.setType = function(requestType) {
        if(requestType !== Request.TYPE_CROSS_SELL && requestType !== Request.TYPE_AD_REQUEST) {
          econda.debug.error("Trying to set invalid cross sell request type: " + requestType)
        }
        this.type = requestType;
        return this
      };
      Request.prototype.getDecorators = function() {
        return this.decorators
      };
      Request.prototype.setDecorators = function(decorators) {
        var cmp = this;
        return this.setArray("decorators", decorators, null, {callback:function(dec) {
          dec.setRequest(cmp)
        }})
      };
      Request.prototype.addDecorators = function(decorators) {
        var cmp = this;
        return this.addArray("decorators", decorators, null, {callback:function(dec) {
          dec.setRequest(cmp)
        }})
      };
      Request.prototype.getProxy = function() {
        return this.proxy
      };
      Request.prototype.setProxy = function(proxy) {
        if(typeof proxy === "string") {
          var className = econda.util.StringUtils.ucFirst(proxy);
          if(typeof econda.recengine.proxy[className] !== "undefined") {
            this.proxy = new econda.recengine.proxy[className]
          }else {
            throw"proxy not supported: " + proxy;
          }
        }else {
          this.proxy = proxy
        }
        if(this.proxy && typeof this.proxy.setRequest !== "undefined") {
          this.proxy.setRequest(this)
        }
        return this
      };
      Request.prototype.getSuccess = function() {
        return this.success
      };
      Request.prototype.setSuccess = function(callback) {
        this.success = callback;
        return this
      };
      Request.prototype.getError = function() {
        return this._error
      };
      Request.prototype.setError = function(callback) {
        this._error = callback;
        return this
      };
      Request.prototype.getTimeoutMilliseconds = function() {
        return this.timeoutMilliseconds
      };
      Request.prototype.setTimeoutMilliseconds = function(seconds) {
        this.timeoutMilliseconds = seconds;
        return this
      };
      Request.prototype.getCallback = function() {
        return this._callback
      };
      Request.prototype.setCallback = function(callback) {
        this._callback = callback;
        return this
      };
      Request.prototype.send = function() {
        var proxy = this.proxy;
        if(!proxy) {
          proxy = new econda.recengine.proxy.AjaxProxy;
          proxy.setRequest(this)
        }
        this._validateAndLogErrors();
        proxy.send()
      };
      Request.prototype.getRecommendationServiceParameters = function() {
        var proxy = this.proxy;
        if(!proxy) {
          proxy = new econda.recengine.proxy.AjaxProxy;
          proxy.setRequest(this)
        }
        return proxy.getRecommendationServiceFormFieldParametersFromRequest()
      };
      Request.prototype._validateAndLogErrors = function() {
        if(!this.getAccountId()) {
          econda.debug.error("Missing crosssell account id in request.", this)
        }
        if(!this.getWidgetId()) {
          econda.debug.error("Missing widget id in request", this)
        }
      };
      Request.prototype.handleResponse = function(response) {
        if(response.getIsError()) {
          if(typeof this._error === "function") {
            this._error()
          }
        }else {
          this.processResponseDecorators(response);
          if(typeof this.success === "function") {
            this.success(response)
          }
        }
        if(typeof this._callback === "function") {
          this._callback(response)
        }
      };
      Request.prototype.processResponseDecorators = function(response) {
        var details = response.getWidgetDetails();
        var tracking = details && details.getTracking();
        if(tracking && tracking.getEmcs() === true) {
          var source = "";
          var context = this.getContext();
          if(context && context.getProducts().length > 0) {
            source = context.getProducts()[0].getId()
          }
          this.addDecorators(new econda.recengine.decorator.PerformanceTracking({position:tracking.getEmcs1(), source:source, widgetName:tracking.getEmcs0()}))
        }
        for(var i = 0;i < this.decorators.length;i++) {
          this.decorators[i].decorate(response)
        }
      };
      Request.TYPE_CROSS_SELL = "cs";
      Request.TYPE_AD_REQUEST = "ac";
      return Request
    }(econda.base.BaseClass);
    recengine.Request = Request
  })(recengine = econda.recengine || (econda.recengine = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var recengine;
  (function(recengine) {
    var widget;
    (function(widget_1) {
      var renderer;
      (function(renderer) {
        var FunctionRenderer = function(_super) {
          __extends(FunctionRenderer, _super);
          function FunctionRenderer(cfg) {
            if(cfg === void 0) {
              cfg = null
            }
            var _this = _super.call(this) || this;
            _this.widget = null;
            _this.rendererFn = null;
            _this.scope = null;
            if(cfg instanceof FunctionRenderer) {
              return cfg
            }
            _this.initConfig(cfg);
            return _this
          }
          FunctionRenderer.prototype.getWidget = function() {
            return this.widget
          };
          FunctionRenderer.prototype.setWidget = function(widget) {
            this.widget = widget
          };
          FunctionRenderer.prototype.getRendererFn = function() {
            return this.rendererFn
          };
          FunctionRenderer.prototype.setRendererFn = function(fn) {
            this.rendererFn = fn;
            return this
          };
          FunctionRenderer.prototype.getScope = function() {
            return this.scope
          };
          FunctionRenderer.prototype.setScope = function(scope) {
            this.scope = scope;
            return this
          };
          FunctionRenderer.prototype.render = function(result) {
            if(!this.rendererFn) {
              throw"Widget FunctionRenderer requires a renderer function to be set. No renderer function found. Check widget.renderer.rendererFn.";
            }
            var targetElement = econda.util.DomHelper.element(this.getWidget().getElement());
            var escapeHelper = econda.util.StringUtils;
            var html = this.rendererFn.call(this.scope || this, result, targetElement, escapeHelper);
            return html
          };
          return FunctionRenderer
        }(econda.base.BaseClass);
        renderer.FunctionRenderer = FunctionRenderer
      })(renderer = widget_1.renderer || (widget_1.renderer = {}))
    })(widget = recengine.widget || (recengine.widget = {}))
  })(recengine = econda.recengine || (econda.recengine = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var templating;
  (function(templating) {
    var Uri = econda.net.Uri;
    var DomHelper = econda.util.DomHelper;
    var Template = function(_super) {
      __extends(Template, _super);
      function Template(cfg) {
        var _this = _super.call(this) || this;
        _this._engineInstance = null;
        _this.uri = null;
        _this.template = null;
        _this.engine = "ejs";
        _this._element = null;
        if(cfg instanceof Template) {
          return cfg
        }
        _this.initConfig(cfg, "uri");
        return _this
      }
      Template.prototype.getUri = function() {
        return this.uri
      };
      Template.prototype.setUri = function(uri) {
        this.uri = new Uri(uri);
        return this
      };
      Template.prototype.getTemplate = function() {
        return this.template
      };
      Template.prototype.setTemplate = function(source) {
        this.template = source;
        return this
      };
      Template.prototype.getEngine = function() {
        return this.engine
      };
      Template.prototype.setEngine = function(name) {
        name = name.toLowerCase();
        if(name != "ejs") {
          throw"It's not possible to change the template engine in this version.";
        }
        return this
      };
      Template.prototype.getElement = function() {
        return this._element
      };
      Template.prototype.setElement = function(element) {
        this._element = element;
        return this
      };
      Template.prototype.render = function(data) {
        if(data === void 0) {
          data = null
        }
        var templateUri = this.getUri(), template = this.getTemplate(), sourceElement = this.getElement();
        if(!templateUri && !template && !sourceElement) {
          econda.debug.error("Cannot render template: No template or uri found.")
        }
        if(sourceElement) {
          var templateSource = this._readTemplateSourceFromDom(sourceElement);
          if(templateSource) {
            template = templateSource
          }
        }
        if(this._engineInstance == null) {
          if(typeof EJS == "undefined") {
            econda.debug.error("EJS is undefined. Make sure EJS is included from lib directory.")
          }
          this._engineInstance = new EJS({url:templateUri ? templateUri.toString() : null, text:template})
        }
        return this._engineInstance.render(data)
      };
      Template.prototype._readTemplateSourceFromDom = function(element) {
        var e = DomHelper.element(element);
        var encodedTemplate = null;
        if(!e) {
          return null
        }
        encodedTemplate = e.innerHTML;
        for(var i = 0;i < e.childNodes.length;i++) {
          if(e.childNodes[i].nodeType == 8) {
            encodedTemplate = e.childNodes[i].textContent;
            break
          }
        }
        return encodedTemplate.trim().replace("&lt;%", "<%").replace("%&gt;", "%>")
      };
      return Template
    }(econda.base.BaseClass);
    templating.Template = Template
  })(templating = econda.templating || (econda.templating = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var recengine;
  (function(recengine) {
    var widget;
    (function(widget_2) {
      var renderer;
      (function(renderer) {
        var Uri = econda.net.Uri;
        var TemplateRenderer = function(_super) {
          __extends(TemplateRenderer, _super);
          function TemplateRenderer(cfg) {
            var _this = _super.call(this) || this;
            _this._widget = null;
            _this._uri = null;
            _this._template = null;
            if(cfg instanceof TemplateRenderer) {
              return cfg
            }
            _this.initConfig(cfg);
            return _this
          }
          TemplateRenderer.prototype.setWidget = function(widget) {
            this._widget = widget
          };
          TemplateRenderer.prototype.setUri = function(uri) {
            this._uri = uri == null ? null : new Uri(uri);
            return this
          };
          TemplateRenderer.prototype.getUri = function() {
            return this._uri
          };
          TemplateRenderer.prototype.getTemplate = function() {
            return this._template
          };
          TemplateRenderer.prototype.setTemplate = function(html) {
            this._template = html;
            return this
          };
          TemplateRenderer.prototype.getElement = function() {
            return this._element
          };
          TemplateRenderer.prototype.setElement = function(element) {
            this._element = element;
            return this
          };
          TemplateRenderer.prototype.render = function(response) {
            var uri = this.getUri();
            var template = this.getTemplate();
            var element = this.getElement();
            var t = new econda.templating.Template({uri:uri, template:template, element:element});
            try {
              return t.render(response)
            }catch(exception) {
              var errorFn = this._widget.getError();
              if(errorFn) {
                errorFn()
              }
            }
          };
          return TemplateRenderer
        }(econda.base.BaseClass);
        renderer.TemplateRenderer = TemplateRenderer
      })(renderer = widget_2.renderer || (widget_2.renderer = {}))
    })(widget = recengine.widget || (recengine.widget = {}))
  })(recengine = econda.recengine || (econda.recengine = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var recengine;
  (function(recengine) {
    var widget;
    (function(widget_3) {
      var renderer;
      (function(renderer) {
        var WebsaleRenderer = function(_super) {
          __extends(WebsaleRenderer, _super);
          function WebsaleRenderer(cfg) {
            if(cfg === void 0) {
              cfg = null
            }
            var _this = _super.call(this) || this;
            _this.widget = null;
            _this.template = "econda.htm";
            _this.callback = null;
            if(cfg instanceof WebsaleRenderer) {
              return cfg
            }
            _this.initConfig(cfg);
            return _this
          }
          WebsaleRenderer.prototype.getWidget = function() {
            return this.widget
          };
          WebsaleRenderer.prototype.setWidget = function(widget) {
            this.widget = widget
          };
          WebsaleRenderer.prototype.getTemplate = function() {
            return this.template
          };
          WebsaleRenderer.prototype.setTemplate = function(name) {
            this.template = name;
            return this
          };
          WebsaleRenderer.prototype.getCallback = function() {
            return this.callback
          };
          WebsaleRenderer.prototype.setCallback = function(func) {
            this.callback = func;
            return this
          };
          WebsaleRenderer.prototype.render = function(result) {
            var targetElement = econda.util.DomHelper.element(this.getWidget().getElement());
            var templateName = this.getTemplate();
            var callbackFnc = this.getCallback();
            if(callbackFnc != null) {
              callbackFnc(targetElement, result, templateName)
            }
            return false
          };
          return WebsaleRenderer
        }(econda.base.BaseClass);
        renderer.WebsaleRenderer = WebsaleRenderer
      })(renderer = widget_3.renderer || (widget_3.renderer = {}))
    })(widget = recengine.widget || (recengine.widget = {}))
  })(recengine = econda.recengine || (econda.recengine = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var condition;
  (function(condition_1) {
    var ConditionContainer = function(_super) {
      __extends(ConditionContainer, _super);
      function ConditionContainer(cfg) {
        var _this = _super.call(this) || this;
        _this._conditions = [];
        if(cfg instanceof ConditionContainer) {
          return cfg
        }
        _this.initConfig(cfg);
        return _this
      }
      ConditionContainer.prototype.areTrue = function() {
        for(var n = 0, l = this._conditions.length;n < l;n++) {
          if(this._conditions[n].isTrue() === false) {
            return false
          }
        }
        return true
      };
      ConditionContainer.prototype.add = function(condition) {
        this.addArray("_conditions", condition);
        return this
      };
      ConditionContainer.prototype.clear = function() {
        this._conditions = [];
        return this
      };
      return ConditionContainer
    }(econda.base.BaseClass);
    condition_1.ConditionContainer = ConditionContainer
  })(condition = econda.condition || (econda.condition = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var recengine;
  (function(recengine) {
    var widget;
    (function(widget) {
      var FallbackConfig = function(_super) {
        __extends(FallbackConfig, _super);
        function FallbackConfig(cfg) {
          var _this = _super.call(this) || this;
          _this._widget = null;
          _this._template = null;
          _this._image = null;
          if(cfg instanceof FallbackConfig) {
            return cfg
          }
          if(cfg instanceof econda.net.Uri) {
            _this._constructWithConfigString(cfg.toString());
            return _this
          }
          if(typeof cfg == "string") {
            _this._constructWithConfigString(cfg);
            return _this
          }
          if(typeof cfg == "object" && cfg !== null) {
            _this._constructWithConfigObject(cfg);
            return _this
          }
          return _this
        }
        FallbackConfig.prototype.setWidget = function(widgetConfig) {
          this._widget = widgetConfig
        };
        FallbackConfig.prototype.getWidget = function() {
          return this._widget
        };
        FallbackConfig.prototype.getTemplate = function() {
          return this._template
        };
        FallbackConfig.prototype.setTemplate = function(template) {
          if(typeof template == "string") {
            if(template.match(/\.html?/i)) {
              this._template = {uri:template}
            }else {
              this._template = {template:template}
            }
          }else {
            this._template = template
          }
          return this
        };
        FallbackConfig.prototype.getImage = function() {
          return this._image
        };
        FallbackConfig.prototype.setImage = function(imageUri) {
          if(imageUri === null) {
            this._image = null
          }else {
            this._image = new econda.net.Uri(imageUri)
          }
          return this
        };
        FallbackConfig.prototype._constructWithConfigObject = function(cfg) {
          if(cfg.id || cfg.widgetId) {
            this._widget = cfg;
            return
          }
          if(cfg.uri || cfg.template) {
            this._template = cfg;
            return
          }
        };
        FallbackConfig.prototype._constructWithConfigString = function(cfg) {
          if(cfg.match(/\.html?/i)) {
            this._template = {uri:cfg};
            return
          }
          if(cfg.match(/\.(png|jpg|jpeg|gif)/i)) {
            this._image = new econda.net.Uri(cfg);
            return
          }
          this._template = {template:cfg}
        };
        return FallbackConfig
      }(econda.base.BaseClass);
      widget.FallbackConfig = FallbackConfig
    })(widget = recengine.widget || (recengine.widget = {}))
  })(recengine = econda.recengine || (econda.recengine = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var recengine;
  (function(recengine) {
    var widget;
    (function(widget_4) {
      var FallbackHandler = function(_super) {
        __extends(FallbackHandler, _super);
        function FallbackHandler(cfg) {
          var _this = _super.call(this) || this;
          _this._element = null;
          _this._config = null;
          _this._allowCrossSellRequests = true;
          _this._callback = null;
          if(cfg instanceof FallbackHandler) {
            return cfg
          }
          _this.initConfig(cfg);
          return _this
        }
        FallbackHandler.prototype.getElement = function() {
          return this._element
        };
        FallbackHandler.prototype.setElement = function(element) {
          this._element = element;
          return this
        };
        FallbackHandler.prototype.getConfig = function() {
          return this._config
        };
        FallbackHandler.prototype.setConfig = function(config) {
          this._config = config;
          return this
        };
        FallbackHandler.prototype.getAllowCrossSellRequests = function() {
          return this._allowCrossSellRequests
        };
        FallbackHandler.prototype.setAllowCrossSellRequests = function(enabled) {
          this._allowCrossSellRequests = enabled;
          return this
        };
        FallbackHandler.prototype.getCallback = function() {
          return this._callback
        };
        FallbackHandler.prototype.setCallback = function(callback) {
          this._callback = callback;
          return this
        };
        FallbackHandler.prototype.execute = function() {
          this._tryRenderFallbackWidget()
        };
        FallbackHandler.prototype._tryRenderFallbackWidget = function() {
          var cmp = this;
          var c = this._config;
          if(c.getWidget() !== null && this._allowCrossSellRequests) {
            var widgetConfig = c.getWidget();
            var widget = new econda.recengine.Widget(widgetConfig);
            widget.setElement(this.getElement());
            widget.setAfterRender(function(widget, success) {
              if(success === false) {
                cmp._tryRenderFallbackTemplate()
              }else {
                cmp._exitStatusSuccess()
              }
            });
            widget.render()
          }else {
            this._tryRenderFallbackTemplate()
          }
        };
        FallbackHandler.prototype._tryRenderFallbackTemplate = function() {
          var c = this._config;
          if(c.getTemplate() !== null) {
            var templateConfig = c.getTemplate();
            var template = new econda.templating.Template(templateConfig);
            var html = template.render();
            econda.util.DomHelper.update(this.getElement(), html);
            this._exitStatusSuccess()
          }else {
            this._tryRenderFallbackImage()
          }
        };
        FallbackHandler.prototype._tryRenderFallbackImage = function() {
          var c = this._config;
          var target;
          if(c.getImage() !== null && (target = this.getElement())) {
            econda.util.DomHelper.empty(target);
            var imgNode = document.createElement("img");
            imgNode.src = this._config.getImage().toString();
            target.appendChild(imgNode);
            this._exitStatusSuccess()
          }else {
            this._exitStatusFailure()
          }
        };
        FallbackHandler.prototype._exitStatusSuccess = function() {
          if(typeof this._callback == "function") {
            this._callback({success:true})
          }
        };
        FallbackHandler.prototype._exitStatusFailure = function() {
          if(typeof this._callback == "function") {
            this._callback({success:false})
          }
        };
        return FallbackHandler
      }(econda.base.BaseClass);
      widget_4.FallbackHandler = FallbackHandler
    })(widget = recengine.widget || (recengine.widget = {}))
  })(recengine = econda.recengine || (econda.recengine = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var recengine;
  (function(recengine) {
    var Widget = function(_super) {
      __extends(Widget, _super);
      function Widget(cfg) {
        var _this = _super.call(this) || this;
        _this.element = null;
        _this.removeIfEmpty = false;
        _this.empty = null;
        _this.setEmpty = function(fn) {
          this.empty = fn;
          return this
        };
        _this.emptyThreshold = null;
        _this.renderer = null;
        _this.afterRender = null;
        _this._preConditions = new econda.condition.ConditionContainer;
        _this._fallback = null;
        if(cfg instanceof Widget) {
          return cfg
        }
        _this.initConfig(cfg);
        return _this
      }
      Widget.prototype.getId = function() {
        return this.getWidgetId()
      };
      Widget.prototype.setId = function(id) {
        this.setWidgetId(id);
        return this
      };
      Widget.prototype.getElement = function() {
        return this.element
      };
      Widget.prototype.setElement = function(element) {
        this.element = element;
        return this
      };
      Widget.prototype.getRemoveIfEmpty = function() {
        return this.removeIfEmpty
      };
      Widget.prototype.setRemoveIfEmpty = function(removeIfEmpty) {
        this.removeIfEmpty = removeIfEmpty;
        return this
      };
      Widget.prototype.getEmpty = function() {
        return this.empty
      };
      Widget.prototype.getEmptyThreshold = function() {
        return this.emptyThreshold != null ? this.emptyThreshold : this.chunkSize
      };
      Widget.prototype.setEmptyThreshold = function(itemCount) {
        this.emptyThreshold = itemCount;
        return this
      };
      Widget.prototype.getRenderer = function() {
        return this.renderer
      };
      Widget.prototype.setRenderer = function(renderer) {
        if(typeof renderer == "string") {
          this.renderer = this.createAndReturnRenderer(renderer);
          return this
        }
        if(renderer && renderer.type) {
          this.renderer = this.createAndReturnRenderer(renderer.type, renderer);
          return this
        }
        this.renderer = renderer;
        return this
      };
      Widget.prototype.setAfterRender = function(fn) {
        this.afterRender = fn;
        return this
      };
      Widget.prototype.getAfterRender = function() {
        return this.afterRender
      };
      Widget.prototype.setPreConditions = function(conditions) {
        this._preConditions.clear();
        this.addPreConditions(conditions);
        return this
      };
      Widget.prototype.addPreConditions = function(conditions) {
        if(econda.util.ArrayUtils.isArray(conditions)) {
          for(var n = 0;n < conditions.length;n++) {
            this._preConditions.add(conditions[n])
          }
        }else {
          this._preConditions.add(conditions)
        }
        return this
      };
      Widget.prototype.setFallback = function(config) {
        this._fallback = new econda.recengine.widget.FallbackConfig(config);
        return this
      };
      Widget.prototype.getFallback = function() {
        return this._fallback
      };
      Widget.prototype.createAndReturnRenderer = function(type, cfg) {
        if(cfg === void 0) {
          cfg = null
        }
        var className = econda.util.StringUtils.ucFirst(type) + "Renderer";
        if(typeof econda.recengine.widget.renderer[className] == "undefined") {
          throw"Unknown renderer type: " + type;
        }
        if(typeof cfg.type != "undefined") {
          delete cfg.type
        }
        return new econda.recengine.widget.renderer[className](cfg)
      };
      Widget.prototype.send = function() {
        _super.prototype.send.call(this)
      };
      Widget.prototype.render = function() {
        return this.send()
      };
      Widget.prototype.handleResponse = function(response) {
        _super.prototype.handleResponse.call(this, response);
        if(response.getIsError() === true) {
          this._onErrorResponse(response);
          return
        }
        if(response.products.length < this.getEmptyThreshold()) {
          this._onEmptyResponse(response);
          return
        }
        this._onSuccessfulResponse(response)
      };
      Widget.prototype._onSuccessfulResponse = function(response) {
        this.requestWasSuccessful = true;
        if(this.renderer) {
          var html = null;
          this.renderer.setWidget(this);
          html = this.renderer.render(response);
          if(html !== false) {
            econda.util.DomHelper.update(this.element, html)
          }
          if(typeof this.afterRender == "function") {
            this.afterRender.call(this, this.requestWasSuccessful)
          }
        }
      };
      Widget.prototype._onErrorResponse = function(response) {
        var cmp = this;
        this.requestWasSuccessful = false;
        var callbackAfterFallback = function() {
          if(typeof this.afterRender == "function") {
            cmp.afterRender.call(cmp, cmp.requestWasSuccessful)
          }
        };
        this._executeFallback(false, callbackAfterFallback)
      };
      Widget.prototype._onEmptyResponse = function(response) {
        var cmp = this;
        this.requestWasSuccessful = false;
        var callbackAfterFallback = function() {
          if(typeof cmp.afterRender == "function") {
            cmp.afterRender.call(cmp, cmp.requestWasSuccessful)
          }
          if(typeof cmp.empty == "function") {
            cmp.empty.call(cmp, response)
          }
        };
        if(this.removeIfEmpty) {
          econda.util.DomHelper.remove(this.element);
          if(typeof this.empty == "function") {
            this.empty.call(this, response)
          }
        }else {
          this._executeFallback(true, callbackAfterFallback)
        }
      };
      Widget.prototype._executeFallback = function(allowCrossSellRequests, callback) {
        if(allowCrossSellRequests === void 0) {
          allowCrossSellRequests = true
        }
        if(callback === void 0) {
          callback = null
        }
        if(this._fallback !== null) {
          var fallbackHandler = new econda.recengine.widget.FallbackHandler({config:this._fallback, element:this.element, allowCrossSellRequests:allowCrossSellRequests, callback:callback});
          fallbackHandler.execute()
        }else {
          if(typeof callback == "function") {
            callback({success:false})
          }
        }
      };
      Widget.renderWidgetsFromConfigArray = function(configs) {
        for(var i = 0;i < configs.length;i++) {
          var w = new Widget(configs[i]);
          w.render()
        }
      };
      return Widget
    }(recengine.Request);
    recengine.Widget = Widget;
    var initEcWidgets = function() {
      if(typeof ecWidgets != "undefined") {
        Widget.renderWidgetsFromConfigArray(ecWidgets)
      }
    };
    econda.util.DomHelper.isDocumentReady() ? initEcWidgets() : econda.util.DomHelper.documentReady(initEcWidgets)
  })(recengine = econda.recengine || (econda.recengine = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var recengine;
  (function(recengine) {
    var proxy;
    (function(proxy) {
      var AjaxProxy = econda.recengine.proxy.AjaxProxy;
      var AdProxy = function(_super) {
        __extends(AdProxy, _super);
        function AdProxy() {
          return _super !== null && _super.apply(this, arguments) || this
        }
        AdProxy.prototype._getClientId = function() {
          var r = this.getRequest();
          var accountId = r.getAccountId();
          if(accountId == null || accountId.length < 10) {
            econda.debug.error("Invalid account id in cross sell request: " + accountId);
            return null
          }else {
            return accountId.substr(0, 8)
          }
        };
        AdProxy.prototype._getRequestUri = function() {
          var r = this.getRequest();
          var accountId = r.getAccountId();
          var protocol = AjaxProxy.detectProtocol();
          var clientId = this._getClientId();
          var uri = protocol + "://cross.econda-monitor.de/l/" + clientId + "/" + accountId + ".do";
          return uri
        };
        AdProxy.prototype._isCookieRequired = function() {
          return true
        };
        AdProxy.prototype.send = function() {
          if(this.getUri() == null) {
            var uri = this._getRequestUri();
            this.setUri(uri)
          }
          _super.prototype.send.call(this)
        };
        return AdProxy
      }(AjaxProxy);
      proxy.AdProxy = AdProxy
    })(proxy = recengine.proxy || (recengine.proxy = {}))
  })(recengine = econda.recengine || (econda.recengine = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var recengine;
  (function(recengine) {
    var AdWidget = function(_super) {
      __extends(AdWidget, _super);
      function AdWidget(cfg) {
        if(cfg === void 0) {
          cfg = null
        }
        var _this = _super.call(this) || this;
        _this.setType("ac");
        _this.setDefaultContextEnabled(false);
        _this.initConfig(cfg);
        _this.setProxy(new econda.recengine.proxy.AdProxy);
        return _this
      }
      AdWidget.renderAdWidgetsFromConfigArray = function(configs) {
        for(var i = 0;i < configs.length;i++) {
          var w = new AdWidget(configs[i]);
          w.render()
        }
      };
      return AdWidget
    }(recengine.Widget);
    recengine.AdWidget = AdWidget;
    var initEcAdWidgets = function() {
      if(typeof ecAdWidgets != "undefined") {
        AdWidget.renderAdWidgetsFromConfigArray(ecAdWidgets)
      }
    };
    econda.util.DomHelper.isDocumentReady() ? initEcAdWidgets() : econda.util.DomHelper.documentReady(initEcAdWidgets)
  })(recengine = econda.recengine || (econda.recengine = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var recengine;
  (function(recengine) {
    var AdRequest = function(_super) {
      __extends(AdRequest, _super);
      function AdRequest(cfg) {
        if(cfg === void 0) {
          cfg = null
        }
        var _this = _super.call(this) || this;
        if(cfg instanceof AdRequest) {
          return cfg
        }
        _this.setType(recengine.Request.TYPE_AD_REQUEST);
        _this.setDefaultContextEnabled(false);
        _this.initConfig(cfg);
        _this.setProxy(new econda.recengine.proxy.AdProxy);
        return _this
      }
      return AdRequest
    }(recengine.Request);
    recengine.AdRequest = AdRequest
  })(recengine = econda.recengine || (econda.recengine = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var profileaccess;
  (function(profileaccess) {
    var context;
    (function(context) {
      var Context = function(_super) {
        __extends(Context, _super);
        function Context(cfg) {
          var _this = _super.call(this) || this;
          _this.profileProperties = {};
          _this.visitorId = null;
          _this.customerId = null;
          _this.recipientId = null;
          _this.userId = null;
          _this.email = null;
          _this.emailHash = null;
          _this._appendVisitorData = null;
          var cfg = cfg || {};
          if(cfg instanceof Context) {
            return cfg
          }else {
            if(typeof cfg["appendVisitorData"] != "undefined") {
              _this._appendVisitorData = cfg["appendVisitorData"] === true;
              delete cfg["appendVisitorData"]
            }
            _this.initConfig(cfg)
          }
          if(_this._appendVisitorData === true) {
            _this._doAppendVisitorData()
          }
          return _this
        }
        Context.prototype.setProfileProperties = function(profileProperties) {
          this.profileProperties = profileProperties
        };
        Context.prototype.getProfileProperties = function() {
          return this.profileProperties
        };
        Context.prototype.setVisitorId = function(visitorId) {
          this.visitorId = visitorId;
          return this
        };
        Context.prototype.getVisitorId = function() {
          return this.visitorId
        };
        Context.prototype.setCustomerId = function(customerId) {
          this.customerId = customerId;
          return this
        };
        Context.prototype.getCustomerId = function() {
          return this.customerId
        };
        Context.prototype.setRecipientId = function(recipientId) {
          this.recipientId = recipientId;
          return this
        };
        Context.prototype.getRecipientId = function() {
          return this.recipientId
        };
        Context.prototype.setUserId = function(userId) {
          this.userId = userId;
          return this
        };
        Context.prototype.getUserId = function() {
          return this.userId
        };
        Context.prototype.setEmail = function(email) {
          this.email = email;
          return this
        };
        Context.prototype.getEmail = function() {
          return this.email
        };
        Context.prototype.setEmailHash = function(emailHash) {
          this.emailHash = emailHash;
          return this
        };
        Context.prototype.getEmailHash = function() {
          return this.emailHash
        };
        Context.prototype._doAppendVisitorData = function() {
          try {
            var visitorProfile = econda.data.visitor;
            this.setVisitorId(visitorProfile.getVisitorId());
            this.setCustomerId(visitorProfile.getCustomerId());
            this.setUserId(visitorProfile.getUserId());
            this.setRecipientId(visitorProfile.getRecipientId());
            this.setEmail(visitorProfile.getEmail());
            this.setEmailHash(visitorProfile.getEmailHash());
            this.setProfileProperties(visitorProfile.getProperties())
          }catch(e) {
            econda.debug.error("Could not append visitor profile data to cross sell request due to an internal exception: " + e)
          }
        };
        return Context
      }(econda.base.BaseClass);
      context.Context = Context
    })(context = profileaccess.context || (profileaccess.context = {}))
  })(profileaccess = econda.profileaccess || (econda.profileaccess = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var profileaccess;
  (function(profileaccess) {
    var Context = econda.profileaccess.context.Context;
    var Request = function(_super) {
      __extends(Request, _super);
      function Request(cfg) {
        if(cfg === void 0) {
          cfg = null
        }
        var _this = _super.call(this) || this;
        _this.endpointKey = null;
        _this.accountId = null;
        _this.context = null;
        _this.defaultContextEnabled = true;
        _this.proxy = null;
        _this.success = null;
        _this._error = null;
        _this._callback = null;
        _this.requestWasSuccessful = null;
        if(cfg instanceof Request) {
          return cfg
        }else {
          _this.initConfig(cfg)
        }
        return _this
      }
      Request.prototype.getEndpointKey = function() {
        return this.endpointKey
      };
      Request.prototype.setEndpointKey = function(endpointKey) {
        this.endpointKey = endpointKey;
        return this
      };
      Request.prototype.getAccountId = function() {
        return this.accountId || econdaConfig.crosssellAccountId
      };
      Request.prototype.setAccountId = function(accountId) {
        this.accountId = accountId;
        return this
      };
      Request.prototype.getContext = function() {
        return this.defaultContextEnabled ? this.context || new Context : this.context
      };
      Request.prototype.setContext = function(context) {
        this.context = new Context(context);
        return this
      };
      Request.prototype.getDefaultContextEnabled = function() {
        return this.defaultContextEnabled
      };
      Request.prototype.setDefaultContextEnabled = function(enabled) {
        this.defaultContextEnabled = enabled === true
      };
      Request.prototype.getProxy = function() {
        return this.proxy
      };
      Request.prototype.setProxy = function(proxy) {
        if(typeof proxy === "string") {
          var className = econda.util.StringUtils.ucFirst(proxy);
          if(typeof econda.profileaccess.proxy[className] !== "undefined") {
            this.proxy = new econda.profileaccess.proxy[className]
          }else {
            throw"proxy not supported: " + proxy;
          }
        }else {
          this.proxy = proxy
        }
        if(this.proxy && typeof this.proxy.setRequest !== "undefined") {
          this.proxy.setRequest(this)
        }
        return this
      };
      Request.prototype.getSuccess = function() {
        return this.success
      };
      Request.prototype.setSuccess = function(callback) {
        this.success = callback;
        return this
      };
      Request.prototype.getError = function() {
        return this._error
      };
      Request.prototype.setError = function(callback) {
        this._error = callback;
        return this
      };
      Request.prototype.getCallback = function() {
        return this._callback
      };
      Request.prototype.setCallback = function(callback) {
        this._callback = callback;
        return this
      };
      Request.prototype.send = function() {
        var proxy = this.proxy;
        if(!proxy) {
          proxy = new econda.profileaccess.proxy.AjaxProxy;
          proxy.setRequest(this)
        }
        this._validateAndLogErrors();
        proxy.send()
      };
      Request.prototype._validateAndLogErrors = function() {
        if(!this.getAccountId()) {
          econda.debug.error("Missing crosssell account id in request.", this)
        }
        if(!this.getEndpointKey()) {
          econda.debug.error("Missing endpoint key in request", this)
        }
      };
      Request.prototype.handleResponse = function(response) {
        if(response.isError) {
          if(typeof this._error === "function") {
            this._error(response)
          }
        }else {
          if(typeof this.success === "function") {
            this.success(response)
          }
        }
        if(typeof this._callback === "function") {
          this._callback(response)
        }
      };
      return Request
    }(econda.base.BaseClass);
    profileaccess.Request = Request
  })(profileaccess = econda.profileaccess || (econda.profileaccess = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var profileaccess;
  (function(profileaccess) {
    var proxy;
    (function(proxy) {
      var AjaxProxy = function(_super) {
        __extends(AjaxProxy, _super);
        function AjaxProxy(cfg) {
          if(cfg === void 0) {
            cfg = null
          }
          var _this = _super.call(this) || this;
          _this.uri = null;
          _this.serviceUri = null;
          _this.request = null;
          _this._ajaxRequest = null;
          if(cfg instanceof AjaxProxy) {
            return cfg
          }
          _this.initConfig(cfg);
          return _this
        }
        AjaxProxy.prototype.getUri = function() {
          return this.uri
        };
        AjaxProxy.prototype.setUri = function(uri) {
          this.uri = uri
        };
        AjaxProxy.prototype.getServiceUri = function() {
          return this.serviceUri
        };
        AjaxProxy.prototype.setServiceUri = function(serviceUri) {
          this.serviceUri = serviceUri
        };
        AjaxProxy.prototype.setRequest = function(request) {
          this.request = request
        };
        AjaxProxy.prototype.getRequest = function() {
          return this.request
        };
        AjaxProxy.prototype.getAjaxRequest = function() {
          return this._ajaxRequest
        };
        AjaxProxy.prototype.send = function() {
          var cmp = this;
          var uri = this.uri;
          if(!uri) {
            uri = this.getEndpointUri()
          }
          var params = this.getEndpointParameters();
          var headerValues = this.getHeaderValues();
          var request = this._ajaxRequest = econda.ajax.Ajax.createRequest({uri:uri, method:"get", reader:"json", params:params, headers:headerValues, success:function(ajaxResponse) {
            cmp.handleSuccess(ajaxResponse)
          }, error:function(error) {
            cmp.handleError(error)
          }});
          request.send()
        };
        AjaxProxy.prototype.getEndpointUri = function() {
          var uri = this.uri;
          var req = this.request;
          if(!uri) {
            var protocol = econda.net.Uri.detectProtocol();
            var serviceUri = this.getServiceUri();
            if(!serviceUri) {
              serviceUri = "services.crosssell.info/profileaccess"
            }
            uri = [protocol, "://", serviceUri, "/", req.getAccountId(), "/profiles/", req.getEndpointKey()].join("")
          }
          return uri
        };
        AjaxProxy.prototype.handleError = function(error) {
          if(!error || !error.isError) {
            error = {isError:true}
          }
          this.request.handleResponse(error)
        };
        AjaxProxy.prototype.handleSuccess = function(responseData) {
          if(!responseData) {
            this.handleError(null);
            return
          }
          this.request.handleResponse(responseData)
        };
        AjaxProxy.prototype.getEndpointParameters = function() {
          var params = {};
          return params
        };
        AjaxProxy.prototype.getHeaderValues = function() {
          var header = {};
          return header
        };
        return AjaxProxy
      }(econda.base.BaseClass);
      proxy.AjaxProxy = AjaxProxy
    })(proxy = profileaccess.proxy || (profileaccess.proxy = {}))
  })(profileaccess = econda.profileaccess || (econda.profileaccess = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var profileaccess;
  (function(profileaccess) {
    var proxy;
    (function(proxy) {
      var AjaxProxy = econda.profileaccess.proxy.AjaxProxy;
      var PublicEndpointProxy = function(_super) {
        __extends(PublicEndpointProxy, _super);
        function PublicEndpointProxy() {
          return _super !== null && _super.apply(this, arguments) || this
        }
        PublicEndpointProxy.prototype.getEndpointParameters = function() {
          var params = {};
          var context = this.getRequest().getContext();
          if(context) {
            if(context.getVisitorId()) {
              params["emvid"] = context.getVisitorId()
            }
            if(context.getCustomerId()) {
              params["emcid"] = context.getCustomerId()
            }
            if(context.getRecipientId()) {
              params["emrid"] = context.getRecipientId()
            }
            if(context.getUserId()) {
              params["emuid"] = context.getUserId()
            }
            if(context.getEmail()) {
              params["ememail"] = context.getEmail()
            }
            if(context.getEmailHash()) {
              params["ememailhash"] = context.getEmailHash()
            }
          }
          return params
        };
        return PublicEndpointProxy
      }(AjaxProxy);
      proxy.PublicEndpointProxy = PublicEndpointProxy
    })(proxy = profileaccess.proxy || (profileaccess.proxy = {}))
  })(profileaccess = econda.profileaccess || (econda.profileaccess = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var profileaccess;
  (function(profileaccess) {
    var PublicEndpointRequest = function(_super) {
      __extends(PublicEndpointRequest, _super);
      function PublicEndpointRequest(cfg) {
        if(cfg === void 0) {
          cfg = null
        }
        var _this = _super.call(this) || this;
        if(cfg instanceof PublicEndpointRequest) {
          return cfg
        }
        _this.initConfig(cfg);
        if(!_this.getProxy()) {
          _this.setProxy(new econda.profileaccess.proxy.PublicEndpointProxy)
        }
        return _this
      }
      return PublicEndpointRequest
    }(profileaccess.Request);
    profileaccess.PublicEndpointRequest = PublicEndpointRequest
  })(profileaccess = econda.profileaccess || (econda.profileaccess = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var profileaccess;
  (function(profileaccess) {
    var proxy;
    (function(proxy) {
      var WebtokenEndpointProxy = function(_super) {
        __extends(WebtokenEndpointProxy, _super);
        function WebtokenEndpointProxy() {
          return _super !== null && _super.apply(this, arguments) || this
        }
        WebtokenEndpointProxy.prototype.getHeaderValues = function() {
          var header = {};
          var webTokenRequest = this.getRequest();
          if(webTokenRequest.getWebToken()) {
            header["X-AUTH-TOKEN"] = webTokenRequest.getWebToken()
          }
          return header
        };
        return WebtokenEndpointProxy
      }(econda.profileaccess.proxy.AjaxProxy);
      proxy.WebtokenEndpointProxy = WebtokenEndpointProxy
    })(proxy = profileaccess.proxy || (profileaccess.proxy = {}))
  })(profileaccess = econda.profileaccess || (econda.profileaccess = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var profileaccess;
  (function(profileaccess) {
    var WebTokenEndpointRequest = function(_super) {
      __extends(WebTokenEndpointRequest, _super);
      function WebTokenEndpointRequest(cfg) {
        if(cfg === void 0) {
          cfg = null
        }
        var _this = _super.call(this) || this;
        _this.webToken = null;
        if(cfg instanceof WebTokenEndpointRequest) {
          return cfg
        }
        _this.initConfig(cfg);
        if(!_this.getProxy()) {
          _this.setProxy(new econda.profileaccess.proxy.WebtokenEndpointProxy)
        }
        return _this
      }
      WebTokenEndpointRequest.prototype.getWebToken = function() {
        return this.webToken
      };
      WebTokenEndpointRequest.prototype.setWebToken = function(webToken) {
        this.webToken = webToken;
        return this
      };
      WebTokenEndpointRequest.prototype._validateAndLogErrors = function() {
        _super.prototype._validateAndLogErrors.call(this);
        if(!this.getWebToken()) {
          econda.debug.error("Missing webtoken in request", this)
        }
      };
      return WebTokenEndpointRequest
    }(profileaccess.Request);
    profileaccess.WebTokenEndpointRequest = WebTokenEndpointRequest
  })(profileaccess = econda.profileaccess || (econda.profileaccess = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var tracking;
  (function(tracking) {
    var TrackingPluginManager = econda.tracking.PluginManager;
    var DebugLogger = function() {
      function DebugLogger() {
      }
      DebugLogger.getInstance = function() {
        if(DebugLogger._instance === null) {
          DebugLogger._instance = new DebugLogger
        }
        return DebugLogger._instance
      };
      DebugLogger.prototype.onRequest = function(emosProperties, cfg) {
        econda.debug.log("Tracking request", emosProperties, cfg)
      };
      DebugLogger.enable = function() {
        TrackingPluginManager.registerPlugin(DebugLogger.getInstance())
      };
      DebugLogger.disable = function() {
        TrackingPluginManager.unregisterPlugin(DebugLogger.getInstance())
      };
      DebugLogger._instance = null;
      return DebugLogger
    }();
    tracking.DebugLogger = DebugLogger;
    if(typeof econdaConfig.trackingLogRequests !== "undefined" && econdaConfig.trackingLogRequests == true || typeof econdaConfig.trackingLogRequests === "undefined" && econdaConfig.debug == true) {
      DebugLogger.enable()
    }
  })(tracking = econda.tracking || (econda.tracking = {}))
})(econda || (econda = {}));
var econda;
(function(econda) {
  var tracking;
  (function(tracking) {
    var CookieStore = econda.cookie.Store;
    var VisitorId = econda.tracking.VisitorId;
    var ThirdPartyVisitorIdPlugIn = function() {
      function ThirdPartyVisitorIdPlugIn() {
      }
      ThirdPartyVisitorIdPlugIn.prototype.onAfterRequest = function(requestProperties, config) {
        var _this = this;
        if(config.isTrackThirdParty() && !config.doNotTrack() && !config.isSyncCacheId()) {
          this.requestThirdPartyVisitorId(function(thirdPartyVisitorId) {
            if(typeof thirdPartyVisitorId === "string" && thirdPartyVisitorId.length > 6) {
              VisitorId.update(thirdPartyVisitorId, {domain:config.getCookieDomain(), expires:config.getClientCookieLifetime()})
            }
            _this.disableAndMarkSessionUpdated()
          })
        }else {
          econda.tracking.PluginManager.unregisterPlugin(ThirdPartyVisitorIdPlugIn)
        }
      };
      ThirdPartyVisitorIdPlugIn.prototype.disableAndMarkSessionUpdated = function() {
        econda.tracking.PluginManager.unregisterPlugin(ThirdPartyVisitorIdPlugIn);
        CookieStore.set({name:"ec_vid_updated", value:"1"})
      };
      ThirdPartyVisitorIdPlugIn.prototype.requestThirdPartyVisitorId = function(callback) {
        try {
          var xhr = new XMLHttpRequest;
          xhr.withCredentials = true;
          xhr.open("GET", this.buildServiceURL(), true);
          xhr.onreadystatechange = function(_) {
            if(xhr.status == 200) {
              callback(xhr.responseText)
            }
          };
          xhr.send()
        }catch(e) {
        }
      };
      ThirdPartyVisitorIdPlugIn.prototype.buildServiceURL = function() {
        var serviceURL = econda.net.Uri.detectProtocol() + "://cross.econda-monitor.de/vi";
        return serviceURL
      };
      return ThirdPartyVisitorIdPlugIn
    }();
    tracking.ThirdPartyVisitorIdPlugIn = ThirdPartyVisitorIdPlugIn;
    if(CookieStore.getValue("ec_vid_updated") !== "1") {
      econda.tracking.PluginManager.registerPlugin(new ThirdPartyVisitorIdPlugIn)
    }
  })(tracking = econda.tracking || (econda.tracking = {}))
})(econda || (econda = {}));

