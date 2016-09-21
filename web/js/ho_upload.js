var HOUploadItem = function(el, prnt) {
    this.el = el;
    this.prnt = prnt;
    this.type = el.data('type');
    this.attach = el.data('attach');
};

HOUploadItem.prototype = {
    init: function() {
        return false;
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
                    new HOUploadItem($(this));
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
