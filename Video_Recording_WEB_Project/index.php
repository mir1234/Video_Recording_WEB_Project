<!DOCTYPE html>
<html>
	<head>
		<title>Video Recording Demo</title>
		<link rel="stylesheet" href="css/style.css" type="text/css" media="screen, projection, tv">
		<script src="js/jquery.min.js" type="text/javascript"></script>
		<!-- For Different Browser to support getUserMedia class -->
		<script src="js/gumadapter.js" type="text/javascript"></script>
		<script src="js/VideoRecorderJS.min.js" type="text/javascript"></script>
	</head>
	<body>
		<h1>Try The Demo...</h1>
		
		<div id="record_box">
			<div id="record_frame">
				<video id="viredemovideoele"></video>
				<video id="recordedvideo" style="display:none;" controls></video>
				</br></br>
				<span style="font-size:16px;font-weight:bold;display:none;" id="countdown"></span>
			</div>
			<div id="record_control">
				<div id="part1">
					<input onclick="operate()" id="start_button" value="Start" type="button"/>
				</div>
				<div id="part2">
					<input onclick="operate2()" id="clear_button" value="Clear" type="button"/>
				</div>
				<div id="part3">
					<input onclick="operate3()" id="post_button" value="Post" type="button"/>
				</div>
			</div>
		</div>
		
		
		<a id="downloadurl" target="_blank">Download</a>
						
					
		
	<script type="text/javascript">
		
		var webcam=1;
		
		navigator.getMedia = ( navigator.getUserMedia || // use the proper vendor prefix
                       navigator.webkitGetUserMedia ||
                       navigator.mozGetUserMedia ||
                       navigator.msGetUserMedia);

		navigator.getMedia({video: true}, function() {
		  // webcam is available
		  webcam=1;
		}, function() {
		  // webcam is not available
		  webcam=0;
		  alert("Please connect camera and microphone or failed to get them !!!");
		});
		
		
		var op_fl=0;
		var op_fl3=0
		var video_available=0;
		function operate()
		{
			if(op_fl==0 && webcam==1)
			{
				virec.startCapture();
				stopCountDown();
				startCountDown();
				document.getElementById("countdown").style.display='block';
				// Appending Stop Button
				document.getElementById("start_button").value='Stop';
				op_fl=1;
				
			}
			else if(op_fl==1 && webcam==1)
			{
				virec.stopCapture(oncaptureFinish);
				stopCountDown();
				video_available=1;
				document.getElementById("countdown").style.display='none';
				document.getElementById("start_button").value='Start';
				document.getElementById("start_button").style.display='none';
				// Appending Clear Button
				document.getElementById("clear_button").style.display='block';
				document.getElementById("viredemovideoele").style.display='none';
				document.getElementById("recordedvideo").style.display='block';
				op_fl=0;
				
			}
			else
			{
				alert("Please connect camera and microphone or failed to get them !!!");
			}
		}
		function operate2()
		{
			virec.clearRecording();
			stopCountDown();
			video_available=0;
			op_fl3=0;
			document.getElementById('downloadurl').href = '';
			document.getElementById("clear_button").style.display='none';
			document.getElementById("start_button").style.display='block';
			document.getElementById("viredemovideoele").style.display='block';
			document.getElementById("recordedvideo").style.display='none';
		}
		function operate3(){
		
			if(video_available==1 && op_fl3==0)
			{
				if(confirm('Are you sure to post the video?'))
				{
					uploadBlob(videoblob);
					op_fl3=1;
				}
			}
			else if(video_available==1 && op_fl3==1)
			{
				alert("Video Already Uploaded!!!");
			}
			
			else
			{
				alert("Nothing to Post or No record available!!!");
			}		
		}
		
		var countdownElement = document.getElementById("countdown");
		var videoblob;
		var videobase64;
		var virec = new VideoRecorderJS.init(
				{
					resize: 0.8, // recorded video dimentions are 0.4 times smaller than the original
					webpquality: 0.5, // chrome and opera support webp imags, this is about the aulity of a frame
					framerate: 15,  // recording frame rate
					videotagid: "viredemovideoele",
					videoWidth: "512",
					videoHeight: "384",
					log: true,
					workerPath : "js/recorderWorker.js"
				},
				function () {
					//success callback. this will fire if browsers supports
				},
				function (err) {
					//onerror callback, this will fire for mediaErrors
					if (err.name == "BROWSER_NOT_SUPPORTED") {
						//handler code goes here
					} else if (err.name == "PermissionDeniedError") {
						//handler code goes here
					} else if (err.name == "NotFoundError") {
						//handler code goes here
					} else {
						throw 'Unidentified Error.....';
					}

				}
		);
	
		var countdowntime = 60;
		var functioncalltime = 0;
		var timerInterval = null;
		
		function oncaptureFinish(result) {
			result.forEach(function (item) {
				if (item.type == "video") {
					videoblob = item.blob;
					videobase64 = window.URL.createObjectURL(videoblob);
					document.getElementById('downloadurl').href = videobase64;
					document.getElementById('recordedvideo').src = videobase64;
				} else if (item.type == "audio") {
					var audioblob = item.blob;
					document.getElementById('audiored').src = window.URL.createObjectURL(audioblob);
				}
			});
		}

		function setCountDownTime(time) {
			countdownElement.innerHTML = time;
			return time;
		}

		function startCountDown() {
			if (timerInterval == null) {
				functioncalltime = countdowntime;
				var value = setCountDownTime(functioncalltime);
				timerInterval = setInterval(function () {
					var value = setCountDownTime(--functioncalltime);
					if (value == 0) {
						clearInterval(timerInterval);
						virec.stopCapture(oncaptureFinish);
						document.getElementById("start_button").value='Start';
						document.getElementById("start_button").style.display='none';
						document.getElementById("clear_button").style.display='block';
						document.getElementById("viredemovideoele").style.display='none';
						document.getElementById("recordedvideo").style.display='block';
						op_fl=0;
						video_available=1;
					}
				}, 1000);
			}
		}

		function stopCountDown() {
			if (timerInterval) {
				clearInterval(timerInterval);
				timerInterval = null;
			}
		}

		
		function uploadBlob(blob) {
		var f = new FormData();
		f.append('videofile', blob);
		
		var xhttp = new XMLHttpRequest();
		
		xhttp.open("POST", "function/fileupload.php", true);
		xhttp.send(f);
		
		}
	</script>
		
	</body>
</html> 