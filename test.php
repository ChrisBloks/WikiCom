<?php
class userLevel
{
    const LVL_GUEST = 0x01;
    const LVL_AUTHOR = 0x02;
    const LVL_ADMIN = 0x04;
    const LVL_ALLWAYS = 0xFF;
}

$always = userLevel::LVL_ALLWAYS;
$guest = userLevel::LVL_GUEST;
$admin = userLevel::LVL_ADMIN;
print(($admin & $guest));