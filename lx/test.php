
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<div id="wp" class="warpper">
    <a id="btnSelect">单击选择要上传的照片</a>
    <input id="uploadFile" type="file" name="myPhoto" />
    <button id="btnConfirm" class="btn" >确认上传</button>
</div>
<div id="maskLayer" class="mask-layer" style="display:none;">
    <p>图片正在上传中...</p>
</div>

<input type="file" accept="video/*;capture=camcorder">
<input type="file" accept="audio/*;capture=microphone">
<input type="file" accept="image/*;capture=camera">直接调用相机
<input type="file" accept="image/*" />调用相机 图片或者相册

</body>
</html>