<html>
<head>
    <title>文件上传</title>
</head>
<body>
<h1>文件上传</h1>
<<<<<<< HEAD
<form action="{{url('api/multipay/upload')}}" method="post" enctype="multipart/form-data">
=======
<form action="{{url('/execute')}}" method="post" enctype="multipart/form-data">
>>>>>>> b8f8fb7f74f187fa8451136c20e0050ff67cbb53
    {{csrf_field()}}
    <input type="file" name="cert" >
    <input type="submit" name="dosubmit" value="上传">
</form>
</body>
<<<<<<< HEAD
</html>
=======
</html>
>>>>>>> b8f8fb7f74f187fa8451136c20e0050ff67cbb53
