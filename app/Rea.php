<?php
//phpinfo();die;
//a公钥  c 
namespace App\Rea;
class Rsa{
    private static $_privkey = '-----BEGIN RSA PRIVATE KEY-----
MIICXgIBAAKBgQC2Ra/hRw5HDVTU7DzSZpiVqIJ07ui0HQBwmIMf614WOm67Ven8
g/ySY0VPNJA8Se+r9CbcnEwtG1HvWmZrH9AgSvs1SDO/ejXzNbRIzyPOSaFCcA49
nvF/7EvWB1yPU7/CmLxj0ZKqS82Jtq3buzUWZQ6HfqRjTO6HzXQTiOV8kwIDAQAB
AoGARzReCwF5IGSi2TMYZ5FQ1lYn8IpMOeCChXnu749BCCn3jBN5/v2Is584OkLs
fcDcci5E6DXexFhB3+FO1VZuNSY+SCGp3wCsefiSdKptMChl+KXbumKJE1D825Wo
CHlYo+vUHsdG0rb4KmvMyxcDq33KQlO42SBFdup6v9Ix3qECQQDlrN+Bf1v00vmq
pst4HJbsk6ehLaDAHwKUkZyNdWAPgnZF5ZKSJ+XTISRV7cVC6mTe2GjbTI5Odmz0
7bwPs+R7AkEAyynqBnCMIeZMTmSmgga6/1G1fNwrslTCy+/GTM93Fk/YEWdhSFIC
bbm4l35xqikH8WKx7T7dj+lxsxdOr0XIyQJBANCaRxZTGq4uwNyiScN6FAojwei+
jHZys+FEr7A7n1f24n1xmcJtwmwUefAh2TyBr8RHQlWMHuRc43FCS8DnXukCQQCE
MId3HS/wtrobn92/aMWvbujZjyBXblM5ApBSVgM0X0tFN7DSr5mo71iuKbWnc/8Z
DpCav3bGNqymTJoD7TIRAkEA1C4JeWWzcGhV+ZDN8+53kGQp8Ll9Qgf9X11TqvBZ
k2WVdG2Dx4/CrgIe/1mlf0mjqFuiD4KbVnB328pkzS2YfA==
-----END RSA PRIVATE KEY-----';
    private static $_pubkey = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC2Ra/hRw5HDVTU7DzSZpiVqIJ0
7ui0HQBwmIMf614WOm67Ven8g/ySY0VPNJA8Se+r9CbcnEwtG1HvWmZrH9AgSvs1
SDO/ejXzNbRIzyPOSaFCcA49nvF/7EvWB1yPU7/CmLxj0ZKqS82Jtq3buzUWZQ6H
fqRjTO6HzXQTiOV8kwIDAQAB
-----END PUBLIC KEY-----';
private static $_isbase64 = false;
/**
 * 初始化key值
 * @param  string  $privkey  私钥
 * @param  string  $pubkey   公钥
 * @param  boolean $isbase64 是否base64编码
 * @return null
 */
public  function init($privkey, $pubkey, $isbase64=false){
    self::$_privkey = $privkey;
    self::$_pubkey = $pubkey;
    self::$_isbase64 = $isbase64;
}
/**
 * 私钥加密
 * @param  string $data 原文
 * @return string       密文
 */
public  function priv_encode($data){
    $outval = '';

    $res = openssl_pkey_get_private(self::$_privkey);

    openssl_private_encrypt($data, $outval, $res);
    if(self::$_isbase64){
        $outval = base64_encode($outval);
    }
    return $outval;
}
/**
 * 公钥解密
 * @param  string $data 密文
 * @return string       原文
 */
public  function pub_decode($data){
    $outval = '';
    if(self::$_isbase64){
        $data = base64_decode($data);
    }
    $res = openssl_pkey_get_public(self::$_pubkey);
    openssl_public_decrypt($data, $outval, $res);
    return $outval;
}
/**
 * 公钥加密
 * @param  string $data 原文
 * @return string       密文
 */
public  function pub_encode($data){
    $outval = '';
    $res = openssl_pkey_get_public(self::$_pubkey);
    openssl_public_encrypt($data, $outval, $res);
    if(self::$_isbase64){
        $outval = base64_encode($outval);
    }
    return $outval;
}
/**
 * 私钥解密
 * @param  string $data 密文
 * @return string       原文
 */
public  function priv_decode($data){
    $outval = '';
    if(self::$_isbase64){
        $data = base64_decode($data);
    }
    $res = openssl_pkey_get_private(self::$_privkey);
    openssl_private_decrypt($data, $outval, $res);
    return $outval;
}
/**
 * 创建一组公钥私钥
 * @return array 公钥私钥数组
 */
public function new_rsa_key(){
    $res = openssl_pkey_new();
    openssl_pkey_export($res, $privkey);
    $d= openssl_pkey_get_details($res);
    $pubkey = $d['key'];
    return array(
        'privkey' => $privkey,
        'pubkey'  => $pubkey
    );
}
}
