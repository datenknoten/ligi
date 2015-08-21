function FileClass() {
    this.id = null;
    this.name = "";
    this.data = "";
    this.deleted = false;
    this.finished = false;
}

function ImageWidget(dom_node)  {
    var DomNode = dom_node;
    var self = this;
    var $file_input = DomNode.find('.file-input-widget');
    var files = [];

    var getDomItemByFilename = function(filename) {
        var $items = DomNode.find('.id-new');
        var $retval;
        $items.each(function(el){
            if (filename == $(this).attr('data-filename')) {
                $retval = $(this);
            }
        });

        return $retval;
    };

    var updateInput = function() {
        $input = DomNode.find('.image-data');
        $input.val(JSON.stringify(files));
    }

    if (DomNode.find('.item').length > 0) {
        DomNode.find('.item').each(function (index) {
            var file = new FileClass();
            var matches = $(this).attr('class').match(/id-(\d+)/);
            if (matches.length > 0) {
                file.id = matches[1];
                file.finished = true;
                files.push(file);
            }
        });
    }

    var addImage = function(ev) {
        $file_input.click();
        return false;
    };

    var uploadFile = function(file) {
        var dom_item = getDomItemByFilename(file.name);
        var progressHandler = function(ev) {
            dom_item.find('.ui.progress').progress({
                percent: Math.round(ev.loaded / ev.total)
            });
        };
        var successHandler = function(data,status,xhr) {
            dom_item
                .removeClass('id-new')
                .addClass('id-'+data.id);
            dom_item.find('.ui.progress').hide();
            dom_item.find('.button.remove-image').show();
            file.id = data.id;
            file.data = null;
            file.name = null;
            file.finished = true;
            updateInput();
        };
        var errorHandler = function(xhr,status,error) {
            console.log([status,error]);
        };
        $.ajax({
            url: Routing.generate('ligi_file_new',{}),  //Server script to process data
            type: 'PUT',
            xhr: function() {  // Custom XMLHttpRequest
                var myXhr = $.ajaxSettings.xhr();
                if(myXhr.upload){ // Check if upload property exists
                    myXhr.upload.addEventListener('progress',progressHandler, false); // For handling the progress of the upload
                }
                return myXhr;
            },
            //Ajax events
            //beforeSend: beforeSendHandler,
            success: successHandler,
            error: errorHandler,
            // Form data
            data: JSON.stringify(file),
            //Options to tell jQuery not to process data or worry about content-type.
            cache: false,
            contentType: false,
            processData: false
        });
    }

    var removeImage = function(ev) {
        var item = $(ev.target).parent('.item');
        console.log(item);
        if (item.hasClass('id-new')) {
            var filename = item.attr('data-filename');
            item.remove();
            console.log(files);
            files = files.filter(function(el) {
                console.log([el.name,filename]);
                return (!(el.name == filename));
            });
            console.log(files);
        } else {
            var matches = $(item).attr('class').match(/id-(\d+)/);
            if (matches.length > 0) {
                var id = matches[1];
                files.forEach(function(file) {
                    if (file.id == id) {
                        file.deleted = true;
                    }
                });
                item.remove();
                updateInput();
            }
        }
        return false;
    };

    var changeFileInput = function(ev) {
        var _files = ev.target.files; // FileList object
        for (var i = 0, f; f = _files[i]; i++) {
            var file = f;
            // Only process image files.
            if (!f.type.match('image.*')) {
                continue;
            }
            var reader = new FileReader();

            var _file = new FileClass();

            // Closure to capture the file information.
            reader.onload = (function(theFile) {
                return function(e) {
                    var template = DomNode.find('template').html().trim();
                    template = $(template);
                    template.attr('data-filename',file.name);
                    DomNode.find('.images').append(template);
                    var image = e.target.result;
                    DomNode.find('div.item[data-filename="'+file.name+'"] img').attr('src',image);
                    DomNode.find('div.item[data-filename="'+file.name+'"] .remove-image').on('click',removeImage);
                    _file.name = file.name;
                    _file.data = image;
                    files.push(_file);
                    uploadFile(_file);
                };
            })(f);

            reader.readAsDataURL(f);
        }
    }

    var submitHandler = function(ev) {
        if (files.filter(function(el) { return !el.finished }).length == 0) {
            return true;
        } else {
            return false;
        }
    };

    DomNode.find('.add-image').on('click',addImage);
    DomNode.find('.remove-image').on('click',removeImage);
    $file_input.hide();
    $file_input.on('change',changeFileInput);
    DomNode.parent('form').on('submit',submitHandler);
    return this;
}

$(document).ready(function(){
    $('.image-widget').each(function(index){
        var widget = new ImageWidget($(this));
    });
});
