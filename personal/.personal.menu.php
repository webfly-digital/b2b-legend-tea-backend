<?
$aMenuLinks = Array(
	Array(
		"Личные данные", 
		"/personal/private/", 
		Array(), 
		Array(), 
		"\$GLOBALS['USER']->IsAuthorized();" 
	),
	Array(
		"Сменить пароль",
         "/personal/change-password/",
		Array(), 
		Array(), 
		"\$GLOBALS['USER']->IsAuthorized();" 
	),
	Array(
		"Выйти", 
		"/personal/?logout=yes", 
		Array(), 
		Array(), 
		"\$GLOBALS['USER']->IsAuthorized();" 
	)
);
?>