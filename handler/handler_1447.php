<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 */
DB::exec("UPDATE rooms SET users_now = users_now-1 WHERE id = '".$user->room_id."'");
unset($user->room_id);
?>