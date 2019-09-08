<html>
<head>
<script src="https://www.paypalobjects.com/js/external/api.js"></script>
<script>
    top.window.opener.location = "http://localhost/STAPP/user/hubmanager?code=<?php echo $_GET['code']?>";
    top.close();
</script>
</head>
</html>
