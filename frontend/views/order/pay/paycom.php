<form action="https://checkout.paycom.uz" method="post" id="payment-form">
    <input type="hidden" name="merchant" value="<?= Yii::$app->params['payment_connections']['paycom']['merchant_id'] ?>">
    <input type="hidden" name="account[order_id]" value="<?= (int)$order->id ?>">
    <input type="hidden" name="lang" value="<?= Yii::$app->language ?>">
    <input type="hidden" name="amount" value="<?= $order->checkout_price * 100 ?>">
    <input type="hidden" name="currency" value="860">
    <input type="hidden" name="callback" value="<?= Yii::$app->urlManager->createAbsoluteUrl(['user/course/index']) ?>">
    <input type="hidden" name="description" value="<?= Yii::t('app', 'Оплата за заказ') ?>">
</form>


<script>
    $('#payment-form').trigger('submit');
</script>
