<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if ($result['SHOW_EMPTY_BASKET']) return;

CBitrixComponent::includeComponentClass("webfly:checkout");
WebflyCheckout::basketHandler($result);
WebflyCheckout::totalHandler($result);
WebflyCheckout::regionBlockHandler($result);
WebflyCheckout::deliveryHandler($result);
WebflyCheckout::userDataHandler($result);
WebflyCheckout::propertiesHandler($result);
WebflyCheckout::checkUserProfile($result);
