<?php
require_once 'includes/i18n.php';
require_once 'includes/customer_auth.php';

customer_logout_user();
header('Location: ' . localized_url('index.php'));
exit;
