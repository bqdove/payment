<html>
<head>
    <title>文件上传</title>
</head>
<body>
<h1>文件上传</h1>

<form action="{{url('/upload')}}" method="post" enctype="multipart/form-data">
    {{csrf_field()}}
    <input type="file" name="cert" >
    <input type="submit" name="dosubmit" value="上传">
</form>
</body>
</html>
