<?php session_start();

include('lib/Box_Rest_Client.php');

$api_key = 'kepl9uu3kn19nmk7u140d80rnh8m9y0q';
$box_net = new Box_Rest_Client($api_key);


if(!array_key_exists('auth',$_SESSION) || empty($_SESSION['auth'])) {
	$_SESSION['auth'] = $box_net->authenticate();
}
else {
	$box_net->auth_token = $_SESSION['auth'];
} 

if(array_key_exists('action',$_POST)) {
	if($_POST['action'] == 'create_folder') {
		$folder = new Box_Client_Folder();
		$folder->attr('name', 'Test Folder.'.time());
		$folder->attr('parent_id', 0);
		$folder->attr('share', false);
		
		echo $box_net->create($folder);

	}
	else if($_POST['action'] == 'upload_file') {
		$file = new Box_Client_File($_FILES['file']['tmp_name'], $_FILES['file']['name']);
		$file->attr('folder_id', 0);
		echo $box_net->upload($file);

	}
}

?>
<html>
	<head>
		<title>Upload Test</title>
	</head>
	<body>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<input type="hidden" name="action" value="create_folder">
		<label>Folder name: </label><input type="text" name="folder_name"> <button type="submit">Create</button>
	</form>
	<hr>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
		<input type="hidden" name="action" value="upload_file">
		
		<label>Select File: </label><input type="file" name="file"> 
		<button type="submit">Upload</button>
	</form>
	</body>
</html>