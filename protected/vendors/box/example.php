<?php 
// This is not necessary for all implementations. This is only 
// preset because after authentication, we set the auth_key to 
// a session for this example.
session_start();

function br($num) {
	for($i = 0; $i < $num; ++$i) {
		echo '<br>';
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>BoxNet Example</title>
		<link href='http://fonts.googleapis.com/css?family=Droid+Sans+Mono' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="style.css">
		<meta charset="utf-8">
	</head>
	<body>
		<table id="wrapper">
			<tr>
				<td class="spanner">
					<p>The Box_Rest_Client is a simple PHP based library to access and work with the Box.net ReST api. 
					By providing a standard get/post interface, Box_Rest_Client automatically supports all 
					get/post requests on the Box.net API.</p><br>
					<p>In addition to that, this library provides various "aliases" to ensure a uniform environment. 
					Aliases are simply calls to the box.net api which are abstracted. For example, you could easily 
					get a list of folders by calling the get_account_tree api method, or you could call the folder() method 
					in the Box_Rest_Client, which returns a list of files and folders as Box_Client_File/Box_Client_Folder 
					respectively. These classes then provide additional aliases which allow you to easy access the api 
					programmatically.</p><br>
					<p>This tutorial is designed to teach you the basics of working with the Box_Rest_Client. It teaches 
					you how to: 
					<ol>
						<li><a href="#step1">Authenticate a user</a></li>
						<li><a href="#step2">Get a list of files and folders</a></li>
						<li><a href="#step3">Create a folder</a></li>
						<li><a href="#step4">Upload a file</a></li>
						<li><a href="#step5">Do Anything Else (GET)</a></li>
					</ol>
					<hr>
					<h2><a name="step1">Authenticate a user</a></h2>
					<p>To authenticate a user we must first have an API KEY. These can be received by registering your 
					application by visiting <a href="http://box.net/developers">http://box.net/developers</a>. After you 
					register, you will be assigned an API KEY. All your requests will require an API key so you should set 
					it in the Box_Rest_Client class itself. </p>
					<?php br(1); ?>
					<p>Create an $api_key and pass it as the only argument to your <code>Box_Rest_Client</code> instance. 
					If you need to, just define it in your Box_Rest_Client.php file.</p>
					<?php br(2); ?>
					<p>Our <code>Box_Rest_Client_Auth</code> class currently returns the auth_token. We assign the returned token 
					to our <code>$_SESSION</code> variable. 
					<?php br(13); ?>
					<h2><a name="step2">Get a list of files and folders</a></h2>
					<p>There are two ways to perform this action. The easiest way is to 
					use the alias. The harder way is to utilize the get/post wrappers and 
					manually call your method. This exmaple will only show you the </p>
					<?php br(2); ?>
					<p>This will return a first-level tree listing of the files and 
					folders under your root folder. This is preferred as each 
					file/folder will return as an instance of Box_Client_File and 
					Box_Client_Folder respectively. This allows you to perform other 
					actions on files/folders such as move, update and delete.</p>
					<?php br(5); ?>
					<h2><a name="step3">Create a folder</a></h2>
					<p>Creating a folder is very simple. You can simply create an 
					instance of "Box_Client_Folder", then just set the following through 
					the <code>attr()</code> method. <br>
					- The name of the folder.<br>
					- The id of the parent folder (or leave blank for the root folder)<br>
					- Whether or not to share the folder (by default, this is set to false) </p>
					<br>
					<p>Your folder object will now contain all the attributes of the 
					folder you just created. The create method will also return a value 
					to indicate whether or not the error was successful. The values 
					returned match directly with possible status output params.</p>
					<?php br(5); ?>
					<h2><a name="step4">Upload a File</a></h2>
					<p>There are two ways to upload a file. The first method is the 
					"bounce". In this method the file is first uploaded to your servers 
					and then is pushed onwards to Box. The second method directly pushes 
					a file over to Box. </p><?php br(1); ?>
					<p><b>Bounce Method</b><br></p>
					<p>To upload a file you need to create an instance of the 
					<code>Box_Client_File</code> class. Then you just need to supply 
					the path to the uploaded file and the new file name during 
					construction.</p><br>
					<p>Then you will need to provide the folder_id of the folder that you 
					will be uploading this folder to.</p>
					<p>Finally, you will call the upload method on the <code>Box_Rest_Client</code> 
					passing in the <code>$file</code> that you created. 
					<?php br(5); ?>
					<h2><a name="step5">Do Anything Else (GET)</a></h2>
					<p>Bundled with Box_Rest_Client, is the ability to perform ANY GET/POST 
					call on the Box api. That's right. Even if there is no "custom" method 
					for you to do what you want, you can still do it.</p><br>
					<p>For example, there is currently no built in way to call the 
					<code>collaboration_change_item_role</code> method. But that's no 
					problem. Simply create an instance of the Box_Rest_Client</p><br>
					<p>Then call your api method! It will return an array representation 
					of the XML response that is normally returned.</p>
				</td>
				<td class="spanner" id="code">
					<div style="position: absolute; top: 10px; right: 10px;">
						<a href="sample.php">View Sample App</a> <br>
						<a href="https://github.com/AngeloR/box_rest_client">Download from Git</a>
					</div>
					<?php br(23); ?>
					<?php highlight_string('<?php 
// Include the Box_Rest_Client class
include(\'lib/Box_Rest_Client.php\');
											
// Set your API Key. If you have a lot of pages reliant on the 
// api key, then you should just set it statically in the 
// Box_Rest_Client class.
$api_key = \'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx\';
$box_net = new Box_Rest_Client($api_key);
					


if(!array_key_exists(\'auth\',$_SESSION) || empty($_SESSION[\'auth\'])) {
	$_SESSION[\'auth\'] = $box_net->authenticate();
}
else {
	// If the auth $_SESSION key exists, then we can say that 
	// the user is logged in. So we set the auth_token in 
	// box_net.
	$box_net->auth_token = $_SESSION[\'auth\'];
} ?>
'); br(16); ?>
<?php highlight_string('<?php 
$folder = $box_net->folder(0); 
?>'); br(9); ?>

<?php highlight_string('<?php 
$my_folder = new Box_Client_Folder();

$my_folder->attr(\'name\',\'New Folder\');
$my_folder->attr(\'parent_id\', 0);

$my_folder->attr(\'share\',0);


$box_net->create($folder);
?>'); br(16); ?>

<?php highlight_string('<?php 

$file = new Box_Client_File($_FILES[\'file\'][\'tmp_name\'], $_FILES[\'file\'][\'name\']);

$file->attr(\'folder_id\', 0);

$box_net->upload($file);
?>'); br(13); ?>

<?php highlight_string('<?php 
$box_net = new Box_Rest_Client($api_key);

$res = $box_net->get(\'collaboration_change_item_role\', array(
	\'collaboration_id\' => 134834,
	\'item_role_name\' => \'viewer\'
));
?>'); ?>
				</td>
			</tr>
		</table>
	</body>
</html>