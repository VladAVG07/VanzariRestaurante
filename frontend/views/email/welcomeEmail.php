<!-- views/email/welcomeEmail.php -->
<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */

$this->title = 'Welcome Email';

?>
<div class="header">
    <h1>Welcome to Our Website</h1>
</div>
<div class="content">
    <p>Hello <?= Html::encode($name) ?>,</p>
    <p>Thank you for signing up with our service. We're excited to have you on board!</p>
    <p>If you have any questions or need assistance, feel free to contact us.</p>
    <p>Best regards,<br>Team XYZ</p>
</div>
<div class="footer">
    <p>&copy; 2024 Dio Bistro. Toate drepturile rezervate.</p>
</div>
