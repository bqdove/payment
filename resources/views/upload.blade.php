<html>
<head>
    <title>文件上传</title>
</head>
<body>
<h1>文件上传</h1>

<form action="{{url('/api/multipay/upload?driver=wechat&certname=cert1')}}" method="post" enctype="multipart/form-data">
    {{csrf_field()}}
    <input type="file" name="cert1" >

    <input type="submit" name="dosubmit" value="上传">
</form>

<form action="{{url('/api/multipay/upload?driver=wechat&certname=cert2')}}" method="post" enctype="multipart/form-data">
    {{csrf_field()}}
    <input type="file" name="cert2" >

    <input type="submit" name="dosubmit" value="上传">
</form>
</body>
</html>
