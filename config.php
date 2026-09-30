<?php
const ALLOWED_IMAGE_TYPES = [
    'image/jpeg' => 'jpg',
    'image/png' => 'png'
];

const MAX_PRICE = 999999999.99;

const UPLOAD_DIR = 'uploads';

const MIN_PASS_LEN = 8;
const MAX_PASS_LEN = 128;
const MAX_EMAIL_LEN = 128;
const MAX_USER_NAME_LEN = 128;
const MAX_CONTACTS_LEN = 4096;

const PASSWORD_ALGO = PASSWORD_ARGON2ID;