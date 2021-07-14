<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
namespace app\modules\api\controllers;

use app\components\filters\AccessControl;
use app\components\filters\AccessRule;
# use app\modules\payment\models\Transaction;
use yii\data\ActiveDataProvider;
use app\modules\api\components\ApiBaseController;
use app\modules\payment\models\Gateway;
use app\models\User;
use app\models\AccountInfo;
use PHPUnit\Exception;
use app\modules\payment\models\Transaction;
use Stripe\Exception\ExceptionInterface;
use app\models\PaymentTransaction;
use app\modules\order\models\Order;
use app\models\Charity;

/**
 * TransactionsController implements the API actions for Transaction model.
 */
class TransactionsController extends ApiBaseController
{

    public $modelClass = "app\modules\payment\models\Transaction";

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::class
                ],
                'rules' => [
                    [
                        'actions' => [
                            'create-account',
                            'update-account',
                            'delete-account',
                            'set-default-account',
                            'view-account',
                            'get-account',
                            'verify-account',
                            'add-card',
                            'card-list',
                            'card-delete',
                            'set-default',
                            'booking-payment',
                            'order-payment'
                        ],
                        'allow' => true,
                        'roles' => [
                            '@'
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * Updates an existing Transaction model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed public function actionMyUpdate($id)
     *         {
     *         $data = [ ];
     *         $model=$this->findModel($id);
     *         if ($model->load(\Yii::$app->request->post())) {
     *        
     *         if ($model->save()) {
     *         $data ['status'] = self::API_OK;
     *         $data ['detail'] = $model;
     *        
     *         } else {
     *         $data['error'] = $model->flattenErrors;
     *         }
     *         } else {
     *         $data['error_post'] = 'No Data Posted';
     *         }
     *        
     *         return $data;
     *         }
     */
    public function actionCreateAccount()
    {
        $response = [];

        \Yii::$app->response->format = 'json';

        $payment_details = Gateway::find()->one();
        if (! empty($payment_details)) {
            $stripe_keys = json_decode($payment_details->value);
            $secret_key = $stripe_keys->secret_key;
        }
        \Stripe\Stripe::setApiKey($secret_key);
        \Stripe\Stripe::setApiVersion(STRIPE_API_VERSION);
        $post = \Yii::$app->request->bodyParams;
        $accountInfo = new AccountInfo();
        $getUser = User::findOne(\Yii::$app->user->id);
        if ($accountInfo->load($post)) {

            try {

                $account_holder_type = '';
                if ($accountInfo->type_id == AccountInfo::ACCOUNT_HOLDER_TYPE_INDIVIDUAL) {

                    $account_holder_type = 'individual';
                } else {
                    if ($accountInfo->type_id == AccountInfo::ACCOUNT_HOLDER_TYPE_COMPANY) {
                        $account_holder_type = 'company';
                    }
                }

                $account_detail = \Stripe\Token::create([

                    'bank_account' => [
                        'country' => 'US',
                        'currency' => 'usd',
                        'account_holder_name' => $accountInfo->account_holder_name,
                        'account_holder_type' => $account_holder_type,
                        'routing_number' => $accountInfo->routing_number,
                        'account_number' => $accountInfo->account_number
                    ]
                ]);

                if (! empty($account_detail->id)) {

                    if (! empty($getUser) && ! empty($getUser->customer_id)) {

                        $user = AccountInfo::find()->where([
                            'user_id' => \Yii::$app->user->id
                        ])->one();

                        if (! empty($user)) {
                            try {
                                $bank_account = \Stripe\Customer::retrieveSource($getUser->customer_id, $account_detail->id);
                            } catch (\Stripe\Exception\ExceptionInterface $e) {
                                $response['error'] = 'Ithroe' . $e->getMessage();
                            }
                        } else {

                            $bank_account = \Stripe\Customer::createSource($getUser->customer_id, [
                                'source' => $account_detail->id
                            ]);
                        }

                        if ($accountInfo->is_default_account == AccountInfo::STATE_ACTIVE) {
                            AccountInfo::updateAll([
                                'is_default_account' => AccountInfo::STATE_INACTIVE
                            ], [
                                'created_by_id' => $getUser->id
                            ]);
                        }
                        if (! empty($bank_account->id)) {

                            $accountInfo->is_verify = AccountInfo::NOT_VERIFIED;
                            $accountInfo->user_id = $getUser->id;
                            $accountInfo->bank_name = isset($account_detail->bank_account) ? $account_detail->bank_account->bank_name : ''; // $account_detail->bank_name;
                            $accountInfo->routing_number = $accountInfo->routing_number;
                            $accountInfo->account_number = $accountInfo->account_number;
                            $accountInfo->state_id = AccountInfo::STATE_ACTIVE;
                            $accountInfo->customer_id = $getUser->customer_id;
                            $accountInfo->bank_token = $account_detail->id;
                            $accountInfo->bank_id = $bank_account->id;
                            $accountInfo->account_holder_name = isset($account_detail->bank_account) ? $account_detail->bank_account->account_holder_name : '';
                            $accountInfo->type_id = $accountInfo->type_id;

                            if ($accountInfo->save()) {

                                $response['detail'] = $accountInfo->asJson();
                                // fetch default account of this user
                                // if default account not set
                                // then set this account as default

                                // check if there is any default

                                $bankAccounts = AccountInfo::find()->where([
                                    'user_id' => $getUser->id,
                                    'is_default_account' => AccountInfo::IS_DEFAULT
                                ])->exists();

                                if (empty($bankAccounts)) {

                                    $accountInfo->is_default_account = AccountInfo::IS_DEFAULT;

                                    $accountInfo->updateAttributes([
                                        'is_default_account'
                                    ]);
                                }

                                $this->setStatus(200);
                                $response['success'] = \Yii::t('app', 'Bank Account Added Succesfully.');
                            } else {
                                $this->setStatus(400);
                                $response['error'] = $accountInfo->getErrors();
                            }
                        } else {
                            $this->setStatus(400);
                            $response['error'] = \Yii::t('app', 'Account already Exists');
                        }
                    } else {
                        $this->setStatus(400);
                        $response['error'] = \Yii::t('app', 'Create an customer first .');
                    }
                } else {
                    $this->setStatus(400);
                    $response['error'] = \Yii::t('app', 'Error saving card details');
                }
                // associate that token with a customer and now onwards only token will be used instead of all info
            } catch (\yii\base\Exception $e) {
                $response['error'] = 'Ithroe' . $e->getMessage();
            }
        } else {
            $this->setStatus(400);
            $response['error'] = \yii::t('app', 'No Data posted');
        }

        return $response;
    }

    // UPDATE A BANK ACCOUNT
    public function actionUpdateAccount($id)
    {
        $response = [];
        $post = \Yii::$app->request->bodyParams;

        // update a card

        $accountInfo = new AccountInfo();

        $stripeDetails = User::getStripeDetails();
        \Stripe\Stripe::setApiKey($stripeDetails->secret_key->value);
        \Stripe\Stripe::setApiVersion(STRIPE_API_VERSION);

        if ($accountInfo->load($post)) {

            $getUser = \Yii::$app->user->identity;

            $bankAccounts = AccountInfo::find()->where([
                'user_id' => $getUser->id,
                'id' => $id
            ])->one();

            if (! empty($bankAccounts)) {

                if (! empty($getUser)) {

                    $account_holder_type = '';

                    if ($accountInfo->type_id == AccountInfo::ACCOUNT_HOLDER_TYPE_INDIVIDUAL) {
                        $account_holder_type = 'individual';
                    } else {
                        if ($accountInfo->type_id == AccountInfo::ACCOUNT_HOLDER_TYPE_COMPANY) {
                            $account_holder_type = 'company';
                        }
                    }

                    $update = \Stripe\Customer::updateSource($getUser->customer_id, $update = $bankAccounts->bank_id, [
                        'account_holder_name' => $accountInfo->account_holder_name,
                        'account_holder_type' => $account_holder_type
                    ]);

                    if ($update->account_holder_name == $accountInfo->account_holder_name) {

                        $bankAccounts->updateAttributes([
                            'type_id' => $accountInfo->type_id,
                            'account_holder_name' => $accountInfo->account_holder_name
                        ]);

                        $response['success'] = \Yii::t('app', 'Bank Account details updated sucessfully');
                    } else {
                        $response['error'] = \Yii::t('app', 'Issue updating Bank Account details ');
                    }
                } else {
                    $response['error'] = \Yii::t('app', 'User not found');
                }
            } else {
                $response['error'] = \Yii::t('app', 'No Data Found');
            }
        } else {
            $response['error'] = \Yii::t('app', 'No Data Found');
        }
        $this->response = $response;
    }

    public function actionDeleteAccount($id = null)
    {
        $response = [];
        // update a card
        $stripeDetails = User::getStripeDetails();
        \Stripe\Stripe::setApiKey($stripeDetails->secret_key->value);
        \Stripe\Stripe::setApiVersion(STRIPE_API_VERSION);

        $getUser = \Yii::$app->user->identity;

        $accountInfo = AccountInfo::find()->where([
            'user_id' => $getUser->id,
            'id' => $id
        ])->one();

        if (! empty($accountInfo)) {

            if (! empty($getUser)) {

                try {

                    $account_deleted = \Stripe\Customer::deleteSource($getUser->customer_id, $accountInfo->bank_id);
                    // $account_deleted = \Stripe\Account::deleteExternalAccount($getUser->account_id, $accountInfo->bank_id);
                    if ($account_deleted->deleted) {

                        if ($accountInfo->delete()) {

                            $response['status'] = self::API_OK;
                            $response['success'] = \Yii::t('app', 'Bank Account removed sucessfully');
                        } else {

                            $response['error'] = $accountInfo->getErrors();
                        }
                    }
                } catch (\yii\base\Exception $e) {
                    $response['error'] = $e->getMessage();
                }
            } else {
                $response['error'] = 'User not found';
            }
        } else {
            $response['error'] = 'No Data Found';
        }
        $this->response = $response;
    }

    // set default account
    public function actionSetDefaultAccount($id)
    {
        $response = [];

        \Yii::$app->response->format = 'json';

        $bankAccounts = AccountInfo::find()->where([
            'user_id' => \Yii::$app->user->id,
            'is_default_account' => AccountInfo::IS_DEFAULT
        ])->andWhere([
            '!=',
            'id',
            $id
        ]);

        $sendAccounts = AccountInfo::findOne($id);

        if (! empty($sendAccounts) && $sendAccounts->is_default_account == AccountInfo::NOT_DEFAULT) {
            if (! empty($sendAccounts)) {
                $stripe_key = \Yii::$app->settings->stripeKey->config->secret_key;
                \Stripe\Stripe::setApiKey($stripe_key);
                \Stripe\Stripe::setApiVersion(STRIPE_API_VERSION);
                $update = \Stripe\Customer::update(\Yii::$app->user->identity->customer_id, [
                    'default_source' => $sendAccounts->bank_id
                ]);
                $sendAccounts->is_default_account = AccountInfo::IS_DEFAULT;
                if ($sendAccounts->save(false, [
                    'is_default_account'
                ])) {
                    $response['status'] = self::API_OK;
                    $response['success'] = \Yii::t('app', 'Bank Account added as default succesfully.');
                }
            }
            foreach ($bankAccounts->each() as $accts) {
                $accts->is_default_account = AccountInfo::NOT_DEFAULT;
                $accts->save(false, [
                    'is_default_account'
                ]);
            }
        }

        $this->response = $response;
    }

    public function actionViewAccount($id)
    {
        $response = [];

        if (! empty($id)) {

            $stripeDetails = User::getStripeDetails();
            \Stripe\Stripe::setApiKey($stripeDetails->secret_key->value);
            \Stripe\Stripe::setApiVersion(STRIPE_API_VERSION);

            $bankAccounts = AccountInfo::findOne($id);

            if (! empty($bankAccounts)) {

                $response['status'] = self::API_OK;
                $response['detail'] = $bankAccounts->asJson();
            } else {
                $response['error'] = \Yii::t('app', 'No such bank account found');
            }
        } else {
            $response['error'] = \Yii::t('app', 'No Data Found');
        }

        $this->response = $response;
    }

    /**
     * This method allows you to retrive all bank accounts of a user
     *
     * @ref link https://stripe.com/docs/api/customer_bank_accounts/list?lang=php
     */
    public function actionGetAccounts()
    {
        $data = [];
        $bankAccounts = AccountInfo::find()->where([
            'user_id' => \Yii::$app->user->id
        ]);
        $list = [];
        if (! empty($bankAccounts)) {

            foreach ($bankAccounts->each() as $bankAccount) {
                $list[] = $bankAccount->asJson();
            }
            $data['status'] = self::API_OK;
            $data['list'] = $list;
        } else {
            $data['message'] = \Yii::t('app', 'No data Found.');
        }

        $this->response = $data;
    }

    /**
     * VERIFY A BANK ACCOUNT
     * A customer's bank account must first be verified before it can be charged.
     * Stripe supports instant verification using Plaid for many of the most popular banks. If your customer's bank is not supported or you do not wish to integrate with Plaid, you must manually verify the customer's bank account using the API.
     *
     * ARGUMENTS
     *
     * @id REQUIRED
     *
     * The ID of the source to be verified.
     *
     * @customer REQUIRED
     *
     * @amounts
     *
     * optional
     * Two positive integers, in cents, equal to the values of the microdeposits sent to the bank account.
     *
     * RETURNS
     * Returns the bank account object with a status of verified.
     *
     * @ref link https://stripe.com/docs/api/customer_bank_accounts/verify?lang=php
     */
    public function actionVerifyAccount($id)
    {
        $data = [];
        $stripeDetails = User::getStripeDetails();
        \Stripe\Stripe::setApiKey($stripeDetails->secret_key->value);
        \Stripe\Stripe::setApiVersion(STRIPE_API_VERSION);
        if (! empty(\Yii::$app->user->identity->customer_id)) {
            $bankAccount = AccountInfo::find()->where([
                'user_id' => \Yii::$app->user->id,
                'id' => $id
            ])->one();

            if (! empty($bankAccount)) {
                if (! empty($bankAccount->bank_id)) {
                    try {
                        $bank_account = \Stripe\Customer::retrieveSource(\Yii::$app->user->identity->customer_id, $bankAccount->bank_id);
                        $verify = $bank_account->verify([
                            'amounts' => [
                                32,
                                45
                            ]
                        ]);
                        if ($verify->status == "verified") {
                            $data['status'] = self::API_OK;
                            $data['verifiedJson'] = $verify;
                            $data['message'] = \Yii::t('app', 'Account has been verified Succssfully.');
                            $bankAccount->is_verify = AccountInfo::VERIFIED;
                            $bankAccount->updateAttributes([
                                'is_verify'
                            ]);
                        } else {
                            $data['message'] = \Yii::t('app', 'Unable to verify');
                            $data['errorStatus'] = $verify->status;
                            $data['verifiedJson'] = $verify;
                        }
                    } catch (Exception $e) {
                        $data['error'] = $e->getMessage();
                    }
                } else {
                    $data['message'] = \Yii::t('app', 'Bank id is invalid.');
                }
            } else {
                $data['message'] = \Yii::t('app', 'Account info not found.');
            }
        } else {
            $data['message'] = \Yii::t('app', 'Please add customer on stripe first');
        }

        $this->response = $data;
    }

    // Below Api's are for Customer Credit Cards
    public function actionAddCard()
    {
        $data = [];
        $current_year = date("Y");
        $current_month = date("m");
        $payment_details = Gateway::find()->one();
        $stripe_keys = json_decode($payment_details->value);
        $secret_key = $stripe_keys->secret_key;
        $publishable_key = $stripe_keys->publishable_key;
        if (! empty($payment_details)) {
            $user_id = \Yii::$app->user->id;
            $post = \Yii::$app->request->post();
            $model = new User();
            $model->loadDefaultValues();
            if (! empty($post)) {
                $user_details = User::find()->where([
                    'id' => $user_id
                ])->one();
                if (! empty($user_details->customer_id)) {
                    $customer_name = $post['customer_name'];
                    $cardNumber = $post['card_number'];
                    $expiry_details = explode("/", $post['expiry_date']);
                    $expiry_month = (int) $expiry_details[0];
                    $expiry_year = (int) $expiry_details[1];
                    $securityCode = $post['cvc'];
                    $customer_id = $user_details->customer_id;
                    $is_defailt = $post['is_default'];
                    try {
                        \Stripe\Stripe::setApiKey($secret_key);
                        \Stripe\Stripe::setApiVersion(STRIPE_API_VERSION);
                        $token = \Stripe\Token::create([
                            'card' => [
                                'number' => $cardNumber,
                                'exp_month' => $expiry_month,
                                'exp_year' => $expiry_year,
                                'cvc' => $securityCode,
                                'name' => $customer_name
                            ]
                        ]);

                        if (! empty($token)) {
                            try {
                                \Stripe\Stripe::setApiKey($secret_key);
                                \Stripe\Stripe::setApiVersion(STRIPE_API_VERSION);
                                $card = \Stripe\Customer::createSource($customer_id, [
                                    'source' => $token->id
                                ]);

                                if (! empty($card)) {
                                    $card_id = $card['id'];
                                    if ($is_defailt == User::STATE_ACTIVE) {
                                        \Stripe\Customer::update($customer_id, [
                                            'default_source' => $card_id
                                        ]);
                                    }
                                    $data['detail'] = $user_details->asJson();
                                    $data['message'] = "Card Add Successfully.";
                                }
                            } catch (\Exception $e) {
                                $this->setStatus(400);
                                $data['message'] = $e->getMessage();
                            }
                        }
                    } catch (\Exception $e) {
                        $this->setStatus(400);
                        $data['message'] = $e->getMessage();
                    }
                } else {
                    $data['message'] = "No Customer Add.";
                }
            }
        } else {
            $data['message'] = "Data not posted.";
        }
        return $data;
    }

    /**
     * Card List
     *
     * @param
     *
     * @return $cardlist
     */
    public function actionCardList()
    {
        $data = [];
        $payment_details = Gateway::find()->one();
        $stripe_keys = json_decode($payment_details->value);
        $secret_key = $stripe_keys->secret_key;
        $publishable_key = $stripe_keys->publishable_key;
        if (! empty($payment_details)) {
            $user_id = \Yii::$app->user->id;
            $post = \Yii::$app->request->post();
            $model = new User();
            $model->loadDefaultValues();
            $user_details = User::find()->where([
                'id' => $user_id
            ])->one();
            if (! empty($user_details->customer_id)) {

                $customer_id = $user_details->customer_id;
                try {
                    \Stripe\Stripe::setApiKey($secret_key);
                    \Stripe\Stripe::setApiVersion(STRIPE_API_VERSION);
                    $stripe = \Stripe\Customer::retrieve($customer_id);

                    $defaultSource = $stripe->default_source;

                    $cards_details = \Stripe\Customer::allSources($customer_id, [
                        'object' => 'card',
                        'limit' => 3
                    ]);

                    $cardlist = $cards_details['data'];
                    if (! empty($cards_details['data'])) {

                        foreach ($cardlist as $key => $value) {

                            if ($defaultSource == $value->id) {
                                $cardlist[$key]->is_default = User::STATE_ACTIVE;
                            } else {
                                $cardlist[$key]->is_default = User::STATE_INACTIVE;
                            }
                        }
                    }

                    $this->setStatus(200);
                    $data['card-details'] = $cardlist;
                } catch (Exception $e) {
                    $data['message'] = $e->getMessage();
                }
            } else {
                $data['message'] = "No Customer Add.";
            }
        } else {
            $data['message'] = "Data not posted.";
        }
        return $data;
    }

    /**
     * Delete Card
     *
     * @param
     *            card_id
     *            
     * @return $message
     */
    public function actionCardDelete($card_id = null)
    {
        $data = [];
        $payment_details = Gateway::find()->one();
        if (! empty($payment_details)) {
            $stripe_keys = json_decode($payment_details->value);
            $secret_key = $stripe_keys->secret_key;
            $publishable_key = $stripe_keys->publishable_key;

            $user_id = \Yii::$app->user->id;
            $post = \Yii::$app->request->post();
            $model = new User();
            $model->loadDefaultValues();
            if (! empty($card_id)) {
                $user_details = User::find()->where([
                    'id' => $user_id
                ])->one();
                if (! empty($user_details->customer_id)) {
                    $customer_id = $user_details->customer_id;
                    try {
                        \Stripe\Stripe::setApiKey($secret_key);
                        \Stripe\Stripe::setApiVersion(STRIPE_API_VERSION);
                        \Stripe\Customer::deleteSource($customer_id, $card_id, []);
                        $this->setStatus(200);
                        $data['success'] = \Yii::t('app', 'Delete Card Add Successfully.');
                    } catch (Exception $e) {

                        $data['message'] = $e->getMessage();
                    }
                } else {
                    $data['message'] = "No Customer Add.";
                }
            }
        } else {
            $data['message'] = "Data not posted.";
        }
        return $data;
    }

    /**
     * SET DEFAULT CARD
     *
     * @param
     *            card_id
     *            
     * @return $message
     */
    public function actionSetDefault($card_id = null)
    {
        $data = [];
        $payment_details = Gateway::find()->one();
        $stripe_keys = json_decode($payment_details->value);
        $secret_key = $stripe_keys->secret_key;
        $publishable_key = $stripe_keys->publishable_key;
        if (! empty($payment_details)) {
            $user_id = \Yii::$app->user->id;
            $post = \Yii::$app->request->post();
            $model = new User();
            $model->loadDefaultValues();
            if ($card_id != '') {
                $user_details = User::find()->where([
                    'id' => $user_id
                ])->one();
                if (! empty($user_details->customer_id)) {
                    $customer_id = $user_details->customer_id;
                    try {
                        \Stripe\Stripe::setApiKey($secret_key);
                        \Stripe\Stripe::setApiVersion(STRIPE_API_VERSION);
                        \Stripe\Customer::update($customer_id, [
                            'default_source' => $card_id
                        ]);
                        $this->setStatus(200);
                        $data['message'] = \Yii::t('app', 'Set Card as Default.');
                    } catch (Exception $e) {

                        $data['message'] = $e->getMessage();
                    }
                } else {
                    $data['message'] = \Yii::t('app', 'No Customer Add.');
                }
            }
        } else {
            $data['message'] = \Yii::t('app', 'Data not posted.');
        }
        return $data;
    }

    public function actionOrderPayment()
    {
        $data = [];
        $post = \yii::$app->request->post();
        $payment = new PaymentTransaction();
        $payment->payer_id = \Yii::$app->user->id;
        $payment->model_id = $post['PaymentTransaction']['order_id'];
        $payment->model_type = Charity::className();
        $payment->name = \Yii::$app->user->identity->full_name;
        $payment->email = \Yii::$app->user->identity->email;
        if ($payment->load($post)) {
            $payment->currency = PaymentTransaction::CURRENCY;
            if ($payment->save()) {
                $this->setStatus(200);
                $data['message'] = 'payment  Successfull.';
            }
        } else {
            $this->setStatus(400);
            $data['message'] = 'data not posted';
        }
        return $data;
    }

    public function actionBookingPayment()
    {
        $data = [];
        $payment_details = Gateway::find()->one();
        $stripe_keys = json_decode($payment_details->value);
        $secret_key = $stripe_keys->secret_key;
        $publishable_key = $stripe_keys->publishable_key;
        if (! empty($payment_details)) {
            $user_id = \Yii::$app->user->id;
            $post = \Yii::$app->request->post();

            // Create Customer
            $user_details = User::find()->where([
                'id' => $user_id
            ])->one();

            if (! empty($user_details->customer_id)) {

                try {
                    \Stripe\Stripe::setApiKey($secret_key);
                    \Stripe\Stripe::setApiVersion(STRIPE_API_VERSION);
                    $stripe_charge = \Stripe\Charge::create([
                        "amount" => $post['Transaction']['price'] * 100,
                        "currency" => 'usd',
                        "customer" => $user_details->customer_id,
                        "description" => "Added to wallet",
                        "receipt_email" => $user_details->email
                    ]);

                    if (! empty($stripe_charge)) {
                        $transaction = new Transaction();
                        $transaction->transaction_id = $stripe_charge['id'];
                        $transaction->currency = 'usd';
                        $transaction->model_id = $post['Transaction']['booking_id'];
                        $transaction->name = $user_details->full_name;
                        $transaction->email = $user_details->email;
                        $transaction->gateway_type = "1";
                        $transaction->description = $stripe_charge;
                        $transaction->amount = number_format(($post['Transaction']['price'] / 100), 2, '.', ' ');
                        if ($stripe_charge['paid'] == Transaction::PAID) {
                            $status = Transaction::PAYMENT_STATUS_SUCCESS;
                            $transaction->payment_status = (int) $status;
                        } else {
                            $status = Transaction::PAYMENT_STATUS_FAIL;
                            $transaction->payment_status = (int) $status;
                        }
                        if ($transaction->save()) {
                            if ($stripe_charge['paid'] == Transaction::PAID) {
                                $this->setStatus(200);
                                $data['message'] = \Yii::t('app', 'Order  Successfully.');
                            } else {
                                $this->setStatus(400);
                                $data['message'] = \Yii::t('app', 'Please Try Again.');
                            }
                        } else {
                            $this->setStatus(400);
                            $data['message'] = \Yii::t('app', 'Please Try Again.');
                        }
                    }
                } catch (Exception $e) {
                    $this->setStatus(400);
                    $data['message'] = $e->getMessage();
                }
            }
        } else {

            $data['message'] = \Yii::t('app', 'Data not posted.');
        }
        return $data;
    }
}
