var HO_upload = function(el) {
    this.el = el;
    this.filefield = el.find('input[type="file"]');
    this.cliOpt = this.filefield.data('clientoptions');
    this.gcode = this.filefield.data('groupcode');
    this.res = el.next();
    this.init();
};

HO_upload.prototype = {
    init: function() {
        var self = this;

        self.filefield.fileupload(self.cliOpt);

        self.filefield.on('fileuploaddone', function(e, data) {
            var id = data.result.files[0].id;
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
            console.log(data);
        });

        self.filefield.on('fileuploadstart', function(e) {
            self.res.html('<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>');
        });

    },
    render: function() {
        var self = this;
        $.ajax({
            method: "GET",
            url: "/file/do/get-group-list",
            data: "gcode=" + self.gcode,
            dataType: "json",
            success: function(data, textStatus, xhr) {
                console.log(data);
                return false;
                this.res.html(data);
            },
        });
    },
};
