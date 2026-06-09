<?php
session_start();

require_once "router.php";

$router = new Router();

// ===== Pages =====
$router->add('index',           'FrontendPageController', 'index');
$router->add('contact',         'FrontendPageController', 'contact');
$router->add('about',           'FrontendPageController', 'about');
$router->add('doctors',         'FrontendPageController', 'doctors');
$router->add('department',      'FrontendPageController', 'department');
$router->add('departmentDetail', 'FrontendPageController', 'departmentDetail');
$router->add('services',        'FrontendPageController', 'services');
$router->add('serviceDetails',  'FrontendPageController', 'serviceDetails');
$router->add('errorsPage',      'FrontendPageController', 'errorsPage');
$router->add('guiLienHe',       'FrontendPageController', 'guiLienHe');

// ===== Appointment =====
$router->add('appointment',        'FrontendAppointmentController', 'appointment');
$router->add('themYeuCauLichHen',  'FrontendAppointmentController', 'themYeuCauLichHen');
$router->add('getAvailableTime',   'FrontendAppointmentController', 'getAvailableTime');

// ===== Auth & Profile =====
$router->add('login',                 'FrontendAuthController', 'login');
$router->add('register',              'FrontendAuthController', 'register');
$router->add('logout',                'FrontendAuthController', 'logout');
$router->add('profile',               'FrontendAuthController', 'profile');
$router->add('change_password',       'FrontendAuthController', 'changePassword');
$router->add('first_login',           'FrontendAuthController', 'first_login');
$router->add('sendLoginOTP',          'FrontendAuthController', 'sendLoginOTP');
$router->add('verifyLoginOTP',        'FrontendAuthController', 'verifyLoginOTP');
$router->add('update_first_password', 'FrontendAuthController', 'update_first_password');
$router->add('forgot_password',       'FrontendAuthController', 'forgot_password');
$router->add('reset_password',        'FrontendAuthController', 'reset_password');
$router->add('new_password',          'FrontendAuthController', 'new_password');

// ===== AI =====
$router->add('dental-ai',      'FrontendAiController', 'dentalAi');
$router->add('dental-analyze', 'FrontendAiController', 'dentalAnalyze');
$router->add('ai',             'FrontendAiController', 'chat');
$router->add('ai-data',        'FrontendAiController', 'aiData');
$router->add('ai-user',        'FrontendAiController', 'aiUserData');
$router->add('ai-user-data',   'FrontendAiController', 'aiUserData');
$router->add('ai-appointment', 'FrontendAiController', 'aiAppointment');

$router->dispatch();
