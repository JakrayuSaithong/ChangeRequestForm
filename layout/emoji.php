<?php
function star($s){
    switch ($s) {
        case 0:
            $stars    =    "";
            break;
        case 1:
            $stars    =    "<i class='fa-solid fa-star fa-fw' style='color:#ffc700;'></i>";
            break;
        case 2:
            $stars    =    "<i class='fa-solid fa-star fa-fw' style='color:#ffc700;'></i><i class='fa-solid fa-star fa-fw' style='color:#ffc700;'></i>";
            break;
        case 3:
            $stars    =    "<i class='fa-solid fa-star fa-fw' style='color:#ffc700;'></i><i class='fa-solid fa-star fa-fw' style='color:#ffc700;'></i><i class='fa-solid fa-star fa-fw' style='color:#ffc700;'></i>";
            break;
        case 4:
            $stars    =    "<i class='fa-solid fa-star fa-fw' style='color:#ffc700;'></i><i class='fa-solid fa-star fa-fw' style='color:#ffc700;'></i><i class='fa-solid fa-star fa-fw' style='color:#ffc700;'></i><i class='fa-solid fa-star fa-fw' style='color:#ffc700;'></i>";
            break;
        case 5:
            $stars    =    "<i class='fa-solid fa-star fa-fw' style='color:#ffc700;'></i><i class='fa-solid fa-star fa-fw' style='color:#ffc700;'></i><i class='fa-solid fa-star fa-fw' style='color:#ffc700;'></i><i class='fa-solid fa-star fa-fw' style='color:#ffc700;'></i><i class='fa-solid fa-star fa-fw' style='color:#ffc700;'></i>";
            break;
        default:
            $stars     =    $s;
    }
    return $stars;
}
function emojirating($s)
{
    switch ($s) {
        case 0:
            $emoj    =    "";
            break;
        case 1:
            $emoj    =    "<i class='fa-solid fa-face-sad-cry fa-fw' style='font-size: 35px;color: #ffba00;'></i>";
            break;
        case 2:
            $emoj    =    "<i class='fa-solid fa-face-rolling-eyes fa-fw' style='font-size: 35px;color: #ffba00;'></i>";
            break;
        case 3:
            $emoj    =    "<i class='fa-solid fa-face-grin-wink fa-fw' style='font-size: 35px;color: #ffba00;'></i>";
            break;
        case 4:
            $emoj    =    "<i class='fa-solid fa-face-laugh-beam fa-fw' style='font-size: 35px;color: #ffba00;'></i>";
            break;
        case 5:
            $emoj    =    "<i class='fa-solid fa-face-grin-hearts fa-fw' style='font-size: 35px;color: #ffba00;'></i>";
            break;
        default:
            $emoj     =    $s;
    }
    return $emoj;
}
function emoji($s)
{
    switch ($s) {
        case 0:
            $emoj3    =    "";
            break;
        case 1:
            $emoj3    =    "<i class='fa-solid fa-face-sad-cry fa-fw' style='font-size: 20px;color: #ffba00;'></i>";
            break;
        case 2:
            $emoj3    =    "<i class='fa-solid fa-face-rolling-eyes fa-fw' style='font-size: 20px;color: #ffba00;'></i>";
            break;
        case 3:
            $emoj3    =    "<i class='fa-solid fa-face-grin-wink fa-fw' style='font-size: 20px;color: #ffba00;'></i>";
            break;
        case 4:
            $emoj3    =    "<i class='fa-solid fa-face-laugh-beam fa-fw' style='font-size: 20px;color: #ffba00;'></i>";
            break;
        case 5:
            $emoj3    =    "<i class='fa-solid fa-face-grin-hearts fa-fw' style='font-size: 20px;color: #ffba00;'></i>";
            break;
        default:
            $emoj3     =    $s;
    }
    return $emoj3;
}
function msgrating($s)
{
    switch ($s) {
        case 0:
            $emoj    =    "";
            break;
        case 1:
            $emoj    =    "<span class='badge badge-light-danger fs-3'>1 คะแนน = แย่มากควรปรับปรุง</span>";
            break;
        case 2:
            $emoj    =    "<span class='badge badge-light-danger fs-3'>2 คะแนน = แย่</span>";
            break;
        case 3:
            $emoj    =    "<span class='badge badge-light-info fs-3'>3 คะแนน = พอใช้</span>";
            break;
        case 4:
            $emoj    =    "<span class='badge badge-light-primary fs-3'>4 คะแนน = ดี</span>";
            break;
        case 5:
            $emoj    =    "<span class='badge badge-light-success fs-3'>5 คะแนน = ดีมากๆ</span>";
            break;
        default:
            $emoj     =    $s;
    }
    return $emoj;
}
function msgrating2($s)
{
    switch ($s) {
        case 0:
            $emoj2    =    "";
            break;
        case 1:
            $emoj2    =    "<span class='badge badge-light-danger fs-3'> แย่มากควรปรับปรุง</span>";
            break;
        case 2:
            $emoj2    =    "<span class='badge badge-light-danger fs-3'> แย่</span>";
            break;
        case 3:
            $emoj2    =    "<span class='badge badge-light-info fs-3'> พอใช้</span>";
            break;
        case 4:
            $emoj2    =    "<span class='badge badge-light-primary fs-3'> ดี</span>";
            break;
        case 5:
            $emoj2    =    "<span class='badge badge-light-success fs-3'> ดีมากๆ</span>";
            break;
        default:
            $emoj2     =    $s;
    }
    return $emoj2;
}
function selectrating($s)
{
    switch ($s) {
        case 0:
            $selectstar    =    "";
            break;
        case 1:
            $selectstar    =    "selected";
            break;
        case 2:
            $selectstar    =    "selected";
            break;
        case 3:
            $selectstar    =    "selected";
            break;
        case 4:
            $selectstar    =    "selected";
            break;
        case 5:
            $selectstar    =    "selected";
            break;
        default:
            $selectstar  =    $s;
    }
    return $selectstar;
}
