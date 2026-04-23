<?php
// includes/i18n.php - Lightweight localization utilities

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$supported_languages = [
    'en' => 'English',
    'km' => 'ខ្មែរ',
];

if (isset($_GET['lang'])) {
    $requested_lang = strtolower(trim((string)$_GET['lang']));
    if (array_key_exists($requested_lang, $supported_languages)) {
        $_SESSION['site_lang'] = $requested_lang;
    }
}

if (empty($_SESSION['site_lang']) || !array_key_exists($_SESSION['site_lang'], $supported_languages)) {
    $_SESSION['site_lang'] = 'en';
}

if (!defined('APP_LANG')) {
    define('APP_LANG', $_SESSION['site_lang']);
}

$translations = [
    'km' => [
        'nav_home' => 'ទំព័រដើម',
        'nav_menu' => 'ម៉ឺនុយ',
        'nav_cart' => 'កន្ត្រក',
        'nav_checkout' => 'ទូទាត់',
        'nav_order_now' => 'បញ្ជាទិញឥឡូវ',
        'nav_toggle_menu' => 'បើកម៉ឺនុយ',
        'nav_language_aria' => 'ប្ដូរភាសា',

        'hero_eyebrow' => 'រចនាសម្រាប់ការបញ្ជាទិញលឿន និងតម្លៃសន្សំ',
        'hero_title' => 'អាហារតម្លៃសមរម្យសម្រាប់<br>ថ្ងៃសិក្សារវល់',
        'hero_subtitle' => 'បន្ថែមកន្ត្រកបានភ្លាមៗ ជ្រើសឈុតសម្រាប់សាកលវិទ្យាល័យ ហើយទូទាត់លឿនពេលអ្នកឃ្លានចន្លោះម៉ោងរៀន។',
        'hero_cta_start' => 'ចាប់ផ្តើមបញ្ជាទិញ',
        'hero_cta_menu' => 'មើលម៉ឺនុយពេញ',
        'hero_meta_price_title' => 'ចាប់ពី $4.90',
        'hero_meta_price_desc' => 'ឈុតអាហារសម្រាប់និស្សិត',
        'hero_meta_time_title' => '~30 នាទី',
        'hero_meta_time_desc' => 'ពេលដឹកជញ្ជូនជាមធ្យមក្នុងទីក្រុង',
        'hero_meta_rating_title' => '4.8/5',
        'hero_meta_rating_desc' => 'ពេញនិយមក្នុងចំណោមនិស្សិត',

        'browse_heading' => 'ជ្រើសតាមចំណង់',
        'browse_lead' => 'ចាប់ផ្តើមពីអ្វីដែលអ្នកចង់ញ៉ាំ ហើយបង្កើតការបញ្ជាទិញលឿនបំផុតពីម៉ឺនុយដល់ការទូទាត់។',
        'browse_see_picks' => 'មើលជម្រើស',
        'browse_breakfast_title' => 'អាហារពេលព្រឹក',
        'browse_breakfast_desc' => 'វ្រប់ពេលព្រឹក បូលស៊ុត និងកុំបូកាហ្វេសម្រាប់ម៉ោងរៀនព្រឹក។',
        'browse_main_title' => 'ម្ហូបចម្បង',
        'browse_main_desc' => 'បាយ មី និងម្ហូបអាំងដែលឆ្អែត និងមានតម្លៃល្អ។',
        'browse_drinks_title' => 'ភេសជ្ជៈ',
        'browse_drinks_desc' => 'តែទឹកកក ភេសជ្ជៈផ្លែឈើ និងថាមពលសម្រាប់ពេលសិក្សាយូរ។',
        'browse_desserts_title' => 'បង្អែម',
        'browse_desserts_desc' => 'ជម្រើសបង្អែមតម្លៃសមរម្យ ដើម្បីបញ្ចប់អាហាររបស់អ្នកដោយមិនចំណាយច្រើន។',

        'about_visit_title' => 'អញ្ជើញមកទស្សនាពួកយើង',
        'about_title' => 'រចនាសម្រាប់និស្សិត<br>បង្កើតដោយអ្នកស្រឡាញ់អាហារ។',
        'about_text_1' => 'Foodie Lab មានគោលបំណងមួយគត់ គឺធ្វើឲ្យអាហារប្រចាំថ្ងៃមានតម្លៃសមរម្យ ឆ្ងាញ់ និងលឿនសម្រាប់កាលវិភាគរវល់។',
        'about_text_2' => 'រាល់មុខម្ហូបត្រូវបានសាកល្បងទាំងរសជាតិ តម្លៃ និងគុណភាពដឹកជញ្ជូន ដើម្បីឲ្យអាហារអ្នកមកដល់ក្តៅ និងឆ្ងាញ់។',
        'about_btn' => 'ស្វែងរកអាហារតម្លៃសមរម្យ',

        'menu_section_title' => 'មុខម្ហូបពេញនិយមពេលនេះ',
        'menu_section_subtitle' => 'តម្រៀបតាមប្រភេទ បន្ថែមបានលឿន ហើយបង្កើតការបញ្ជាទិញក្នុងរយៈពេលតិចជាងមួយនាទី។',
        'menu_tab_all' => 'ទាំងអស់',
        'menu_categories_aria' => 'ប្រភេទម៉ឺនុយ',
        'menu_order_now' => 'បញ្ជាទិញឥឡូវ',
        'menu_add_to_cart_aria_prefix' => 'បន្ថែម',
        'menu_add_to_cart_aria_suffix' => 'ទៅកន្ត្រក',
        'menu_empty' => 'មិនមានមុខម្ហូបក្នុងប្រភេទនេះទេ។',
        'menu_toast_added_full' => 'បានបន្ថែមមុខម្ហូបទៅកន្ត្រក!',
        'menu_toast_added_tail' => 'បានបន្ថែមទៅកន្ត្រក',

        'delivery_title' => 'ដឹកជញ្ជូនលឿន<br>បញ្ជាទិញដោយគ្មានស្ត្រេស',
        'delivery_text' => 'ជ្រើសមុខម្ហូប បញ្ជាក់ទិន្នន័យរបស់អ្នក ហើយបញ្ជាទិញបានក្នុងប៉ុន្មានចុចប៉ុណ្ណោះ។',
        'delivery_feature_1' => 'ពេលដឹកជញ្ជូនជាមធ្យមប្រហែល 30 នាទី',
        'delivery_feature_2' => 'តម្លៃសមរម្យសម្រាប់និស្សិត និងប្រូម៉ូសិនប្រចាំថ្ងៃ',
        'delivery_feature_3' => 'ការបញ្ជាទិញអនឡាញរលូនចាប់ពីម៉ឺនុយដល់ទូទាត់',

        'testimonials_title' => 'អ្វីដែលនិស្សិតនិយាយ',
        'testimonial_1_title' => '"តម្លៃល្អបំផុតជិតសាលា"',
        'testimonial_1_text' => 'ខ្ញុំអាចបញ្ជាទិញចន្លោះម៉ោងរៀន ហើយយកបានលឿន។ តម្លៃកុំបូធ្វើឲ្យពួកយើងត្រលប់មកវិញជាញឹកញាប់។',
        'testimonial_2_title' => '"ទូទាត់លឿនខ្លាំង"',
        'testimonial_2_text' => 'គេហទំព័រងាយប្រើលើទូរស័ព្ទ ហើយពេលដឹកជញ្ជូនទុកចិត្តបាន។ ល្អសម្រាប់យប់សិក្សា។',
        'testimonial_3_title' => '"តម្លៃសមរម្យ ហើយឆ្ងាញ់"',
        'testimonial_3_text' => 'បរិមាណល្អ រសជាតិស្ថិរភាព ហើយតម្លៃសមស្របសម្រាប់និស្សិត។',

        'footer_desc' => 'អាហារលឿនសម្រាប់ជីវិតនិស្សិតរវល់។ ឈុតតម្លៃសមរម្យ ទូទាត់រលូន និងដឹកជញ្ជូនទុកចិត្តបាន។',
        'footer_order_flow' => 'លំហូរបញ្ជាទិញ',
        'footer_explore' => 'ស្វែងរក',
        'footer_mood_board' => 'កម្រងរូបអាហារ',
        'footer_browse_categories' => 'មើលប្រភេទ',
        'footer_popular_dishes' => 'មុខម្ហូបពេញនិយម',
        'footer_student_reviews' => 'មតិនិស្សិត',

        'cart_title' => 'កន្ត្រករបស់អ្នក',
        'cart_summary_title' => 'សង្ខេបការបញ្ជាទិញ',
        'cart_subtotal' => 'សរុបរង',
        'cart_tax' => 'ពន្ធ (10%)',
        'cart_total' => 'សរុប',
        'cart_proceed' => 'បន្តទៅទូទាត់',
        'cart_empty_title' => 'កន្ត្រករបស់អ្នកទទេ',
        'cart_empty_text' => 'មើលទៅអ្នកមិនទាន់បានបន្ថែមមុខម្ហូបនៅឡើយទេ។',
        'cart_empty_cta' => 'មើលម៉ឺនុយ',
        'cart_remove_item' => 'លុបមុខម្ហូប',

        'checkout_title' => 'ទូទាត់',
        'checkout_success_title' => 'បញ្ជាទិញបានជោគជ័យ',
        'checkout_success_text' => 'អរគុណសម្រាប់ការបញ្ជាទិញ។ ពួកយើងនឹងចាប់ផ្តើមរៀបចំអាហារភ្លាមៗ។',
        'checkout_back_home' => 'ត្រឡប់ទៅទំព័រដើម',
        'checkout_delivery_title' => 'ព័ត៌មានដឹកជញ្ជូន',
        'checkout_full_name' => 'ឈ្មោះពេញ *',
        'checkout_phone' => 'លេខទូរស័ព្ទ *',
        'checkout_email' => 'អ៊ីមែល',
        'checkout_address' => 'អាសយដ្ឋានដឹកជញ្ជូន *',
        'checkout_payment_title' => 'វិធីសាស្ត្រទូទាត់',
        'checkout_wallet_help' => 'សូមបង់តាមកាបូបអេឡិចត្រូនិក រួចបិទភ្ជាប់លេខយោងនៅខាងក្រោម។ ការបញ្ជាទិញនឹងស្ថិតក្នុងស្ថានភាពរង់ចាំ រហូតដល់អេដមីនបញ្ជាក់។',
        'checkout_wallet_reference' => 'លេខយោងប្រតិបត្តិការ *',
        'checkout_place_order' => 'ដាក់ការបញ្ជាទិញ',
        'payment_cash_on_delivery' => 'បង់ជាសាច់ប្រាក់ពេលទទួល',
        'payment_card_on_delivery' => 'បង់ដោយកាតពេលទទួល',
        'payment_wallet_qr' => 'កាបូបអេឡិចត្រូនិក / QR Pay',
        'checkout_order_summary' => 'សង្ខេបការបញ្ជាទិញ',
        'checkout_total' => 'សរុប:',
        'checkout_empty_cart' => 'កន្ត្រករបស់អ្នកទទេ។',

        'error_empty_cart' => 'កន្ត្រករបស់អ្នកទទេ។',
        'error_invalid_payment' => 'សូមជ្រើសវិធីសាស្ត្រទូទាត់ត្រឹមត្រូវ។',
        'error_required_fields' => 'សូមបំពេញព័ត៌មានចាំបាច់ទាំងអស់ (ឈ្មោះ លេខទូរស័ព្ទ អាសយដ្ឋាន)។',
        'error_wallet_not_configured' => 'ការទូទាត់តាមកាបូបអេឡិចត្រូនិកមិនទាន់បានកំណត់រចនាសម្ព័ន្ធទេ។ សូមធ្វើបច្ចុប្បន្នភាពមូលដ្ឋានទិន្នន័យ ឬជ្រើសវិធីផ្សេង។',
        'error_wallet_reference_required' => 'សូមបញ្ចូលលេខយោងប្រតិបត្តិការកាបូបអេឡិចត្រូនិក។',
        'error_wallet_reference_too_long' => 'លេខយោងប្រតិបត្តិការវែងពេក។',
        'error_processing_order' => 'មានបញ្ហាក្នុងការដំណើរការការបញ្ជាទិញ:',
    ],
];

