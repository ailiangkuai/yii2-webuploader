(function () {
    var URL = window.WEBUPLOADER_HOME_URL || getWUBasePath();
    var ServerURL = window.WEBUPLOADER_SERVER_URL || URL;
    window.WEB_UPLOADER_CONFIG = {
        /**
         * 文件接收服务器
         *
         * @var string
         */
        server: ServerURL,
        /**
         * 设置上传flash
         *
         * @var string
         */
        swf   : URL + 'Uploader.swf'
        /**
         * 指定选择文件的按钮容器，不指定则不创建按钮。
         * - id 指定选择文件的按钮容器，不指定则不创建按钮。
         * - innerHTML 指定按钮文字。不指定时优先从指定的容器中看是否自带文字。
         * - multiple 是否开起同时选择多个文件能力。
         *
         * pick : '.pick'
         * pick : {id:'.pick', multiple:true}
         *
         * @var string|object|undefined
         *///
        //pick : undefined,
        /**
         * 自动上传
         * 默认 false
         *
         * @var bool
         *///
        //auto : false,
        /**
         * 指定接受哪些类型的文件。 由于目前还有ext转mimeType表，所以这里需要分开指定。
         * - title 文字描述
         * - extensions 允许的文件后缀，不带点，多个用逗号分割。
         * - mimeTypes 多个用逗号分割。
         *
         * {
         * title: 'Images',
         * extensions: 'gif,jpg,jpeg,bmp,png',
         * mimeTypes: 'image/*'
         * }
         *
         * @var array|null
         */
        //accept : null,
        /**
         * 配置生成缩略图的选项。
         *
         * {
         *   width: 110,
         *   height: 110,
         *
         *   // 图片质量，只有type为`image/jpeg`的时候才有效。
         *   quality: 70,
         *
         *   // 是否允许放大，如果想要生成小图的时候不失真，此选项应该设置为false.
         *   allowMagnify: true,
         *
         *   // 是否允许裁剪。
         *   crop: true,
         *
         *   // 为空的话则保留原有图片格式。
         *   // 否则强制转换成指定的类型。
         *   type: 'image/jpeg'
         *  }
         *
         * @var object
         */
        //thumb: {
        //    width       : 110,
        //    height      : 110,
        //    // 图片质量，只有type为`image/jpeg`的时候才有效。
        //    quality     : 70,
        //    // 是否允许放大，如果想要生成小图的时候不失真，此选项应该设置为false.
        //    allowMagnify: true,
        //    // 是否允许裁剪。
        //    crop        : true,
        //    // 为空的话则保留原有图片格式。
        //    // 否则强制转换成指定的类型。
        //    type        : 'image/jpeg'
        //}
        /**
         * 配置压缩的图片的选项。如果此选项为false, 则图片在上传前不进行压缩。
         *
         * {
         *   width: 1600,
         *   height: 1600,
         *
         *   // 图片质量，只有type为`image/jpeg`的时候才有效。
         *   quality: 90,
         *
         *   // 是否允许放大，如果想要生成小图的时候不失真，此选项应该设置为false.
         *   allowMagnify: false,
         *
         *   // 是否允许裁剪。
         *   crop: false,
         *
         *   // 是否保留头部meta信息。
         *   preserveHeaders: true,
         *
         *   // 如果发现压缩后文件大小比原来还大，则使用原来图片
         *   // 此属性可能会影响图片自动纠正功能
         *   noCompressIfLarger: false,
         *
         *   // 单位字节，如果图片大小小于此值，不会采用压缩。
         *   compressSize: 0
         *  }
         *
         * @var object|bool
         */
        //compress: {
        //    width             : 1600,
        //    height            : 1600,
        //    // 图片质量，只有type为`image/jpeg`的时候才有效。
        //    quality           : 90,
        //    // 是否允许放大，如果想要生成小图的时候不失真，此选项应该设置为false.
        //    allowMagnify      : false,
        //    // 是否允许裁剪。
        //    crop              : false,
        //    // 是否保留头部meta信息。
        //    preserveHeaders   : true,
        //    // 如果发现压缩后文件大小比原来还大，则使用原来图片
        //    // 此属性可能会影响图片自动纠正功能
        //    noCompressIfLarger: false,
        //    // 单位字节，如果图片大小小于此值，不会采用压缩。
        //    compressSize      : 0
        //}
        /**
         * 是否允许在文件传输时提前把下一个文件准备好。 对于一个文件的准备工作比较耗时，比如图片压缩，md5序列化。 如果能提前在当前文件传输期处理，可以节省总体耗时。
         *
         * @var bool
         */
        //prepareNextFile : false
        /**
         * 设置拖拽上传的容器
         *
         * dnd : '#drag-drop' | '.drag-drop'
         *
         * @var string|object|undefined
         */
        //dnd : undefined,
        /**
         * 禁用整个页面拖拽功能，当设置dnd时需要把该项设置为document.body，否则会默认被浏览器打开
         * disableGlobalDnd : false
         *
         * @var string|bool|object
         */
        //disableGlobalDnd : false,
        /**
         * 定监听paste事件的容器，如果不指定，不启用此功能。此功能为通过粘贴来添加截屏的图片。建议设置为document.body
         *
         * @var Object|undefined
         */
        //paste : document.body
        /**
         * 是否要分片处理大文件上传。
         *
         * chunked : false
         *
         * @var bool
         */
        //chunked : false,
        /**
         * 如果要分片，分多大一片？ 默认大小为5M.
         *
         * chunkSize : 5242880
         *
         * @var integer
         */
        //chunkSize : 5242880,
        /**
         * 如果某个分片由于网络问题出错，允许自动重传多少次？
         *
         * chunkRetry : 2
         *
         * @var integer
         */
        //chunkRetry : 2,
        /**
         * 上传并发数。允许同时最大上传进程数。
         *
         * threads : 3
         *
         * @var integer
         */
        //threads : 3,
        /**
         * 文件上传请求的参数表，每次发送都会发送此对象中的参数。
         *
         * @var object
         */
        //formData : {},
        /**
         * 设置文件上传域的name。
         *
         * fileVal : 'file',
         *
         * @var string
         */
        //fileVal : 'file',
        /**
         * 文件上传方式，POST或者GET。
         *
         * @var string
         */
        //method : 'POST',
        /**
         * 是否已二进制的流的方式发送文件，这样整个上传内容php://input都为文件内容， 其他参数在$_GET数组中。
         *
         * @var bool
         */
        //sendAsBinary : false,
        /**
         * 验证文件总数量, 超出则不允许加入队列。
         *
         * fileNumLimit : undefined
         *
         * @var undefined|integer
         */
        //fileNumLimit : undefined,
        /**
         * 验证文件总大小是否超出限制, 超出则不允许加入队列。
         *
         * fileSizeLimit : undefined
         *
         * @var undefined|integer
         */
        //fileSizeLimit : undefined,
        /**
         * 验证单个文件大小是否超出限制, 超出则不允许加入队列。
         *
         * fileStringSizeLimit : undefined
         * @var undefined|integer
         */
        //fileSingleSizeLimit : undefined,
        /**
         * 去重， 根据文件名字、文件大小和最后修改时间来生成hash Key.
         *
         * duplicate : undefined
         *
         * @var bool
         */
        //duplicate : undefined
    };
    function getWUBasePath(docUrl, confUrl) {
        var url = getBasePath(docUrl || self.document.URL || self.location.href, confUrl || getConfigFilePath());
        return (url + (url.substr(url.length - 1) == '/' ? '' : '/'));
    }

    function getConfigFilePath() {
        var configPath = document.getElementsByTagName('script');
        return configPath[ configPath.length - 1 ].src;
    }

    function getBasePath(docUrl, confUrl) {
        var basePath = confUrl;
        if (/^(\/|\\\\)/.test(confUrl)) {
            basePath = /^.+?\w(\/|\\\\)/.exec(docUrl)[0] + confUrl.replace(/^(\/|\\\\)/, '');
        } else if (!/^[a-z]+:/i.test(confUrl)) {
            docUrl = docUrl.split("#")[0].split("?")[0].replace(/[^\\\/]+$/, '');
            basePath = docUrl + "" + confUrl;
        }
        return optimizationPath(basePath);
    }

    function optimizationPath(path) {
        var protocol = /^[a-z]+:\/\//.exec(path)[ 0 ],
            tmp = null,
            res = [];
        path = path.replace(protocol, "").split("?")[0].split("#")[0];
        path = path.replace(/\\/g, '/').split(/\//);
        path[ path.length - 1 ] = "";
        while (path.length) {
            if (( tmp = path.shift() ) === "..") {
                res.pop();
            } else if (tmp !== ".") {
                res.push(tmp);
            }
        }
        return protocol + res.join("/");
    }
})();
window.WebUploaderInit = function (opt) {
    var config = window.WEB_UPLOADER_CONFIG;
    $.extend(config, (opt || {}));
    return window.WebUploader.create(config);
}