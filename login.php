<?php
//doc online : 
//https://docs.github.com/en/developers/apps/building-oauth-apps/authorizing-oauth-apps

//get request , either code from github, or login request
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
	//authorised at github
	if(isset($_GET['code']))
	{
		//2. Users are redirected back to your site by GitHub
		$ch = curl_init();	
		curl_setopt($ch, CURLOPT_URL, 'https://github.com/login/oauth/access_token');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'User-Agent: PHP')); 
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array('client_id' => GITHUB_client_id, 'client_secret' => GITHUB_your_client_secret, 'code' => $_GET['code']));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);	
		$response = curl_exec($ch);
		if (curl_errno($ch)) die('erreur github : ' . curl_error($ch));
		$token_data = json_decode($response , true);

		//3. Use the access token to access the API
		curl_setopt($ch, CURLOPT_URL, 'https://api.github.com/user');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: token ' . $token_data['access_token'], 'User-Agent: PHP')); 
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		if (curl_errno($ch)) die('erreur github : ' . curl_error($ch));
		curl_close($ch);
		$user_data = json_decode($response , true);
		
    //HERE we are : lets make some process in our site/database (session, cookie, redirect)
		//fr : faire ici le traitements d'enregistrement ou redirection (session, cookie, redirect)
		signup
		exit();
	}
	else
	{
		//1. Request a user's GitHub identity
		//fr : demande des données du user en lecture seule
		header("Location: https://github.com/login/oauth/authorize?client_id=" . GITHUB_client_id . "&amp;redirect_uri=" . GITHUB_redirect_url . "&amp;scope=read:user");
	}
}
?>	
