var HO_upload = function(el) {
    this.el = el;
    this.filefield = el.find('input[type="file"]');
    this.cliOpt = el.data('clientoptions');
    this.gcode = el.data('groupcode');
    this.res = el.next();
    this.init();
};

HO_upload.prototype = {
    init: function() {
        var self = this;
        self.filefield.fileupload(self.cliOpt);

        self.filefield.on('fileuploaddone', function(e, data) {
            console.log(data);
            return false;
            var id = data.result.files[0].id;
            $.ajax({
                method: "GET",
                url: "/file/upload/changegroup",
                data: "id=" + id + "&gcode=" + self.gcode,
                dataType: "text",
                success: function(data, textStatus, xhr) {
                    $.ajax({
                        method: "GET",
                        url: "/file/get-group-list",
                        data: "gcode=" + self.gcode,
                        dataType: "html",
                        success: function(data, textStatus, xhr) {
                            this.res.html(data);
                            deleteImgInit();
                        },
                    });
                },
            });
        });

        self.filefield.on('fileuploadfail', function(e, data) {
            console.log(e);
            console.log(data);
        });

        self.filefield.on('fileuploadstart', function(e) {
            self.res.html('<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>');
        });

    },
};
