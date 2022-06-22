<?php

namespace App\Http\Controllers;

use App\Mail\TestMail;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;

class SettingController extends Controller
{

    public function index()
    {
        if(\Auth::user()->type == 'company')
        {
            $settings  = Utility::settings();
            $timezones = config('timezones');
            $company_payment_setting = Utility::getCompanyPaymentSetting();

            return view('settings.index', compact('settings', 'timezones','company_payment_setting'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    public function saveBusinessSettings(Request $request)
    {
        $user = \Auth::user();
        if(\Auth::user()->type == 'company')
        {

            if($request->logo)
            {
                $request->validate(['logo' => 'required|image|mimes:jpeg,jpg,png|max:204800']);
                $request->logo->storeAs('uploads/logo', 'logo-dark.png');
            }

            if($request->white_logo)
            {
                $request->validate(['white_logo' => 'required|image|mimes:jpeg,jpg,png|max:204800']);
                $request->white_logo->storeAs('uploads/logo', 'logo-light.png');
            }


            if($request->favicon)
            {
                $request->validate(
                    [
                        'favicon' => 'image|mimes:png|max:20480',
                    ]
                );
                $favicon = 'favicon.png';
                $path    = $request->file('favicon')->storeAs('uploads/logo/', $favicon);
                \DB::insert(
                    'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                                                                                                                                 $favicon,
                                                                                                                                                 'favicon',
                                                                                                                                                 \Auth::user()->creatorId(),
                                                                                                                                             ]
                );
            }

            $arrEnv = [
                'SITE_RTL' => !isset($request->SITE_RTL) ? 'off' : 'on',
                'THEME_COLOR' => $request->color, 
            ];

            $request->user = \Auth::user()->id;

            Artisan::call('config:cache');
            Artisan::call('config:clear');

            Utility::setEnvironmentValue($arrEnv);


            if(!empty($request->title_text) || !empty($request->footer_text) || !empty($request->default_language) || isset($request->display_landing_page) || isset($request->gdpr_cookie)|| isset($request->color) || isset($request->cust_theme_bg) || isset($request->cust_darklayout))
            {
                $post = $request->all();
                unset($post['_token'], $post['logo'], $post['small_logo'], $post['favicon']);
                if(!isset($request->display_landing_page))
                {
                    $post['display_landing_page'] = 'off';
                }
                 if(!isset($request->gdpr_cookie))
                {
                    $post['gdpr_cookie'] = 'off';
                }

                if(!isset($request->color))
                    {
                        $color = $request->has('color') ? $request-> color : 'theme-3';
                        $post['color'] = $color;
                    }
                    if(!isset($request->cust_theme_bg))
                    {
                        $cust_theme_bg         = (isset($request->cust_theme_bg)) ? 'on' : 'off';
                        $post['cust_theme_bg'] = $cust_theme_bg;
                    }

                    if(!isset($request->cust_darklayout))
                    {
                        $post['cust_darklayout'] = 'off';
                    }
                   

                $settings = Utility::settings();

                foreach($post as $key => $data)
                {
                    if(in_array($key, array_keys($settings)))
                    {
                        \DB::insert(
                            'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                                                                                                                                         $data,
                                                                                                                                                         $key,
                                                                                                                                                          $request->user,
                                                                                                                                                     ]
                        );
                    }

                }
               
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

        return redirect()->back()->with('success', 'Business setting succefully saved.');
    }

    public function saveCompanySettings(Request $request)
    {

        if(\Auth::user()->type == 'company')
        {
            $request->validate(
                [
                    'company_name' => 'required|string',
                    'company_email' => 'required',
                    'company_email_from_name' => 'required|string',
                    'company_address' => 'required',
                    'company_city' => 'required',
                    'company_state' => 'required',
                    'company_zipcode' => 'required',
                    'company_country' => 'required',
                    'company_telephone' => 'required',
                    'timezone' => 'required',
                    'registration_number' => 'required|string',
                ]
            );
            $post = $request->all();
            unset($post['_token']);

            $settings = Utility::settings();
            foreach($post as $key => $data)
            {
                if(in_array($key, array_keys($settings)))
                {
                    \DB::insert(
                        'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                                                                                                                                     $data,
                                                                                                                                                     $key,
                                                                                                                                                     \Auth::user()->creatorId(),
                                                                                                                                                 ]
                    );
                }

            }

            $arrEnv = [
                'TIMEZONE' => $request->timezone,
            ];

            $request->user = \Auth::user()->id;

            Artisan::call('config:cache');
            Artisan::call('config:clear');

            Utility::setEnvironmentValue($arrEnv);

            return redirect()->back()->with('success', __('Setting successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function saveEmailSettings(Request $request)
    {

        if(\Auth::user()->type == 'company')
        {
            $request->validate(
                [
                    'mail_driver' => 'required|string|max:50',
                    'mail_host' => 'required|string|max:50',
                    'mail_port' => 'required|string|max:50',
                    'mail_username' => 'required|string|max:50',
                    'mail_password' => 'required|string|max:50',
                    'mail_encryption' => 'required|string|max:50',
                    'mail_from_address' => 'required|string|max:50',
                    'mail_from_name' => 'required|string|max:50',
                ]
            );

            $arrEnv = [
                'MAIL_DRIVER' => $request->mail_driver,
                'MAIL_HOST' => $request->mail_host,
                'MAIL_PORT' => $request->mail_port,
                'MAIL_USERNAME' => $request->mail_username,
                'MAIL_PASSWORD' => $request->mail_password,
                'MAIL_ENCRYPTION' => $request->mail_encryption,
                'MAIL_FROM_NAME' => $request->mail_from_name,
                'MAIL_FROM_ADDRESS' => $request->mail_from_address,
            ];
            Utility::setEnvironmentValue($arrEnv);

            return redirect()->back()->with('success', __('Setting successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }


    }

    public function saveSystemSettings(Request $request)
    {

        if(\Auth::user()->type == 'company')
        {
            $request->validate(
                [
                    'site_currency' => 'required',
                ]
            );
            $post = $request->all();
            // dd($post);
            unset($post['_token']);

            foreach($post as $key => $data)
            {
                \DB::insert(
                    'insert into settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) 
                    values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                                                                                                                                                                 $data,
                                                                                                                                                                                 $key,
                                                                                                                                                                                 \Auth::user()->creatorId(),
                                                                                                                                                                                 date('Y-m-d H:i:s'),
                                                                                                                                                                                 date('Y-m-d H:i:s'),
                                                                                                                                                                             ]
                );
            }

            return redirect()->back()->with('success', __('Setting successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function savePusherSettings(Request $request)
    {
        if(\Auth::user()->type == 'company')
        {
            $request->validate(
                [
                    'pusher_app_id' => 'required',
                    'pusher_app_key' => 'required',
                    'pusher_app_secret' => 'required',
                    'pusher_app_cluster' => 'required',
                ]
            );

            $arrEnvStripe = [
                'PUSHER_APP_ID' => $request->pusher_app_id,
                'PUSHER_APP_KEY' => $request->pusher_app_key,
                'PUSHER_APP_SECRET' => $request->pusher_app_secret,
                'PUSHER_APP_CLUSTER' => $request->pusher_app_cluster,
            ];

            $request->user = \Auth::user()->id;

            Artisan::call('config:cache');
            Artisan::call('config:clear');
            

            $envStripe = Utility::setEnvironmentValue($arrEnvStripe);

            if($envStripe)
            {
                return redirect()->back()->with('success', __('Pusher successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', 'Something went wrong.');
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }


    }

    public function savePaymentSettings(Request $request)
    {
        if(\Auth::user()->type == 'company')
        {
            $request->validate(
                [
                    'currency' => 'required|string|max:255',
                    'currency_symbol' => 'required|string|max:255',
                ]
            );

            if(isset($request->enable_stripe) && $request->enable_stripe == 'on')
            {
                $request->validate(
                    [
                        'stripe_key' => 'required|string|max:255',
                        'stripe_secret' => 'required|string|max:255',
                    ]
                );
            }
            elseif(isset($request->enable_paypal) && $request->enable_paypal == 'on')
            {
                $request->validate(
                    [
                        'paypal_mode' => 'required|string',
                        'paypal_client_id' => 'required|string',
                        'paypal_secret_key' => 'required|string',
                    ]
                );
            }


            $arrEnv = [
                'CURRENCY_SYMBOL' => $request->currency_symbol,
                'CURRENCY' => $request->currency,
                'ENABLE_STRIPE' => $request->enable_stripe ?? 'off',
                'STRIPE_KEY' => $request->stripe_key,
                'STRIPE_SECRET' => $request->stripe_secret,
                'ENABLE_PAYPAL' => $request->enable_paypal ?? 'off',
                'PAYPAL_MODE' => $request->paypal_mode,
                'PAYPAL_CLIENT_ID' => $request->paypal_client_id,
                'PAYPAL_SECRET_KEY' => $request->paypal_secret_key,

            ];

            $request->user = \Auth::user()->id;

            Artisan::call('config:cache');
            Artisan::call('config:clear');

            Utility::setEnvironmentValue($arrEnv);

            $post = $request->all();
            unset($post['_token'], $post['stripe_key'], $post['stripe_secret']);

            $settings = Utility::settings();
            foreach($post as $key => $data)
            {
                if(in_array($key, array_keys($settings)))
                {
                    \DB::insert(
                        'insert into settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                                                                                                                                                                     $data,
                                                                                                                                                                                     $key,
                                                                                                                                                                                     $request->user,
                                                                                                                                                                                     date('Y-m-d H:i:s'),
                                                                                                                                                                                     date('Y-m-d H:i:s'),
                                                                                                                                                                                 ]
                    );
                }

            }

            return redirect()->back()->with('success', __('Payment setting successfully saved.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function saveCompanyPaymentSettings(Request $request)
    {

        if(isset($request->is_stripe_enabled) && $request->is_stripe_enabled == 'on')
        {

            $request->validate(
                [
                    'stripe_key' => 'required|string|max:255',
                    'stripe_secret' => 'required|string|max:255',
                ]
            );

            $post['is_stripe_enabled'] = $request->is_stripe_enabled;
            $post['stripe_secret']     = $request->stripe_secret;
            $post['stripe_key']        = $request->stripe_key;
        }

        else
        {
            $post['is_stripe_enabled'] = 'off';
        }

        if(isset($request->is_paypal_enabled) && $request->is_paypal_enabled == 'on')
        {
            $request->validate(
                [
                    'paypal_mode' => 'required',
                    'paypal_client_id' => 'required',
                    'paypal_secret_key' => 'required',
                ]
            );

            $post['is_paypal_enabled'] = $request->is_paypal_enabled;
            $post['paypal_mode']       = $request->paypal_mode;
            $post['paypal_client_id']  = $request->paypal_client_id;
            $post['paypal_secret_key'] = $request->paypal_secret_key;
        }
        else
        {
            $post['is_paypal_enabled'] = 'off';
        }

        if(isset($request->is_paystack_enabled) && $request->is_paystack_enabled == 'on')
        {
            $request->validate(
                [
                    'paystack_public_key' => 'required|string',
                    'paystack_secret_key' => 'required|string',
                ]
            );
            $post['is_paystack_enabled'] = $request->is_paystack_enabled;
            $post['paystack_public_key'] = $request->paystack_public_key;
            $post['paystack_secret_key'] = $request->paystack_secret_key;
        }
        else
        {
            $post['is_paystack_enabled'] = 'off';
        }

        if(isset($request->is_flutterwave_enabled) && $request->is_flutterwave_enabled == 'on')
        {
            $request->validate(
                [
                    'flutterwave_public_key' => 'required|string',
                    'flutterwave_secret_key' => 'required|string',
                ]
            );
            $post['is_flutterwave_enabled'] = $request->is_flutterwave_enabled;
            $post['flutterwave_public_key'] = $request->flutterwave_public_key;
            $post['flutterwave_secret_key'] = $request->flutterwave_secret_key;
        }
        else
        {
            $post['is_flutterwave_enabled'] = 'off';
        }
        if(isset($request->is_razorpay_enabled) && $request->is_razorpay_enabled == 'on')
        {
            $request->validate(
                [
                    'razorpay_public_key' => 'required|string',
                    'razorpay_secret_key' => 'required|string',
                ]
            );
            $post['is_razorpay_enabled'] = $request->is_razorpay_enabled;
            $post['razorpay_public_key'] = $request->razorpay_public_key;
            $post['razorpay_secret_key'] = $request->razorpay_secret_key;
        }
        else
        {
            $post['is_razorpay_enabled'] = 'off';
        }

    
        if(isset($request->is_mercado_enabled) && $request->is_mercado_enabled == 'on')
        {
            $request->validate(
                [
                    'mercado_access_token' => 'required|string',
                ]
            );
            $post['is_mercado_enabled'] = $request->is_mercado_enabled;
            $post['mercado_access_token']     = $request->mercado_access_token;
            $post['mercado_mode'] = $request->mercado_mode;
        }
        else
        {
            $post['is_mercado_enabled'] = 'off';
        }

        if(isset($request->is_paytm_enabled) && $request->is_paytm_enabled == 'on')
        {
            $request->validate(
                [
                    'paytm_mode' => 'required',
                    'paytm_merchant_id' => 'required|string',
                    'paytm_merchant_key' => 'required|string',
                    'paytm_industry_type' => 'required|string',
                ]
            );
            $post['is_paytm_enabled']    = $request->is_paytm_enabled;
            $post['paytm_mode']          = $request->paytm_mode;
            $post['paytm_merchant_id']   = $request->paytm_merchant_id;
            $post['paytm_merchant_key']  = $request->paytm_merchant_key;
            $post['paytm_industry_type'] = $request->paytm_industry_type;
        }
        else
        {
            $post['is_paytm_enabled'] = 'off';
        }
        if(isset($request->is_mollie_enabled) && $request->is_mollie_enabled == 'on')
        {
            $request->validate(
                [
                    'mollie_api_key' => 'required|string',
                    'mollie_profile_id' => 'required|string',
                    'mollie_partner_id' => 'required',
                ]
            );
            $post['is_mollie_enabled'] = $request->is_mollie_enabled;
            $post['mollie_api_key']    = $request->mollie_api_key;
            $post['mollie_profile_id'] = $request->mollie_profile_id;
            $post['mollie_partner_id'] = $request->mollie_partner_id;
        }
        else
        {
            $post['is_mollie_enabled'] = 'off';
        }

        if(isset($request->is_skrill_enabled) && $request->is_skrill_enabled == 'on')
        {
            $request->validate(
                [
                    'skrill_email' => 'required|email',
                ]
            );
            $post['is_skrill_enabled'] = $request->is_skrill_enabled;
            $post['skrill_email']      = $request->skrill_email;
        }
        else
        {
            $post['is_skrill_enabled'] = 'off';
        }

        if(isset($request->is_coingate_enabled) && $request->is_coingate_enabled == 'on')
        {
            $request->validate(
                [
                    'coingate_mode' => 'required|string',
                    'coingate_auth_token' => 'required|string',
                ]
            );

            $post['is_coingate_enabled'] = $request->is_coingate_enabled;
            $post['coingate_mode']       = $request->coingate_mode;
            $post['coingate_auth_token'] = $request->coingate_auth_token;
        }
        else
        {
            $post['is_coingate_enabled'] = 'off';
        }

        if(isset($request->is_paymentwall_enabled) && $request->is_paymentwall_enabled == 'on')
        {
            $request->validate(
                [
                    'paymentwall_public_key' => 'required|string',
                    'paymentwall_private_key' => 'required|string',
                ]
            );
            $post['is_paymentwall_enabled'] = $request->is_paymentwall_enabled;
            $post['paymentwall_public_key'] = $request->paymentwall_public_key;
            $post['paymentwall_private_key'] = $request->paymentwall_private_key;
        }
        else
        {
            $post['is_paymentwall_enabled'] = 'off';
        }

        foreach($post as $key => $data)
        {

            $arr = [
                $data,
                $key,
                \Auth::user()->id,
            ];
            \DB::insert(
                'insert into company_payment_settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', $arr
            );

        }

        return redirect()->back()->with('success', __('Payment setting successfully updated.'));

    }

    public function testMail()
    {
        return view('settings.test_mail');
    }


    public function testSendMail(Request $request)
    {
        $validator = \Validator::make($request->all(), ['email' => 'required|email']);
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        try
        {
            
            Mail::to($request->email)->send(new TestMail());
        }
        catch(\Exception $e)
        {
            $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
        }
        //dd('jfg');
        return redirect()->back()->with('success', __('Email send Successfully.') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''));

    }

    public function saveZoomSettings(Request $request)
    {
        $post = $request->all();

        unset($post['_token']);
        $created_by = \Auth::user()->creatorId();
        
        foreach($post as $key => $data)
        {
            \DB::insert(
                'insert into settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                                                                                                                                                                $data,
                                                                                                                                                                                $key,
                                                                                                                                                                                $created_by,
                                                                                                                                                                                date('Y-m-d H:i:s'),
                                                                                                                                                                                date('Y-m-d H:i:s'),
                                                                                                                                                                            ]
            );
        }
        return redirect()->back()->with('success', __('Setting added successfully saved.'));
    }

     public function slack(Request $request){  

        $post = [];
        $post['slack_webhook'] = $request->input('slack_webhook');
        $post['holiday_create_notification'] = $request->has('holiday_create_notification')?$request->input('holiday_create_notification'):0;
        $post['meeting_create_notification'] = $request->has('meeting_create_notification')?$request->input('meeting_create_notification'):0;
        $post['company_policy_create_notification'] = $request->has('company_policy_create_notification')?$request->input('company_policy_create_notification'):0;
        $post['award_create_notification'] = $request->has('award_create_notification')?$request->input('award_create_notification'):0;
        $post['lead_create_notification'] = $request->has('lead_create_notification')?$request->input('lead_create_notification'):0;
        $post['deal_create_notification'] = $request->has('deal_create_notification')?$request->input('deal_create_notification'):0;
        $post['convert_lead_to_deal_notification'] = $request->has('convert_lead_to_deal_notification')?$request->input('convert_lead_to_deal_notification'):0;
        $post['estimation_create_notification'] = $request->has('estimation_create_notification')?$request->input('estimation_create_notification'):0;
        $post['project_create_notification'] = $request->has('project_create_notification')?$request->input('project_create_notification'):0;
        $post['project_status_updated_notification'] = $request->has('project_status_updated_notification')?$request->input('project_status_updated_notification'):0;
        $post['task_create_notification'] = $request->has('task_create_notification')?$request->input('task_create_notification'):0;
        $post['task_move_notification'] = $request->has('task_move_notification')?$request->input('task_move_notification'):0;
        $post['task_comment_notification'] = $request->has('task_comment_notification')?$request->input('task_comment_notification'):0;
        $post['milestone_create_notification'] = $request->has('milestone_create_notification')?$request->input('milestone_create_notification'):0;
        $post['invoice_create_notification'] = $request->has('invoice_create_notification')?$request->input('invoice_create_notification'):0;
        $post['invoice_status_updated_notification'] = $request->has('invoice_status_updated_notification')?$request->input('invoice_status_updated_notification'):0;
        $post['payment_create_notification'] = $request->has('payment_create_notification')?$request->input('payment_create_notification'):0;
        $post['contract_create_notification'] = $request->has('contract_create_notification')?$request->input('contract_create_notification'):0;
        $post['support_create_notification'] = $request->has('support_create_notification')?$request->input('support_create_notification'):0;
        $post['event_create_notification'] = $request->has('event_create_notification')?$request->input('event_create_notification'):0;
        $created_by = \Auth::user()->creatorId();
        if(isset($post) && !empty($post) && count($post) > 0)
        {
            $created_at = $updated_at = date('Y-m-d H:i:s');

            foreach($post as $key => $data)
            {
                \DB::insert(
                    'INSERT INTO settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), `updated_at` = VALUES(`updated_at`) ', [                                                                                                                                                                             $data,                                                                                                                                                                                                             $key,                                                                                                                                                                                                                      $created_by,
                                                                                                                                                                                                                      $created_at,
                                                                                                                                                                                                                      $updated_at,
                                                                                                                                                                                                                  ]
                );
            }
        }


        return redirect()->back()->with('success', __('Settings updated successfully.'));
    }

    public function telegram(Request $request)
    {
       
        $post = [];
        $post['telegrambot'] = $request->input('telegrambot');
        $post['telegramchatid'] = $request->input('telegramchatid');
        $post['telegram_holiday_create_notification'] = $request->has('telegram_holiday_create_notification')?$request->input('telegram_holiday_create_notification'):0;
        $post['telegram_meeting_create_notification'] = $request->has('telegram_meeting_create_notification')?$request->input('telegram_meeting_create_notification'):0;
        $post['telegram_company_policy_create_notification'] = $request->has('telegram_company_policy_create_notification')?$request->input('telegram_company_policy_create_notification'):0;
        $post['telegram_award_create_notification'] = $request->has('telegram_award_create_notification')?$request->input('telegram_award_create_notification'):0;
        $post['telegram_lead_create_notification'] = $request->has('telegram_lead_create_notification')?$request->input('telegram_lead_create_notification'):0;
        $post['telegram_deal_create_notification'] = $request->has('telegram_deal_create_notification')?$request->input('telegram_deal_create_notification'):0;
        $post['telegram_convert_lead_to_deal_notification'] = $request->has('telegram_convert_lead_to_deal_notification')?$request->input('telegram_convert_lead_to_deal_notification'):0;
        $post['telegram_estimation_create_notification'] = $request->has('telegram_estimation_create_notification')?$request->input('telegram_estimation_create_notification'):0;
        $post['telegram_project_create_notification'] = $request->has('telegram_project_create_notification')?$request->input('telegram_project_create_notification'):0;
        $post['telegram_project_status_updated_notification'] = $request->has('telegram_project_status_updated_notification')?$request->input('telegram_project_status_updated_notification'):0;
        $post['telegram_task_create_notification'] = $request->has('telegram_task_create_notification')?$request->input('telegram_task_create_notification'):0;
        $post['telegram_task_move_notification'] = $request->has('telegram_task_move_notification')?$request->input('telegram_task_move_notification'):0;
        $post['telegram_task_comment_notification'] = $request->has('telegram_task_comment_notification')?$request->input('telegram_task_comment_notification'):0;
        $post['telegram_milestone_create_notification'] = $request->has('telegram_milestone_create_notification')?$request->input('telegram_milestone_create_notification'):0;
        $post['telegram_invoice_create_notification'] = $request->has('telegram_invoice_create_notification')?$request->input('telegram_invoice_create_notification'):0;
        $post['telegram_invoice_status_updated_notification'] = $request->has('telegram_invoice_status_updated_notification')?$request->input('telegram_invoice_status_updated_notification'):0;
        $post['telegram_payment_create_notification'] = $request->has('telegram_payment_create_notification')?$request->input('telegram_payment_create_notification'):0;
        $post['telegram_contract_create_notification'] = $request->has('telegram_contract_create_notification')?$request->input('telegram_contract_create_notification'):0;
        $post['telegram_support_create_notification'] = $request->has('telegram_support_create_notification')?$request->input('telegram_support_create_notification'):0;
        $post['telegram_event_create_notification'] = $request->has('telegram_event_create_notification')?$request->input('telegram_event_create_notification'):0;

        $created_by = \Auth::user()->creatorId();
        if(isset($post) && !empty($post) && count($post) > 0)
        {
            $created_at = $updated_at = date('Y-m-d H:i:s');

            foreach($post as $key => $data)
            {
                \DB::insert(
                'insert into settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                                                                                                                                                                $data,
                                                                                                                                                                                $key,
                                                                                                                                                                                $created_by,
                                                                                                                                                                                date('Y-m-d H:i:s'),
                                                                                                                                                                                date('Y-m-d H:i:s'),
                                                                                                                                                                            ]
            );
            }
        }
        return redirect()->back()->with('success', __('Setting added successfully saved.'));
    }

    public function twilio(Request $request)
    {
      
        $post = [];
        $post['twilio_sid'] = $request->input('twilio_sid');
        $post['twilio_token'] = $request->input('twilio_token');
        $post['twilio_from'] = $request->input('twilio_from');
        $post['twilio_leave_approve_reject_notification'] = $request->has('twilio_leave_approve_reject_notification')?$request->input('twilio_leave_approve_reject_notification'):0;
        $post['twilio_award_create_notification'] = $request->has('twilio_award_create_notification')?$request->input('twilio_award_create_notification'):0;
        $post['twilio_trip_create_notification'] = $request->has('twilio_trip_create_notification')?$request->input('twilio_trip_create_notification'):0;
        $post['twilio_ticket_create_notification'] = $request->has('twilio_ticket_create_notification')?$request->input('twilio_ticket_create_notification'):0;
        $post['twilio_event_create_notification'] = $request->has('twilio_event_create_notification')?$request->input('twilio_event_create_notification'):0;
        $post['twilio_project_create_notification'] = $request->has('twilio_project_create_notification')?$request->input('twilio_project_create_notification'):0;
        $post['twilio_task_create_notification'] = $request->has('twilio_task_create_notification')?$request->input('twilio_task_create_notification'):0;
        $post['twilio_contract_create_notification'] = $request->has('twilio_contract_create_notification')?$request->input('twilio_contract_create_notification'):0;
        $post['twilio_invoice_create_notification'] = $request->has('twilio_invoice_create_notification')?$request->input('twilio_invoice_create_notification'):0;
        $post['twilio_invoice_payment_create_notification'] = $request->has('twilio_invoice_payment_create_notification')?$request->input('twilio_invoice_payment_create_notification'):0;
        $post['twilio_payment_create_notification'] = $request->has('twilio_payment_create_notification')?$request->input('twilio_payment_create_notification'):0;

        $created_by = \Auth::user()->creatorId();
        if(isset($post) && !empty($post) && count($post) > 0)
        {
            $created_at = $updated_at = date('Y-m-d H:i:s');

            foreach($post as $key => $data)
            {
                \DB::insert(
                'insert into settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                                                                                                                                                                $data,
                                                                                                                                                                                $key,
                                                                                                                                                                                $created_by,
                                                                                                                                                                                date('Y-m-d H:i:s'),
                                                                                                                                                                                date('Y-m-d H:i:s'),
                                                                                                                                                                            ]
            );
            }
        }
        return redirect()->back()->with('success', __('Setting added successfully saved.'));
    }

    public function recaptchaSettingStore(Request $request)
    {
        //return redirect()->back()->with('error', __('This operation is not perform due to demo mode.'));
        $user = \Auth::user();
        $rules = [];
        if($request->recaptcha_module == 'yes')
        {
            $rules['google_recaptcha_key'] = 'required|string|max:50';
            $rules['google_recaptcha_secret'] = 'required|string|max:50';
        }
        $validator = \Validator::make(
            $request->all(), $rules
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        $arrEnv = [
            'RECAPTCHA_MODULE' => $request->recaptcha_module ?? 'no',
            'NOCAPTCHA_SITEKEY' => $request->google_recaptcha_key,
            'NOCAPTCHA_SECRET' => $request->google_recaptcha_secret,
        ];
        if(Utility::setEnvironmentValue($arrEnv))
        {
            return redirect()->back()->with('success', __('Recaptcha Settings updated successfully'));
        }
        else
        {
            return redirect()->back()->with('error', __('Something is wrong'));
        }
    }
        
}
