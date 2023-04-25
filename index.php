<?php

$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];

//getFullnameFromParts принимает как аргумент три строки — фамилию, имя и отчество. Возвращает как результат их же, но склеенные через пробел.

function getFullnameFromParts($surname, $name, $patronomyc){
	$fullname = "";
	$fullname .= $surname;
	$fullname .= " ";
	$fullname .= $name;
	$fullname .= " ";
	$fullname .= $patronomyc;
	return $fullname;
};

//getPartsFromFullname принимает как аргумент одну строку — склеенное ФИО.

function getPartsFromFullname($fullname) {
	$personName = explode(' ', $fullname);
	$FIO = [
		'surname' => $personName[0],
		'name' => $personName[1], 
		'patronomyc' => $personName[2],
	];
	return $FIO;
};

//Разработайте функцию getShortName, принимающую как аргумент строку, содержащую ФИО вида «Иванов Иван Иванович» и возвращающую строку вида «Иван И.»,

function getShortName($fullname){
	$shortname = "";
	$shortname .= getPartsFromFullname($fullname)['name'];
	$shortname .= " ";
	$shortname .= mb_substr(getPartsFromFullname($fullname)['surname'], 0, 1);
	$shortname .= ".";
	return $shortname;
};

//Разработайте функцию getGenderFromName, принимающую как аргумент строку, содержащую ФИО (вида «Иванов Иван Иванович»). 

function getGenderFromName($person){
	$gender = 0;
	$fullname = getPartsFromFullname($person);
	$searchName = mb_substr($fullname['name'], mb_strlen($fullname['name']) - 1);
	$searchSurnameFemale = mb_substr($fullname['surname'], mb_strlen($fullname['surname']) - 2);
	$searchSurnameMale = mb_substr($fullname['surname'], mb_strlen($fullname['surname']) - 1);
	$searchPatronomycFemale = mb_substr($fullname['patronomyc'], mb_strlen($fullname['patronomyc']) - 3);
	$searchPatronomycMale = mb_substr($fullname['patronomyc'], mb_strlen($fullname['patronomyc']) - 2);
	if (($searchName == 'й' || $searchName == 'н') || ($searchSurnameMale == 'в') || ($searchPatronomycMale == 'ич')) {
		$gender++;
	}elseif (($searchName == 'а') || ($searchSurnameFemale == 'ва') || ($searchPatronomycFemale == 'вна')) {
		$gender--;
	}
	if($gender > 0){
		$printGender = "мужской пол";
	}elseif ($gender < 0) {
		$printGender = "женский пол";
	}else {
		$printGender = "неопределенный пол";
	}
	return $printGender;
};

//Напишите функцию getGenderDescription для определения полового состава аудитории. Как аргумент в функцию передается массив, схожий по структуре с массивом $example_persons_array.

function getGenderDescription($arrayExample){
	for ($i=0; $i < count($arrayExample); $i++) { 
		$person = $arrayExample[$i]['fullname'];
		$gender[$i] = getGenderFromName($person);
		};
	$numbersMale = array_filter($gender, function($gender) {
   	return $gender == "мужской пол";
   });
	$numbersFemale = array_filter($gender, function($gender) {
   	return $gender == "женский пол";
   });
	$numbersOther = array_filter($gender, function($gender) {
   	return $gender == "неопределенный пол";
	});
	$resultMale = count($numbersMale)/count($arrayExample) * 100;
	$resultFemale = count($numbersFemale)/count($arrayExample) * 100;
	$resultOth = count($numbersOther)/count($arrayExample) * 100;

	echo 'Гендерный состав аудитории: <hr>' . 'Мужчины - ' . round($resultMale, 2). '%<br>' . 'Женщины - ' . round($resultFemale, 2) . '%<br>' . 'Не удалось определить - ' . round($resultOth, 2) . '%<br>';
};

//Напишите функцию getPerfectPartner для определения «идеальной» пары.

function getPerfectPartner($surname, $name, $patronomyc, $arrayExample){
	$surnamePerson = mb_convert_case($surname, MB_CASE_UPPER, "UTF-8");
	$namePerson = mb_convert_case($name, MB_CASE_UPPER, "UTF-8");
	$patronomycPerson = mb_convert_case($patronomyc, MB_CASE_UPPER, "UTF-8"); 
	$fullname = getFullnameFromParts($surnamePerson, $namePerson, $patronomycPerson);
	$genderPerson = getGenderFromName($fullname);
	$numberRand = rand(0, count($arrayExample)-1);
	$personTwo = $arrayExample[$numberRand]['fullname'];
	$genderPersonTwo = getGenderFromName($personTwo);
	if (($genderPerson == $genderPersonTwo) || ($genderPersonTwo == "неопределенный пол")) {
		$genderCompare = false;
		while ($genderCompare == false) {
			if (($genderPerson != $genderPersonTwo) && ($genderPersonTwo != "неопределенный пол")) {
				$genderCompare = true;
				$randomNumber = rand(5000, 10000)/100;
				$text = getShortName($fullname) . ' + ' . getShortName($personTwo) . ' = <br>' . "♡ Идеально на {$randomNumber}% ♡";
			   echo $text;
			}
		}
	}else {
		$randomNumber = rand(5000, 10000)/100;
		$text = getShortName($fullname) . ' + ' . getShortName($personTwo) . ' = <br>' . "♡ Идеально на {$randomNumber}% ♡";
		echo $text;
	};
};
	
?>