function get_current_lang(): string
{
    return APP_LANG;
}

function get_supported_languages(): array
{
    global $supported_languages;
    return $supported_languages;
}

function t(string $key, string $fallback = ''): string
{
    global $translations;

    $lang = get_current_lang();
    if (isset($translations[$lang][$key])) {
        return $translations[$lang][$key];
    }

    return $fallback !== '' ? $fallback : $key;
}

function localized_url(string $path, array $params = []): string
{
    $fragment = '';
    if (strpos($path, '#') !== false) {
        [$path, $fragment] = explode('#', $path, 2);
    }

    $existing_query = [];
    if (strpos($path, '?') !== false) {
        [$path, $query_string] = explode('?', $path, 2);
        parse_str($query_string, $existing_query);
    }

    $query = array_merge($existing_query, $params, ['lang' => get_current_lang()]);

    $url = $path;
    if (!empty($query)) {
        $url .= '?' . http_build_query($query);
    }

    if ($fragment !== '') {
        $url .= '#' . $fragment;
    }

    return $url;
}

function lang_switch_url(string $lang): string
{
    $supported = get_supported_languages();
    if (!array_key_exists($lang, $supported)) {
        $lang = 'en';
    }

    $request_uri = $_SERVER['REQUEST_URI'] ?? ($_SERVER['PHP_SELF'] ?? 'index.php');
    $path = parse_url($request_uri, PHP_URL_PATH);
    if ($path === null || $path === false || $path === '') {
        $path = basename($_SERVER['PHP_SELF'] ?? 'index.php');
    }

    $query = $_GET;
    $query['lang'] = $lang;

    return $path . '?' . http_build_query($query);
}

function get_current_font(): string
{
    $lang = get_current_lang();
    return ($lang === 'km') ? 'Kontumruy Pro, "Google Sans", sans-serif' : 'DM Sans, "Segoe UI", sans-serif';
}

function is_rtl_language(): bool
{
    return get_current_lang() === 'km';
}

function get_font_url(): string
{
    $lang = get_current_lang();
    if ($lang === 'km') {
        return 'https://fonts.googleapis.com/css2?family=Kontumruy+Pro:wght@400;500;700&display=swap';
    }
    return '';
}
