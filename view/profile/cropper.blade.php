<div>
  <img id="image" src="/api/v1/storages/2">
</div>
<img id="img">
<a href="javascript:" onclick="test()">123</a>
<script src="{{ $routes['resource'] }}/js/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="{{ $routes['resource'] }}/cropper/cropper.min.css">
<script src="{{ $routes['resource'] }}/cropper/cropper.min.js"></script>
<script type="text/javascript">
var image = $('#image').cropper({
	aspectRatio: 16 / 9,

	crop: function(e) {
	}
});
var test = function(){
	var a = $('#image').cropper('getCroppedCanvas').toDataURL();
	console.log(a);
	$('#img').attr('src', a);
}
  </script>
</body>
</html>