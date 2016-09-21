var HOUploadItem = function(el, prnt) {
    this.el = el;
    this.prnt = prnt;
    this.type = el.data('type');
    this.attach = el.data('attach');
    this.img = el.data('img');
    this.url = el.data('url');
    this.name = el.data('name');
    this.description = el.data('description') || '';
    this.wrap = el.find('.ho_upload_item_wrap');
    this.deletebtn = el.find('.delete');
    this.editbtn = el.find('.edit');
    this.init();
};

HOUploadItem.prototype = {
    init: function() {
        var self = this;
        self.wrap.bind('click', function() {
            console.log('view click');

            if (self.type == 1) {
                var img = '<img src="' + self.img + '" alt="" style="max-width:100%;">';
                var head = '<h2>' + self.name + '</h2><p>' + self.description + '</p>'
                self.prnt.viewmodal.find('.modal-body').css('text-align', 'center').html(img);
                self.prnt.viewmodal.find('.modal-header').html(head);
                self.prnt.viewmodal.modal();
            } else if (self.type == 2) {
                var head = '<h2>' + self.name + '</h2><p>' + self.description + '</p>'
                var host = 'http://' + window.location.hostname;
                var docview = '<p><iframe src=http://docs.google.com/viewer?url=' + host + self.url + '&amp;embedded=true width=\"100%\" height=\"640\" style=\"border: none;\"></iframe></p>';
                self.prnt.viewmodal.find('.modal-header').html(head);
                self.prnt.viewmodal.find('.modal-body').html(docview);
                self.prnt.viewmodal.modal();
            }


            return false;
        });
        self.deletebtn.bind('click', function() {
            console.log('delete click');
            return false;
        });

        self.editbtn.bind('click', function() {
            console.log('edit click');
            return false;
        });
    },
    view: function() {
        return false;
    },
    edit: function() {
        return false;
    },
};

var HOUpload = function(el) {
    this.el = el;
    this.filefield = el.find('input[type="file"]');
    this.gcode = this.filefield.data('groupcode');
    this.res = el.find('.ho_upload_res');
    this.tmpl = el.find('.ho_upload_tmpl');
    this.errors = el.find('.ho_upload_errors');
    this.editmodal = el.find('.edit-modal');
    this.viewmodal = el.find('.view-modal');
    this.init();
};

HOUpload.prototype = {
    init: function() {
        var self = this;

        self.filefield.fileupload({
            'url': '/file/upload',
            'dataType': 'json',
        });

        self.filefield.on('fileuploaddone', function(e, data) {
            if (!data.result.files) {
                self.res.html('');
                self.errors.append(data.files[0].name + ':<br>');
                for (var key in data.result) {
                    for (var i in data.result[key]) {
                        self.errors.append('>>>> ' + data.result[key][i] + '<br>');
                    }
                }
            }

            var id = data.result.files.id;

            $.ajax({
                method: "GET",
                url: "/file/do/attach",
                data: "file_id=" + id + "&gcode=" + self.gcode,
                dataType: "json",
                success: function(data, textStatus, xhr) {
                    self.render();
                },
            });
        });

        self.filefield.on('fileuploadfail', function(e, data) {
            console.log(e);
            console.log(data.jqXHR.responseText);
        });

        self.filefield.on('fileuploadstart', function(e) {
            self.res.html('<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>');
        });

        self.render();
    },
    render: function() {
        var self = this;
        $.ajax({
            method: "GET",
            url: "/file/do/get-group-list",
            data: "gcode=" + self.gcode,
            dataType: "json",
            success: function(data, textStatus, xhr) {
                var r = '';
                for (var i in data) {
                    console.log(data[i]);
                    console.log(self.tmpl.html());
                    r += makeFromTemplate(data[i], self.tmpl.html());
                }
                self.res.html(r);
                self.el.find('.ho_upload_item').each(function() {
                    new HOUploadItem($(this), self);
                });
            },
        });
    },
};

function makeFromTemplate(obj, html) {
    for (var i in obj) {
        html = html.replace(new RegExp('{{' + i + '}}', 'g'), obj[i]);
    }
    html = html.replace(new RegExp('{{(.{1,30})}}', 'g'), '');
    return html;
}
