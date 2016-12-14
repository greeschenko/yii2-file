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
    this.reupload = el.find('.reupload');
    this.download = el.find('.download');
    this.init();
};

HOUploadItem.prototype = {
    init: function() {
        var self = this;
        self.wrap.bind('click', function() {
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
            } else if (self.type == 3) {
                window.open(self.url);
            }

            return false;
        });
        self.deletebtn.bind('click', function() {
            self.el.css('background', 'tomato').fadeOut(300, function() {
                $.ajax({
                    url: '/file/do/unattach',
                    type: 'POST',
                    dataType: 'json',
                    data: 'id=' + self.attach,
                    success: function(data, textStatus, jqXHR) {
                        self.prnt.render();
                    },
                });
            });
            return false;
        });

        self.editbtn.bind('click', function() {
            var form = self.prnt.editmodal;
            var id = form.find('input[name="ho_file_info_edit_id"]');
            var title = form.find('input[name="ho_file_info_edit_title"]');
            var description = form.find('textarea[name="ho_file_info_edit_description"]');
            var submit = form.find('#fileeditform_submit');
            form.modal();
            id.val(self.attach);
            title.val(self.name);
            description.val(self.description);
            submit.unbind('click');
            submit.bind('click', function() {
                $.ajax({
                    url: '/file/do/change-info',
                    type: 'POST',
                    dataType: 'json',
                    data: 'id=' + id.val() + '&title=' + title.val() + '&description=' + description.val(),
                    success: function(data, textStatus, jqXHR) {
                        if (data.result == 'success') {
                            form.modal('hide');
                            self.prnt.render();
                        } else {
                            alert(data.msg);
                        }
                    },
                });
                return false;
            });
            return false;
        });

        self.reupload.bind('click', function() {
            if (self.type == 3) {
                self.prnt.linkmodal.modal();
                self.prnt.el.find('input[name="ho_link_edit_id"]').val(self.attach);
            } else {
                self.prnt.reu = self.attach;
                self.prnt.filefield.trigger('click');
                $('body').bind('click', function() {
                    self.prnt.reu = 0;
                });
            }
            return false;
        });

        self.download.bind('click', function() {
            window.open(self.url);
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
    this.reu = 0;
    this.filefield = el.find('input[type="file"]');
    this.addlink = el.find('.ho_upload_link_btn');
    this.gcode = this.filefield.data('groupcode');
    this.res = el.find('.ho_upload_res');
    this.tmpl = el.find('.ho_upload_tmpl');
    this.errors = el.find('.ho_upload_errors');
    this.fatalerrors = el.find('.ho_upload_fatalerrors');
    this.editmodal = el.find('.edit-modal');
    this.viewmodal = el.find('.view-modal');
    this.linkmodal = el.find('.link-modal');
    this.init();
};

HOUpload.prototype = {
    init: function() {
        var self = this;

        self.addlink.bind('click', function() {
            self.linkmodal.modal();
        });

        self.el.find('.linkadd_submit').bind('click', function() {
            var id = self.el.find('input[name="ho_link_edit_id"]');
            var group = self.el.find('input[name="ho_link_edit_group"]');
            var type = self.el.find('input[name="ho_link_edit_type"]');
            var link = self.el.find('input[name="ho_link"]');
            var u = /http(s?):\/\/[-\w\.]{2,}\.[A-Za-z]{2,3}/;
            if (u.test(link.val())) {
                link.parent().removeClass('has-error');
                link.parent().find('.help-block').addClass('hidden');
                $.ajax({
                    url: '/file/upload/link',
                    type: 'POST',
                    dataType: 'json',
                    data: 'id=' + id.val() + '&group=' + group.val() + '&type=' + type.val() + '&link=' + link.val(),
                    success: function(data, textStatus, jqXHR) {
                        if (data.result == 'success') {
                            self.linkmodal.modal('hide');
                            link.val('');
                            self.render();
                        } else {
                            alert(data.msg);
                        }
                    }
                });
            } else {
                link.parent().addClass('has-error');
                link.parent().find('.help-block').removeClass('hidden');
            }
            return false;
        });

        self.filefield.fileupload({
            'url': '/file/upload',
            'dataType': 'json',
            'limitMultiFileUploadSize': 100,
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
                self.render();
                return false;
            }

            var id = data.result.files.id;

            $.ajax({
                method: "GET",
                url: "/file/do/attach",
                data: "file_id=" + id + "&gcode=" + self.gcode + '&replace=' + self.reu,
                dataType: "json",
                success: function(data, textStatus, xhr) {
                    self.render();
                },
            });
        });

        self.filefield.on('fileuploadfail', function(e, data) {
            console.log(e);
            console.log(data.jqXHR.responseText);
            self.fatalerrors.show();
            self.render();
        });

        self.filefield.on('fileuploadstart', function(e) {
            self.res.html('<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>');
            self.errors.html('');
            self.fatalerrors.hide();
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
                /*console.log(data);
                return false;*/

                var r = '';
                for (var i in data) {
                    r += self.makefromtmpl(data[i], self.tmpl.html());
                }
                self.res.html(r);
                self.el.find('.ho_upload_item').each(function() {
                    new HOUploadItem($(this), self);
                });
            },
        });
    },
    makefromtmpl: function(obj, html) {
        for (var i in obj) {
            html = html.replace(new RegExp('{{' + i + '}}', 'g'), obj[i]);
        }
        html = html.replace(new RegExp('{{.*}}', 'g'), '');
        return html;
    }
};
