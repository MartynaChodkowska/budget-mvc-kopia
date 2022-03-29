<?php

$token = bin2hex(random_bytes(16)); 

var_dump($token);

$hashed_token = hash_hmac('sha256', $token, 'Rmip0r5KFsvULkeVRmeYZWvWnX4GO8TZ');

var_dump($hashed_token);