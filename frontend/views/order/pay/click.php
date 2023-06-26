<form action="https://my.click.uz/services/pay" method="get" id="payment-form">
    <input type="hidden" name="merchant_id" value="<?= Yii::$app->params['payment_connections']['click']['merchant_id'] ?>"/>
    <input type="hidden" name="service_id" value="<?= Yii::$app->params['payment_connections']['click']['service_id'] ?>"/>
    <input type="hidden" name="transaction_param" value="<?= (int)$order->id ?>"/>
    <input type="hidden" name="amount" value="<?= $order->checkout_price ?>">
    <input type="hidden" name="return_url" value="<?= Yii::$app->urlManager->createAbsoluteUrl(['user/course/index']) ?>"/>
</form>


<script>
    $('#payment-form').trigger('submit');
</script>
