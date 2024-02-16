<?php
// Пришлось приколхозить следующий блок, чтобы этот скрипт работал под Windows !0
$myCodePage = "utf-8";
if(mb_stripos(php_uname(), 'windows')!==false){
    $needToRecodeInput = true;
    $myCodePage = 'cp1251';
    if (PHP_VERSION_ID < 50600) {
        iconv_set_encoding('input_encoding', $myCodePage);
        iconv_set_encoding('output_encoding', 'utf-8');
        iconv_set_encoding('internal_encoding', 'utf-8');
    } else {
        ini_set('default_encoding',$myCodePage);
        ini_set('input_encoding', $myCodePage);
    }
}

// Получаем ФИО из STDIN
echo "Представьтесь, пожалуйста!\n";
echo "Фамилия: ";       $lastName = trim(fgets(STDIN));
echo "Имя: ";           $firstName = trim(fgets(STDIN));
echo "Отчество: ";      $surName = trim(fgets(STDIN));

if($needToRecodeInput) { // если надо - преобразуем строки в UTF-8
    [$lastName,$firstName,$surName] = mb_convert_encoding([$lastName,$firstName,$surName],'UTF-8',$myCodePage);
}

$lastName = prepareInput($lastName);
$firstName = prepareInput($firstName);
$surName = prepareInput($surName);

$fullName = $lastName . " " . $firstName . " " . $surName;
$surnameAndInitials = $lastName . " " . mb_substr($firstName,0,1) . "." . mb_substr($surName,0,1) . ".";
$fio = mb_substr($lastName,0,1) . mb_substr($firstName,0,1) . mb_substr($surName,0,1);

echo "Полное имя: '{$fullName}'\n";
echo "Фамилия и инициалы: '{$surnameAndInitials}'\n";
echo "Аббревиатура: '{$fio}'";
echo PHP_EOL;

function prepareInput(string $string=""):string{
    # Making only first letter UPPERCASEd
    return mb_strtoupper(mb_substr($string,0,1)) . mb_strtolower(mb_substr($string, 1, mb_strlen($string)));
}
