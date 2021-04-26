# Mesajkolik PHP
Mesajkolik PHP sınıfı ile api işlemlerini kolayca gerçekleştirebilirsiniz.  
`Öncesinde panel üzerinden API kullanıcısı oluşturmalısınız!`

## Örnekler

#### Sınıfı Tanımlamak
Sayfanın devamındaki işlemleri yapabilmek için önce bu tanımlamayı yapmalısınız !
```php
require_once('mesajkolik.php');
$mesajkolik = new Mesajkolik($api_kullanici, $api_sifre, $baslik);
```
`$api_kullanici`: Oluşturduğunuz API kullanıcı adını girmelisiniz.  
`$api_sifre`: Oluşturduğunuz API şifresini girmelisiniz.  
`$baslik`: Varsayılan olarak kullanılacak SMS başlığı. (Opsiyonel)  

------------

##### SMS Gönderimi
Tekil veya toplu sms gönderimi için kullanılır.
```php
$mesajkolik->sendsms($gsm, $message, $baslik);
```
`$gsm`: Numaraları virgül şeklinde veya array olarak ekleyebilirsiniz.  
`$message`: SMS içeriğini string olarak girebilirsiniz.  
`$baslik`: SMS başlığını veya başlık ID'sini girebilirsiniz.  

------------

##### Gelişmiş SMS Gönderimi
Tek seferde farklı numaralara farklı sms içerikleri göndermek için kullanılır.
```php
$sms = [
    ['gsm' => '905000000001', 'message' => 'Mesaj 1'],
    ['gsm' => '905000000002', 'message' => 'Mesaj 2'],
];
$mesajkolik->advancedsms($sms, $baslik);
```
`$sms`: Array şeklinde numara ve mesaj içerikleri girilebilir.  
`$baslik`: SMS başlığını veya başlık id'sini girebilirsiniz.  

------------

##### Bakiye Sorgulama
Hesabınızdaki SMS kredisi ve TL miktarını sorgulamak için kullanılır.
```php
$mesajkolik->getBalance();
```

------------

##### Başlıkları Sorgulama
Hesabınıza tanımlı ve aktif SMS başlıklarınızı getirir.
```php
$mesajkolik->getHeaders();
```

------------

##### Grupları Sorgulama
Hesabınıza rehber gruplarını getirir.
```php
$mesajkolik->getGroups();
```

------------

##### Kişi Grubu Ekleme
Hesabınıza rehber ekler.
```php
$mesajkolik->groupadd($grupadi);
```
`$grupadi`: Grup adı girebilirsiniz. Tek seferde çoklu grup eklemek için array girebilirsiniz.
Girdiğiniz grup hesabınızda mevcut ise mevcut grubun id'sini döndürür.  

------------

##### Rehber Kişisi Ekleme
Hesabınıza rehber kişisi ekler.
```php
$kisiler = [];

$kisi = new stdClass();
$kisi->name = $name;
$kisi->surname = $surname;
$kisi->gsm = $gsm;
$kisi->group_id = $grupid;
$kisiler[] = $kisi;

$mesajkolik->personadd($kisiler, $grupid);
```
`$kisiler`: Array formatta olmalıdır. İçine eklediğiniz kişileri gösterildiği üzere object formatında girebilirsiniz.  
`$grupid`: Kişilerinizin ekleneceği grup id'sini girmelisiniz.  

------------

##### Anahtar Kelimeleri Listeleme
Hesabınıza tanımlı gelen kutusu anahtar kelimelerinizi listeler.
```php
$mesajkolik->keywords();
```

------------

##### Gelen Mesajları Listeleme
Hesabınıza tanımlı anahtar kelimeleriniz ile gelen mesajları listelemenizi sağlar.
```php
$mesajkolik->inbox($keyword, $begin, $date);
```
`$keyword`: Mesajları anahtar kelimeye göre filtrelemek için girilebilir. (Opsiyonel)  
`$begin`: Başlangıç tarihi (YYYY-MM-DD) (Opsiyonel)  
`$end`: Bitiş tarihi (YYYY-MM-DD) (Opsiyonel)  
