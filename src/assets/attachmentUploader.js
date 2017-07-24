/**
 * Created by Choate on 15/7/10.
 */
$(function () {
    $.fn.xstAttachmentUploader = function (method) {
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
        id           : null,
        name         : null,
        input        : null,
        contentOptions : {},
        options      : {},
        uploadOptions: {},
        removeOptions: {},
        cancelOptions: {},
        helpOptions  : {},
        clientOptions: {},
        events       : {}
    };
    var dataName = 'xstAttachmentUploader';
    var methods = {
        init: function (options) {
            var settings = $.extend({}, defaults, options);
            var uploader = WebUploader.create(settings.clientOptions);
            findInput(settings.id).data(dataName, {'uploader' : uploader});
            findInput(settings.uploadOptions.id).click(function () {
                uploader.upload();
                return false;
            });
            $.each(settings.events, function (eventName, call) {
                uploader.on(eventName, call);
            });
            uploader.off('beforeFileQueued');
            uploader.off('uploadSuccess');
            uploader.off('uploadAccept');
            uploader.on('beforeFileQueued', function (file) {
                if (settings.clientOptions.fileNumLimit == 1 && findInput(settings.id).find(getValue(settings.options, 'tag', 'li')).length > 0) {
                    uploader.reset();
                    findInput(settings.id).empty();
                }
                return true;
            });
            uploader.on('fileQueued', function (file) {
                var $options = $.extend({}, settings.options);
                var $cancelOptions = $.extend({}, settings.cancelOptions);
                var $helpOptions = $.extend({}, settings.helpOptions);
                var $contentOptions = $.extend({}, settings.contentOptions);
                var $li = $('<' + removeValue($options, 'tag', 'li') + '>');
                var $help = $('<div>');
                var $cancel = $('<' + removeValue($cancelOptions, 'tag', 'a') + '>');
                var $content = $('<' + removeValue($contentOptions, 'tag', 'span') + '>');
                var $img = $('<img>');
                /*var $icon = getIcon(file.ext);*/
                /*读取新的一套文件缩略图图标*/
                var $icon = getNewIcon(file.ext);

                $img.attr('src', $icon);
                $img.attr('title', file.name);
                $cancel.html(removeValue($cancelOptions, 'label'));
                $content.html(file.name + '(' + converterSize(file.size) + ')');
                $.each(settings.options, function (attr, value) {
                    $li.attr(attr, value);
                });
                $.each($cancelOptions, function (attr, value) {
                    $cancel.attr(attr, value);
                });
                $.each($helpOptions, function (attr, value) {
                    $help.attr(attr, value);
                });
                $.each($contentOptions, function(attr, value) {
                    $content.attr(attr, value);
                });
                $cancel.attr('href', 'javascript:;');
                $li.attr('id', file.id);
                $li.append($img);
                $li.append($content);
                $li.append($help);
                $li.append($cancel);
                findInput(settings.id).append($li);
            });
            uploader.on('uploadProgress', function(file, percentage) {
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
                var $value = JSON.stringify({name:file.name, size:file.size, ext:file.ext, url:response.fileId});
                var $hidden = $('<input>').attr('type', 'hidden').attr('name', settings.name).val($value);
                findInput(file.id).append($hidden);
                var $removeOptions = $.extend({}, settings.removeOptions);
                var $remove = $('<' + removeValue($removeOptions, 'tag', 'a') + '>');
                $remove.html(removeValue($removeOptions, 'label'));
                $.each($removeOptions, function (attr, value) {
                    $remove.attr(attr, value);
                });
                $remove.attr('href', 'javascript:;');
                findInput(settings.input).val('1');
                findInput(file.id).find('.' + settings.helpOptions.class).show().addClass('success').html('上传成功');
                findInput(file.id).find('.'+settings.helpOptions.class).fadeOut(2000, function() {
                    findInput(file.id).find('.' + settings.helpOptions.class).removeClass('success').html('');
                });
                findInput(file.id).find('.' + settings.cancelOptions.class).replaceWith($remove);
            });
            findInput(settings.id).on('click', '.'+settings.cancelOptions.class + ',.' + settings.removeOptions.class,function() {
                var $parent = $(this).parent();
                if ($parent.attr('id')) {
                    uploader.removeFile($parent.attr('id'));
                }
                $parent.remove();
                if (findInput(settings.id).find(getValue(settings.options, 'tag', 'li')).length <= 0) {
                    findInput(settings.input).val('');
                }
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
    };
    var getIcon = function (ext) {
        var maps = {
            "file": "default.png",
            "rar" : "rar.png",
            "zip" : "zip.png",
            "tar" : "zip.png",
            "gz"  : "zip.png",
            "bz2" : "zip.png",
            "doc" : "doc.png",
            "docx": "doc.png",
            "pdf" : "pdf.png",
            "mp3" : "mp3.png",
            "xls" : "xls.png",
            "xlsx": "xls.png",
            "ppt" : "ppt.png",
            "pptx": "ppt.png",
            "avi" : "mp4.png",
            "rmvb": "mp4.png",
            "wmv" : "mp4.png",
            "flv" : "mp4.png",
            "swf" : "mp4.png",
            "rm"  : "mp4.png",
            "txt" : "txt.png",
            "jpg" : "jpg.png",
            "png" : "png.png",
            "jpeg": "jpg.png",
            "gif" : "gif.png",
            "ico" : "jpg.png",
            "bmp" : "jpg.png"
        };
        return window.WEBUPLOADER_HOME_URL + 'images/' + (maps[ext] ? maps[ext] : maps['file']);
    };

    /*
     * 获取新的一套图标
     * linsifu
     */
    var getNewIcon = function (ext) {
        var maps = {
            "file": "default.png",
            "rar" : "zip.png",
            "zip" : "zip.png",
            "tar" : "zip.png",
            "gz"  : "zip.png",
            "bz2" : "zip.png",
            "doc" : "doc.png",
            "docx": "doc.png",
            "pdf" : "pdf.png",
            "mp3" : "mp3.png",
            "xls" : "xls.png",
            "xlsx": "xls.png",
            "ppt" : "ppt.png",
            "pptx": "ppt.png",
            "avi" : "avi.png",
            "rmvb": "avi.png",
            "wmv" : "avi.png",
            "flv" : "avi.png",
            "swf" : "avi.png",
            "rm"  : "avi.png",
            "txt" : "txt.png",
            "jpg" : "jpg.png",
            "png" : "jpg.png",
            "jpeg": "jpg.png",
            "gif" : "jpg.png",
            "ico" : "jpg.png",
            "bmp" : "jpg.png"
        };
        return window.WEBUPLOADER_HOME_URL + 'images/new_icons/' + (maps[ext] ? maps[ext] : maps['file']);
    };

    var converterSize = function(size) {
        var K = 1000;
        var M = 1000000;
        var G = 1000000000;
        var value = '0KB';
        if (size >= M && size < G) {
             value = (size / M).toFixed(2) + 'MB';
        } else if (size >= G) {
            value = (size / G).toFixed(2) + 'GB';
        } else {
            value = (size / K).toFixed(2) + 'KB';
        }

        return value;
    }
});
