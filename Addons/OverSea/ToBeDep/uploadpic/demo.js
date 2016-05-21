wx.ready(function () {

    // 5 图片接口
    // 5.1 拍照、本地选图
    var images = {
        localIds: [],
        serverIds: []
    };

    document.querySelector('#uplaodImages').onclick = function () {
        wx.chooseImage({
            success: function (res) {
                images.localIds = res.localIds;
                //alert('已选择 ' + res.localIds.length + ' 张图片');

                var i = 0, length = images.localIds.length;
                images.serverIds = [];
                function upload() {
                    wx.uploadImage({
                        localId: images.localIds[i],
                        success: function (res) {
                            i++;
                            alert('已上传：' + i + '/' + length);
                            images.serverIds.push(res.serverIds);
                            if (i < length) {
                                upload();
                            } else {
                                window.location.href='../../../../../Controller/UploadPictureToOSS.php?serverids=' + images.serverIds;
                            }
                        },
                        fail: function (res) {
                            alert(JSON.stringify(res));
                        }
                    });
                }
                upload();
            }
        });
    };

    document.querySelector('#chooseImage').onclick = function () {
        wx.chooseImage({
            success: function (res) {
                images.localIds = res.localIds;
                alert('已选择 ' + res.localIds.length + ' 张图片');
            }
        });
    };

    // Another way to do batch load
    /*
    document.querySelector('#chooseImage').onclick = function () {
        wx.chooseImage({
            success: function (res) {
                var localIds = res.localIds;
                syncUpload(localIds);
            }
        });
    };


    var syncUpload = function(localIds){
        var localIds = localIds.pop();
        wx.uploadImage({
            localIds: localIds,
            isShowProgressTips: 1,
            success: function (res) {
                var serverIds = res.serverIds; // 返回图片的服务器端ID
                //其他对serverId做处理的代码
                if(localIds.length > 0){
                    syncUpload(localIds);
                }
            }
        });
    };
    */


    // 5.2 图片预览
    document.querySelector('#previewImage').onclick = function () {
        wx.previewImage({
            current: 'http://img3.douban.com/view/photo/photo/public/p2152117150.jpg',
            urls: [
                'http://img3.douban.com/view/photo/photo/public/p2152117150.jpg',
                'http://img3.douban.com/view/photo/photo/public/p2152134700.jpg'
            ]
        });
    };

    // 5.3 上传图片
    document.querySelector('#uploadImage').onclick = function () {
        if (images.localIds.length == 0) {
            alert('请先使用 chooseImage 接口选择图片');
            return;
        }
        var i = 0, length = images.localIds.length;
        images.serverIds = [];
        function upload() {
            wx.uploadImage({
                localIds: images.localIds[i],
                success: function (res) {
                    i++;
                    alert('已上传：' + i + '/' + length);
                    images.serverIds.push(res.serverIds);
                    if (i < length) {
                        upload();
                    }
                },
                fail: function (res) {
                    alert(JSON.stringify(res));
                }
            });
        }
        upload();
    };

    // 5.4 下载图片
    document.querySelector('#downloadImage').onclick = function () {
        if (images.serverIds.length === 0) {
            alert('请先使用 uploadImage 上传图片');
            return;
        }
        var i = 0, length = images.serverIds.length;
        images.localIds = [];
        function download() {
            wx.downloadImage({
                serverIds: images.serverIds[i],
                success: function (res) {
                    i++;
                    alert('已下载：' + i + '/' + length);
                    images.localIds.push(res.localIds);
                    if (i < length) {
                        download();
                    }
                }
            });
        }
        download();
    };
});

wx.error(function (res) {
    alert(res.errMsg);
});