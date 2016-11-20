var FGitem = function(el, prnt) {
    this.el = el.find('.fg_item_content');
    this.type = this.el.data('type');
    this.src = this.el.data('src');
    this.link = this.el.data('link');
    this.name = this.el.data('name');
    this.descripton = this.el.data('descripton');
    this.prnt = prnt;
    this.init();
};

FGitem.prototype = {
    init: function() {
        var self = this;
        self.el.bind('click', function() {
            var src = self.src;
            if (self.link == 1) {
                window.open(src);
                return false;
            }
            self.prnt.wrap.fadeIn();
            if (self.type == 'doc') {
                if (src.substr(0, 4) != 'http') {
                    src = 'http://' + window.location.hostname + src;
                };
                var docview = '<p><iframe src=http://docs.google.com/viewer?url=' + src + '&amp;embedded=true width=\"100%\" height=\"640\" style=\"border: none;\"></iframe></p>';
                self.prnt.modal_content.html(docview);
                self.prnt.modal.fadeIn();
            } else {
                self.prnt.modal
                    .fadeIn()
                    .css('background-image', 'url("' + src + '")');
            }

            self.prnt.modal_title.html(self.name);
            self.prnt.modal_description.html(self.descripton);

            self.prnt.modal_download.bind('click', function() {
                window.open(src);
            });

            self.el.find('.fg_download').bind('click', function(e) {
                window.open(src);
                return false;
            });
        });
    },
};

var FG = function(el) {
    this.el = el;
    this.wrap = el.find('.fg_blackwrap');
    this.modal = el.find('.fg_modal');
    this.modal_title = this.modal.find('.fg_modal_title');
    this.modal_description = this.modal.find('.fg_modal_description');
    this.modal_content = this.modal.find('.fg_modal_content');
    this.modal_close = this.modal.find('.fg_modal_close');
    this.modal_download = this.modal.find('.fg_modal_download');
    this.items = el.find('.fg_item');
    this.init();
};

FG.prototype = {
    init: function() {
        var self = this;
        self.items.each(function() {
            new FGitem($(this), self);
        });

        self.modal_close.bind('click', function(e) {
            self.close();
        });

        self.wrap.bind('click', function(e) {
            self.close();
        });
    },
    close: function() {
        var self = this;
        self.modal.css('background-image', 'none');
        self.modal_title.html('');
        self.modal_description.html('');
        self.modal_content.html('');
        self.modal_download.unbind('click');
        self.modal.fadeOut();
        self.wrap.fadeOut();
    },
};


$(window).ready(function() {
    $('.fg_wrap').each(function() {
        new FG($(this));
    });
});
/**
 *
 */
