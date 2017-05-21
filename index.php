<?php
$a = '{
 "questionSet":[
 {"question":"Kuliah dimana1?","no":1,"questionID":"76","opt1":"UI","opt2":"Gundar","opt3":"BSI","opt4":"UP","correctAns":"BSI"},
 {"question":"Kuliah dimana2?","no":2,"questionID":"76","opt1":"UI","opt2":"Gundar","opt3":"BSI","opt4":"UP","correctAns":"BSI"},
 {"question":"Kuliah dimana3?","no":3,"questionID":"76","opt1":"UI","opt2":"Gundar","opt3":"BSI","opt4":"UP","correctAns":"BSI"},
 {"question":"Kuliah dimana4?","no":4,"questionID":"76","opt1":"UI","opt2":"Gundar","opt3":"BSI","opt4":"UP","correctAns":"BSI"}
 ]
}
';
$d = array(4,5);
$a = json_decode($a,1);
foreach($a['questionSet'] as $q){
	if(in_array($q['no'],$d)){
		var_dump($q);
	}
}