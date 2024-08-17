<?php

namespace frontend\controllers;

use backend\models\Produse;
use backend\models\ProduseDetalii;
use backend\models\Restaurante;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\Basket;
use frontend\models\BasketItem;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use frontend\models\FormularComanda;
use Symfony\Component\HttpFoundation\UrlHelper;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * Site controller
 */
class SiteController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions() {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
            'captcha' => [
                'class' => \yii\captcha\CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex() {
        //$this->title='Acasă - DioBistro Călărași';
        return $this->render('_home_view');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin() {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
                    'model' => $model,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact() {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        }

        return $this->render('contact', [
                    'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout() {
        return $this->render('about');
    }

    public function actionPaginaMeniu() {
        return $this->render('_meniu_view');
    }

    public function actionPaginaHome() {
        return $this->render('_home_view');
    }

    public function actionPaginaContact() {
        return $this->render('_contact_view');
    }

    public function actionSchimbaCategorie($idCategorie) {
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_categorie_view', ['id' => $idCategorie]);
        }
        return $this->renderPartial('_categorie_view', ['id' => $idCategorie]);
    }

    public function actionAfiseazaProdus($idProdus) {
        return $this->renderAjax('_produs_view', ['id' => $idProdus]);
    }

    public function actionRestaurantInchis() {
        $dayOfWeek = date('w');
        if ($dayOfWeek == 0) {
            $dayOfWeek = 7;
        }
        $model = \backend\models\IntervaleLivrare::findOne(['restaurant' => 10, 'ziua_saptamanii' => $dayOfWeek, 'program' => 1]);
        $newTimezone = new \DateTimeZone('Europe/Bucharest');
        $currentDateTime = new \DateTime('now', $newTimezone);
        $startDateTime = \DateTime::createFromFormat('H:i', $model->ora_inceput, $newTimezone);
        $endDateTime = \DateTime::createFromFormat('H:i', $model->ora_sfarsit, $newTimezone);
        $currentDate = $currentDateTime->format('Y-m-d');
        $startDateTime->setDate($currentDateTime->format('Y'), $currentDateTime->format('m'), $currentDateTime->format('d'));
        $endDateTime->setDate($currentDateTime->format('Y'), $currentDateTime->format('m'), $currentDateTime->format('d'));

        $response = array(
            'inchis' => 0,
            'message' => "mesaj"
        );

        $setariVanzari = \backend\models\SetariVanzari::findOne(['restaurant'=>10]);
        if ($setariVanzari->vanzari_oprite){
            $response['inchis']=1;
            $response['message']=$setariVanzari->mesaj_oprit;
            return json_encode($response);
        }
        if ($currentDateTime >= $startDateTime && $currentDateTime <= $endDateTime) {
            return json_encode($response);
        } else {
            $response['inchis']=1;
            $response['message']=$setariVanzari->mesaj_generic;
            return json_encode($response);
        }
    }

    public function actionStergeDinCos() {
        $request = \Yii::$app->request;
        $idProdus = $request->post('idProdus');
        $basket = \Yii::$app->session->get('basket');
        $produs = ProduseDetalii::findOne($idProdus);
        if (is_null($produs)) {
            throw new BadRequestHttpException('Id produs inexistent');
        }

        if (!is_null($basket) && array_key_exists($idProdus, $basket->basketItems)) {
            $currentItem = $basket->basketItems[$idProdus];
            $currentItem->cantitate -= 1;
            if ($currentItem->cantitate == 0) {
                unset($basket->basketItems[$idProdus]);
            }
        }
        \Yii::$app->session->set('basket', $basket);
    }

    public function actionContinutCos() {
        //  Yii::$app->session->destroy();
        $basket = \Yii::$app->session->get('basket');
        if (is_null($basket))
            $basket = new Basket();
        return $this->renderAjax('_cos_view', [
                    'model' => $basket,
        ]);
    }

    public function actionIsCosEmpty(){
        $basket = \Yii::$app->session->get('basket');
        return count($basket->basketItems);
    }

    public function actionAdaugaInCos() {
        $request = \Yii::$app->request;
        $idProdus = $request->post('idProdus');
        $cantitate = $request->post('cantitate');
        $basket = \Yii::$app->session->get('basket');
        //  $produs = Produse::findOne($idProdus);
        $produsDetaliu = ProduseDetalii::findOne($idProdus);
        if (is_null($produsDetaliu)) {
            throw new BadRequestHttpException('Id produs inexistent');
        }
        $produs = $produsDetaliu->produs0;

        if (is_null($produs)) {
            throw new BadRequestHttpException('Id produs inexistent');
        }
        if (is_null($basket)) {
            $basket = new Basket(['metodaPlata' => 1]);
            $basket->basketItems[$idProdus] = new BasketItem(['idProdus' => $idProdus, 'cantitate' => 1, 'pret' => $produsDetaliu->pret, 'denumire' => $produsDetaliu->denumireCompleta]);
        } else {
            if (array_key_exists($idProdus, $basket->basketItems)) {
                $currentItem = $basket->basketItems[$idProdus];
                $currentItem->cantitate = $cantitate;
            } else {
                $currentItem = new BasketItem(['idProdus' => $idProdus, 'cantitate' => $cantitate, 'pret' => $produsDetaliu->pret, 'denumire' => $produsDetaliu->denumireCompleta]);
            }
            $basket->basketItems[$idProdus] = $currentItem;
            if ($cantitate == 0) {
                unset($basket->basketItems[$idProdus]);
            }
        }
        return Json::encode($basket);
        // \Yii::$app->session->set('basket', $basket);
        // return $this->renderAjax('_cos_view', [
        //     'model' => $basket,
        // ]);
    }

    public function actionProceseazaComanda() {
        $basket = \Yii::$app->session->get('basket');
        $model = new FormularComanda();
        $model->metodaPlata = 1;
        $model->tipLocuinta = 1;
        $post = Yii::$app->request->post();
        if (count($post) > 0) {
            $value = $post['FormularComanda']['tipLocuinta'];
            $model->load($post);
            $model->tipLocuinta = intval($value);
            if ($model->validate()) {
                if ($model->saveComanda($basket) && $model->sendMail($basket)) {
                    Yii::$app->session->remove('basket');
                    $comanda = \backend\models\Comenzi::findOne(['id'=>$model->idComanda]);
                    Yii::$app->session->set('comanda', $comanda);
                    $this->redirect(Url::to(['site/pagina-home']));
                }

                // if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                //     Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
                // } else {
                //     Yii::$app->session->setFlash('error', 'There was an error sending your message.');
                // }
            }
        }

        return $this->render('comanda', ['model' => $model, 'basket' => $basket]);
    }

    public function actionGenereazaModalProdus() {
        $request = \Yii::$app->request;
        $idProdus = $request->post('idProdus');
        $produs = Produse::findOne($idProdus);
        if (is_null($produs)) {
            throw new BadRequestHttpException('Id produs inexistent');
        }
        $detalii = array_map(function ($el) {
            return ['id' => $el->id, 'descriere' => $el->descriere, 'pret' => $el->pret];
        }, $produs->produseDetalii);
        $currentItem = new BasketItem(['idProdus' => $idProdus, 'produseDetalii' => $detalii, 'cantitate' => 1, 'pret' => $detalii[0]['pret'], 'denumire' => $produs->nume]);
        return $this->renderAjax('_produs_in_cos_view', [
                    'generare' => true,
                    'model' => $currentItem,
        ]);
    }

    public function actionProdusAdaugaInCos() {
        $request = \Yii::$app->request;
        $idProdus = $request->post('idProdus');
        $cantitate = $request->post('cantitate');
        $basket = \Yii::$app->session->get('basket');
        //$produs = Produse::findOne($idProdus);
        $produsDetaliu = ProduseDetalii::findOne($idProdus);
        if (is_null($produsDetaliu)) {
            throw new BadRequestHttpException('Id produs inexistent');
        }
        $produs = $produsDetaliu->produs0;

        $detalii = array_map(function ($el) {
            return ['id' => $el->id, 'descriere' => $el->descriere, 'pret' => $el->pret];
        }, $produs->produseDetalii);

        if (is_null($basket)) {
            $basket = new Basket(['metodaPlata' => 1]);
            $currentItem = new BasketItem(['idProdus' => $idProdus, 'produseDetalii' => $detalii, 'pdId' => $produsDetaliu->id, 'cantitate' => $cantitate, 'pret' => $produsDetaliu->pret, 'denumire' => $produsDetaliu->denumireCompleta]);
            $currentItem->produseDetalii = $detalii;
            $basket->basketItems[$idProdus] = $currentItem;
        } else {
            if (array_key_exists($idProdus, $basket->basketItems)) {
                $currentItem = $basket->basketItems[$idProdus];
                $currentItem->cantitate += 1;
            } else {
                $currentItem = new BasketItem(['idProdus' => $idProdus, 'produseDetalii' => $detalii, 'pdId' => $produsDetaliu->id, 'cantitate' => $cantitate, 'pret' => $produsDetaliu->pret, 'denumire' => $produsDetaliu->denumireCompleta]);
            }
            $currentItem->produseDetalii = $detalii;
            $basket->basketItems[$idProdus] = $currentItem;
        }
        //  var_dump($detalii);
        // var_dump($currentItem);
        //exit();
        \Yii::$app->session->set('basket', $basket);
        return $this->renderAjax('_produs_in_cos_view', [
                    'generare' => false,
                    'model' => $currentItem,
        ]);
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup() {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('signup', [
                    'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset() {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
        }

        return $this->render('requestPasswordResetToken', [
                    'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token) {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
                    'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token) {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if (($user = $model->verifyEmail()) && Yii::$app->user->login($user)) {
            Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
            return $this->goHome();
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail() {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
                    'model' => $model
        ]);
    }

    public function actionPayment()
{
    $request = Yii::$app->request;
    $restaurant = Restaurante::findOne(9);

    if (!$restaurant) {
        throw new NotFoundHttpException('Restaurant not found.');
    }

    if ($request->isPost) {
        $paymentMethodId = $request->post('paymentMethodId');
        $amount = 5000; // Amount in cents (i.e., $50.00)
        $productDetails = [
            'name' => $request->post('productName'),
            'description' => $request->post('productDescription'),
            'quantity' => $request->post('productQuantity'),
        ];

        $stripe = Yii::$app->stripe;
        $stripe->setApiKey('sk_test_51PWJWpAsMoeIDeIqqe3ewLdVa0Ki6Nm3BhS5I0AlTmTKkx0QMqdfYlTJu4myBTt45mfQImNLqjqnRVp8pavMw3ZN00EiRvNRIc');
        $paymentIntent = $stripe->createPaymentIntent($amount, $productDetails);

        try {
            $paymentIntent = $stripe->confirmPaymentIntent($paymentIntent->id, $paymentMethodId);

            if ($paymentIntent->status == 'succeeded') {
                // Payment successful, handle post-payment actions
                Yii::$app->session->setFlash('success', 'Payment successful!');
                return $this->refresh();
            } else {
                Yii::$app->session->setFlash('error', 'Payment failed. Please try again.');
            }
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Error: ' . $e->getMessage());
        }
    }

    return $this->render('payment', ['restaurant' => $restaurant]);
}


}
