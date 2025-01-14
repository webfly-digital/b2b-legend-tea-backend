<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/**
 * @global CMain $APPLICATION
 */

global $APPLICATION;

//delayed function must return a string
if(empty($arResult))
	return "";

$strReturn = '';


$strReturn .= '<div class="breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList"><div class="inner">';

$itemSize = count($arResult);
for($index = 0; $index < $itemSize; $index++)
{
	$title = htmlspecialcharsex($arResult[$index]["TITLE"]);
	$arrow = '<div class="item"><div class="icon"></div></div>';

	if($arResult[$index]["LINK"] <> "" && $index != $itemSize-1)
	{
		$strReturn .= '
			<a href="'.$arResult[$index]["LINK"].'" class="item" id="bx_breadcrumb_'.$index.'" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
					<span itemprop="name">'.$title.'</span>
				<meta itemprop="position" content="'.($index + 1).'" />
			</a>'.$arrow;
	}
	else
	{
		$strReturn .= '
			<div class="item">
				<span>'.$title.'</span>
			</div>';
	}
}

$strReturn .= '</div></div>';

return $strReturn;

?>

