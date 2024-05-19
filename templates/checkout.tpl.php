<?php

/** @var array $items */
/** @var Session $session */

declare(strict_types=1);

$vendors = [];
$totalPrice = 0;

foreach ($items as $item) {
    if (!isset($vendors[$item->user_id])) {
        $vendors[$item->user_id]['username'] = $item->username;
        $vendors[$item->user_id]['itemsTotalPrice'] = 0;
    }

    $totalPrice += $item->price;
    $vendors[$item->user_id]['itemsTotalPrice'] += $item->price;
}

?>


<section id="payment">
    <h2 class="title">Payment method:</h2>

    <div class="card">

        <?php drawErrors($session->getErrorMessages()) ?>

        <form id="buy" class="simple-form" action="../actions/action_checkout.php" method="post">

            <?= Request::generateCsrfTokenInput() ?>

            <div class="radio-container">
                <input type="radio" name="cardType" value="visa" id="visa" onclick="toggleFields()" required>
                <label for="visa" style="background-image: url('/assets/img/visa.png')"></label>

                <input type="radio" name="cardType" value="mbWay" id="mbWay" onclick="toggleFields()" required>
                <label for="mbWay" style="background-image: url('/assets/img/mbway.jpeg')"></label>
            </div>

            <div id="phoneLabel" style="display: none">
                <label>
                    Phone <input type="text" name="phone" >
                </label>
            </div>

            <div id="visaFields" style="display: none">
                <label>
                    Card number <input type="text" id="formattedCardNumber" autocomplete="off">
                    <input type="hidden" name="cardNumber" id="cardNumber">
                </label>

                <label>
                    CVV <input type="text" name="cvv" id="cvv" >
                </label>
            </div>
        </form>
    </div>
</section>

<script>
    function toggleFields() {
        const visaFields = document.getElementById('visaFields');
        const phoneLabel = document.getElementById('phoneLabel');

        if (document.getElementById('mbWay').checked) {
            visaFields.style.display = 'none';
            phoneLabel.style.display = 'block';
        } else {
            visaFields.style.display = 'block';
            phoneLabel.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        let formattedCardInput = document.getElementById('formattedCardNumber');
        let rawCardInput = document.getElementById('cardNumber');
        let cvvInput = document.getElementById('cvv');

        formattedCardInput.addEventListener('input', function() {
            let rawInput = this.value.split(' ').join(''); // Remove spaces for processing
            if (rawInput.length > 16) {
                rawInput = rawInput.substring(0, 16); // Limit to 16 digits
            }

            let formattedInput = '';
            if (rawInput.length > 0) {
                formattedInput = rawInput.match(new RegExp('.{1,4}', 'g')).join(' '); // Add spaces every 4 digits
            }
            
            this.value = formattedInput; // Update visible input field
            rawCardInput.value = rawInput; // Update hidden input field
        });

        cvvInput.addEventListener('input', function() {
            if (this.value.length > 3) {
                this.value = this.value.substring(0, 3); // Limit CVV to 3 digits
            }
        });
    });

</script>
