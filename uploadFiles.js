/*
	This is a utility script designed to upload files to a wordpress child-theme dir. 
	The default action is to upload the css and css.map to the theme's root directory.
	This is the function built into the `npm run build:css` script. Additional files 
	can be added as arguments. At present, the local path (relative to root) will be mimicked 
	in the remote dir. In essence, it is a selective clone of local dir.

	EXAMPLE USAGE:

	node uploadFiles.js // Just the css

	node uploadFiles.js functions.php // CSS plus the functions.php

	node uploadFiles.js sass/ // Attempting to upload a dir, such as sass/ will be skipped, but not error out.
	*/

	var Client = require('ftp');
	var fs = require('fs');
	var colors = require('colors');


	function uploadDirectory(dirPath){
		// Eventually, we'll figure out how to upload directories.
		console.log(`Skipping ${dirPath} because it is not a file.`.red.inverse);
	}


// By default, we want to upload these files
var filesToUpload = []

process.argv.forEach((v, i) => {
	// Cycle through the arguments passed arguments and add them to the list if they are, in fact, files
	if (i > 1){
		var stats = fs.statSync(v);
		if (stats.isFile()){
			filesToUpload.push(v);
		} else {
			uploadDirectory(v);
		}
	}
});

var remotePath = "XXX";

var c = new Client();

c.on('ready', function() {
	filesToUpload.forEach((v,i) => {
		var file = v;
		if(file.indexOf('/') > -1 ){
			// If the file is not in the root, then we need to make the remote subdirs.
			// Still, this only applies for individual files, not whole directory uploads.
			var filePath_arr = file.split('/');
			var tempPath = remotePath;
			console.log('>>> Generating remote directories'.yellow);

			for (var i=0; i<filePath_arr.length-1; i++){
				tempPath = `${tempPath}/${filePath_arr[i]}`;
				console.log(tempPath.grey);
				c.mkdir(`${tempPath}`, true, (err) => {
					if (err) throw err;
				});
			}
		} 
		// Now we upload the file. The necesssary remote dirs have been created.
		c.put(`${v}`, `${remotePath}/${v}`, (err) => {
			if (err) throw err;
			console.log(`>>> Uploaded ${v}`.green);
		});
	})
	c.end();
});

c.connect({
	host: "XXXX",
	port: 21, // defaults to 21 
	user: "XXX", // defaults to "anonymous" 
	password: "XXX" // defaults to "@anonymous" 
});