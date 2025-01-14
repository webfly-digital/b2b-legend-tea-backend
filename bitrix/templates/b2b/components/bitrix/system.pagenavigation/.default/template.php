<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$this->setFrameMode(true);

if (!$arResult["NavShowAlways"]) {
    if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
        return;
}
?>
<section>
    <div class="pagination">
        <div class="content">
            <?

            $strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"] . "&amp;" : "");
            $strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?" . $arResult["NavQueryString"] : "");
            $bFirst = true;

            if ($arResult["NavPageNomer"] > 1):
                if ($arResult["bSavePage"]):
                    ?>
                    <a class="item" href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"] - 1) ?>">
                        <div class="arrow">
                            <div class="icon icon-arrow-left"></div>
                        </div>
                    </a>
                <?
                else:
                    if ($arResult["NavPageNomer"] > 2):
                        ?>
                        <a class="item" href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"] - 1) ?>">
                            <div class="arrow">
                                <div class="icon icon-arrow-left"></div>
                            </div>
                        </a>
                    <?
                    else:
                        ?>
                        <a class="item" href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>">
                            <div class="arrow">
                                <div class="icon icon-arrow-left"></div>
                            </div>
                        </a>
                    <?
                    endif;

                endif;

                if ($arResult["nStartPage"] > 1):
                    $bFirst = false;
                    if ($arResult["bSavePage"]):
                        ?>
                        <a class="item" href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=1">
                            <div class="num">1</div>
                        </a>
                    <?
                    else:
                        ?>
                        <a class="item" href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>">
                            <div class="num">1</div>
                        </a>
                    <?
                    endif;
                    if ($arResult["nStartPage"] > 2):
                        ?>
                        <a class="item"
                           href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= round($arResult["nStartPage"] / 2) ?>">...</a>
                    <?
                    endif;
                endif;
            endif;

            do {
                if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):
                    ?>
                    <span class="item">
                        <div class="num active"><?= $arResult["nStartPage"] ?></div>
                    </span>
                <?
                elseif ($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false):
                    ?>
                    <a class="item" href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>">
                        <div class="num"><?= $arResult["nStartPage"] ?></div>
                    </a>
                <?
                else:
                    ?>
                    <a class="item" href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= $arResult["nStartPage"] ?>">
                        <div class="num"><?= $arResult["nStartPage"] ?></div>
                    </a>
                <?
                endif;
                $arResult["nStartPage"]++;
                $bFirst = false;
            } while ($arResult["nStartPage"] <= $arResult["nEndPage"]);

            if ($arResult["NavPageNomer"] < $arResult["NavPageCount"]):
                if ($arResult["nEndPage"] < $arResult["NavPageCount"]):
                    if ($arResult["nEndPage"] < ($arResult["NavPageCount"] - 1)):
                        ?>
                        <a class="item"
                           href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= round($arResult["nEndPage"] + ($arResult["NavPageCount"] - $arResult["nEndPage"]) / 2) ?>">...</a>
                    <?
                    endif;
                    ?>
                    <a class="item" href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= $arResult["NavPageCount"] ?>">
                        <div class="num"><?= $arResult["NavPageCount"] ?></div>
                    </a>
                <?
                endif;
                ?>
                <a class="item" href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"] + 1) ?>">
                    <div class="arrow">
                        <div class="icon icon-arrow-right"></div>
                    </div>
                </a>
            <?
            endif; ?>
        </div>
    </div>
</section>
