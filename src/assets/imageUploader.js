$(function () {
    $.fn.ImageUploader = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.xstUploader');
            return false;
        }
    };
    var defaults = {
        id: null,
        name: null,
        input: null,
        imgOptions: {},
        options: {},
        uploadOptions: {},
        removeOptions: {},
        cancelOptions: {},
        helpOptions: {},
        clientOptions: {},
        events: {}
    };
    var dataName = 'ImageUploader';
    var methods = {
        init: function (options) {
            var settings = $.extend({}, defaults, options);
            var uploader = WebUploader.create(settings.clientOptions);
            uploader.option('compress', null);
            findInput(settings.id).data(dataName, {'uploader': uploader});
            findInput(settings.uploadOptions.id).click(function () {
                uploader.upload();
                return false;
            });
            $.each(settings.events, function (eventName, call) {
                uploader.on(eventName, call);
            });
            uploader.off('error');
            uploader.off('beforeFileQueued');
            uploader.off('uploadSuccess');
            uploader.off('uploadAccept');
            uploader.on('error', function (type, max, file) {
                if (type == "Q_TYPE_DENIED") {
                    alert("请上传图片格式文件");
                } else if (type == "Q_EXCEED_SIZE_LIMIT" || type == "F_EXCEED_SIZE") {
                    alert("文件大小不能超过" + (max / 1024 / 1024).toFixed(2) + "M");
                } else {
                    alert("上传出错！请检查后重新上传！错误代码" + type);
                }
            });
            uploader.on('beforeFileQueued', function (file) {
                if (settings.clientOptions.fileNumLimit == 1 && findInput(settings.id).find(getValue(settings.options, 'tag', 'li')).length > 0) {
                    uploader.reset();
                    findInput(settings.id).empty();
                }
                //添加判断图片队列是否超过上限
                if (findInput(settings.id).find(getValue(settings.options, 'tag', 'li')).length >= settings.clientOptions.fileNumLimit) {
                    alert('上传图片超过上限：' + findInput(settings.id).find(getValue(settings.options, 'tag', 'li')).length);
                    return false;
                }
                return true;
            });
            uploader.on('fileQueued', function (file) {
                var $options = $.extend({}, settings.options);
                var $cancelOptions = $.extend({}, settings.cancelOptions);
                var $imgOptions = $.extend({}, settings.imgOptions);
                var $helpOptions = $.extend({}, settings.helpOptions);
                var $li = $('<' + removeValue($options, 'tag', 'li') + '>');
                var $img = $('<img>');
                var $help = $('<div>');
                var $cancel = $('<' + removeValue($cancelOptions, 'tag', 'a') + '>');
                $cancel.html(removeValue($cancelOptions, 'label'));
                $.each(settings.options, function (attr, value) {
                    $li.attr(attr, value);
                });
                $.each($imgOptions, function (attr, value) {
                    $img.attr(attr, value);
                });
                $.each($cancelOptions, function (attr, value) {
                    $cancel.attr(attr, value);
                });
                $.each($helpOptions, function (attr, value) {
                    $help.attr(attr, value);
                });
                $cancel.attr('href', 'javascript:;');
                $li.attr('id', file.id);
                $li.append($img);
                $li.append($help);
                $li.append($cancel);
                findInput(settings.id).append($li);
                uploader.makeThumb(file, function (error, src) {
                    $img.attr('src', src);
                }, getValue($imgOptions, 'width', 110), getValue($imgOptions, 'height', 110));
            });
            uploader.on('uploadProgress', function (file, percentage) {
                var _percentage = Math.round(percentage * 100);
                findInput(file.id).find('.' + settings.helpOptions.class).addClass('progress').html(_percentage + '%');
            });
            uploader.on('uploadAccept', function (object, ret) {
                return ret.status == 'SUCCESS';
            });
            uploader.on('uploadError', function (file) {
                findInput(file.id).find('.' + settings.helpOptions.class).addClass('error');
                findInput(file.id).find('.' + settings.helpOptions.class).addClass('progress').html('上传失败');
            });
            uploader.on('uploadSuccess', function (file, response) {
                findInput(file.id).find("img").wrap('<a href="' + response.url + '" class="_webuploadZoom" target="_blank"></a>')
                    .data("url",response.filePath);
                var $removeOptions = $.extend({}, settings.removeOptions);
                var $remove = $('<' + removeValue($removeOptions, 'tag', 'a') + '>');
                $remove.html(removeValue($removeOptions, 'label'));
                $.each($removeOptions, function (attr, value) {
                    $remove.attr(attr, value);
                });
                $remove.attr('href', 'javascript:;');
                findInput(file.id).find('.' + settings.helpOptions.class).show().addClass('success').html('上传成功');
                findInput(file.id).find('.' + settings.helpOptions.class).fadeOut(2000, function () {
                    findInput(file.id).find('.' + settings.helpOptions.class).removeClass('success').html('');
                });
                findInput(file.id).find('.' + settings.cancelOptions.class).replaceWith($remove);
                var val = [];
                $("#uploader-" + settings.input + "-items")
                    .find("img")
                    .each(function (i) {
                        val.push($(this).data("url"));
                    });
                findInput(settings.input).val(JSON.stringify(val));
            });
            findInput(settings.id).on('click', '.' + settings.cancelOptions.class + ',.' + settings.removeOptions.class, function () {
                var $parent = $(this).parent();
                if ($parent.attr('id')) {
                    uploader.removeFile($parent.attr('id'));
                }
                $parent.remove();
                if (findInput(settings.id).find(getValue(settings.options, 'tag', 'li')).length <= 0) {
                    findInput(settings.input).val('');
                }
                var val = [];
                $("#uploader-" + settings.input + "-items")
                    .find("img")
                    .each(function (i) {
                        val.push($(this).data("url"));
                    });
                findInput(settings.input).val(JSON.stringify(val));
            });
        }
    };
    var findInput = function (id) {
        return $('#' + id);
    };
    var getValue = function (obj, key, defaultValue) {
        if (typeof obj[key] != 'undefined') {
            return obj[key];
        }
        return defaultValue;
    };
    var removeValue = function (obj, key, defaultValue) {
        var value = getValue(obj, key, defaultValue);
        if (value) {
            delete obj[key];
        }
        return value;
    }
});
