<!DOCTYPE html>
<html lang="zh-CN">

<head>
  <meta charset="utf-8">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ $csrf_token }}">
  <title>Pc管理</title>
  
  <!-- global config. -->
  <script type="text/javascript">
      window.PC = {!!
          json_encode([
              'token' => $token,
              'csrfToken' => $csrf_token,
              'baseURL' => $base_url,
              'api' => $api
          ])
      !!};
  </script>
</head>

<body>
  <div id="app"></div>
</body>

</html>
